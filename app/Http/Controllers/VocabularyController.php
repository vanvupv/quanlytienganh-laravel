<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vocabulary\VocabWord;
use App\Models\Vocabulary\VocabStage;
use App\Models\Vocabulary\VocabProperty;
use App\Models\Vocabulary\VocabStudyLog;
use App\Models\Vocabulary\VocabReview;

class VocabularyController extends Controller
{
    /**
     * Display the dynamic vocabulary growth tree and storage table.
     */
    public function index(Request $request)
    {
        // Get active student user or fallback to first database user for demo persistence
        $user = \Illuminate\Support\Facades\Auth::user() ?: \App\Models\User::first();
        $userId = $user ? $user->id : 1;

        // 1. Fetch all base words along with their stage properties, study logs, and reviews filtered by active user
        $words = VocabWord::with([
            'properties.stage',
            'studyLogs' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'reviews' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }
        ])->get();

        // 2. Select focused active word (default to first seeded word)
        $activeWordId = $request->input('word_id');
        $activeWord = null;
        if ($activeWordId) {
            $activeWord = $words->firstWhere('id', $activeWordId);
        }
        if (!$activeWord && $words->isNotEmpty()) {
            $activeWord = $words->first();
        }

        // 3. Fetch all growth stages ordered chronologically with their properties and words
        $stages = VocabStage::with('properties.word')->orderBy('order', 'asc')->get();

        // 4. Construct the progressive Growth Tree for the active word
        $growthTree = [];
        if ($activeWord) {
            foreach ($stages as $stage) {
                // Fetch all properties introduced in or prior to this stage
                $accumulated = VocabProperty::where('word_id', $activeWord->id)
                    ->whereHas('stage', function ($query) use ($stage) {
                        $query->where('order', '<=', $stage->order);
                    })
                    ->with('stage')
                    ->get()
                    ->map(function ($prop) use ($stage) {
                        // Dynamically assign 'new' class if introduced in the current stage, 'old' if accumulated
                        $prop->status = ($prop->stage_id == $stage->id) ? 'new' : 'old';
                        return $prop;
                    });

                $growthTree[$stage->id] = [
                    'stage' => $stage,
                    'properties' => $accumulated
                ];
            }
        }

        return view('vocabularies.voca', compact('words', 'activeWord', 'stages', 'growthTree'));
    }

    /**
     * Store a new vocabulary word.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'word' => 'required|string|max:255|unique:vocab_words,word',
            'uk_phonetic' => 'nullable|string',
            'us_phonetic' => 'nullable|string',
        ]);

        $word = VocabWord::create($data);
        return response()->json($word, 201);
    }

    /**
     * Show detailed JSON for a word.
     */
    public function show($id)
    {
        $word = VocabWord::with('properties.stage')->findOrFail($id);
        return response()->json($word);
    }

    /**
     * Update a vocabulary word.
     */
    public function update(Request $request, $id)
    {
        $word = VocabWord::findOrFail($id);
        $data = $request->validate([
            'word' => 'sometimes|required|string|max:255|unique:vocab_words,word,' . $word->id,
            'uk_phonetic' => 'nullable|string',
            'us_phonetic' => 'nullable|string',
        ]);

        $word->update($data);
        return response()->json($word);
    }

    /**
     * Delete a word.
     */
    public function destroy($id)
    {
        $word = VocabWord::findOrFail($id);
        $word->delete();
        return response()->json(null, 24);
    }

    /**
     * Display the student vocabulary active recall quiz view.
     */
    public function review($id)
    {
        $word = VocabWord::with('properties.stage')->findOrFail($id);
        return view('vocabularies.review', compact('word'));
    }

    /**
     * Submit and grade the vocabulary active recall quiz.
     */
    public function submitReview(Request $request, $id)
    {
        $activeWord = VocabWord::with('properties.stage')->findOrFail($id);
        
        // Ensure student user is authenticated or get default
        $user = \Illuminate\Support\Facades\Auth::user() ?: \App\Models\User::first();
        if (!$user) {
            return redirect()->back()->with('error', 'Học viên cần đăng nhập để làm bài ôn tập.');
        }

        $oldUnlockedIds = $user->achievements()->pluck('achievements.id')->toArray();

        $durationSeconds = intval($request->input('duration_seconds', 0));
        $spellingAnswer = trim($request->input('spelling'));

        // 1. Fuzzy normalization helper
        $normalize = function ($s) {
            $s = mb_strtolower($s, 'UTF-8');
            $s = preg_replace('/[.,?!;:+\-_\/*()\'"“”`~<>?@#$%^&|\\\]/', '', $s);
            $s = preg_replace('/\s+/', '', $s);
            return trim($s);
        };

        // 2. Grade spelling (30 points)
        $isSpellingCorrect = (strtolower(trim($spellingAnswer)) === strtolower(trim($activeWord->word)));
        $spellingScore = $isSpellingCorrect ? 30.0 : 0.0;

        // 3. Grade multiple POS & meanings (40 points)
        $props = $activeWord->properties;
        $totalPropsCount = $props->count();
        $meaningResults = [];
        $meaningScore = 0.0;

        if ($totalPropsCount > 0) {
            $sharePerMeaning = 40.0 / $totalPropsCount;
            foreach ($props as $prop) {
                $studentAns = trim($request->input('meanings.' . $prop->id));
                $isCorrect = ($normalize($studentAns) === $normalize($prop->meaning));
                if ($isCorrect) {
                    $meaningScore += $sharePerMeaning;
                }

                $meaningResults[] = [
                    'stage' => $prop->stage->name,
                    'pos' => $prop->part_of_speech,
                    'student' => $studentAns,
                    'actual' => $prop->meaning,
                    'is_correct' => $isCorrect
                ];
            }
        } else {
            $meaningScore = 40.0; // Auto points if no properties found
        }

        // 4. Grade example contextual translation (30 points)
        $propsWithExamples = $props->filter(function($p) {
            return !empty($p->example_sentence) && !empty($p->example_translation);
        });
        $examplesCount = $propsWithExamples->count();
        $exampleResults = [];
        $exampleScore = 0.0;

        if ($examplesCount > 0) {
            $sharePerExample = 30.0 / $examplesCount;
            foreach ($propsWithExamples as $prop) {
                $studentAns = trim($request->input('examples.' . $prop->id));
                $isCorrect = ($normalize($studentAns) === $normalize($prop->example_sentence));
                if ($isCorrect) {
                    $exampleScore += $sharePerExample;
                }

                $exampleResults[] = [
                    'vietnamese' => $prop->example_translation,
                    'student' => $studentAns,
                    'actual' => $prop->example_sentence,
                    'is_correct' => $isCorrect
                ];
            }
        } else {
            $exampleScore = 30.0; // Auto points if no examples are set up
        }

        $totalScore = $spellingScore + $meaningScore + $exampleScore;

        // 5. Save the review log attempt
        $review = VocabReview::create([
            'user_id' => $user->id,
            'word_id' => $activeWord->id,
            'score' => round($totalScore),
            'duration_seconds' => $durationSeconds,
            'passed' => $totalScore >= 80,
            'answers' => [
                'spelling' => [
                    'student' => $spellingAnswer,
                    'actual' => $activeWord->word,
                    'is_correct' => $isSpellingCorrect
                ],
                'meanings' => $meaningResults,
                'examples' => $exampleResults
            ]
        ]);

        // 6. Update student active study stats log
        $studyLog = VocabStudyLog::firstOrCreate([
            'user_id' => $user->id,
            'word_id' => $activeWord->id
        ]);
        $studyLog->sessions_count++;
        // Add duration in minutes (round up to at least 1 min)
        $reviewMinutes = max(1, round($durationSeconds / 60));
        $studyLog->review_time_minutes += $reviewMinutes;
        $studyLog->save();

        // 7. Check for new dynamic achievements unlocked
        \App\Services\AchievementService::checkAndUnlock($user);
        $newUnlocked = $user->achievements()->whereNotIn('achievements.id', $oldUnlockedIds)->get();
        if ($newUnlocked->isNotEmpty()) {
            session()->flash('newly_unlocked_achievements', $newUnlocked);
        }

        return redirect()->route('vocabulary.reviews.show', $review->id)
            ->with('success', 'Nộp bài thi ôn tập thành công! Dưới đây là kết quả của bạn.');
    }

    /**
     * Display detailed active recall comparative results.
     */
    public function showReview($id)
    {
        $review = VocabReview::with('word.properties.stage')->findOrFail($id);
        return view('vocabularies.review-detail', compact('review'));
    }

    /**
     * Vocabulary Administration Dashboard
     */
    public function adminIndex()
    {
        $stages = VocabStage::orderBy('order', 'asc')->get();
        $words = VocabWord::with('properties.stage')->get();
        $properties = VocabProperty::with(['word', 'stage'])->get();
        return view('vocabularies.admin.index', compact('stages', 'words', 'properties'));
    }

    // ==========================================
    // STAGES CRUD
    // ==========================================
    public function storeStage(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);
        VocabStage::create($data);
        return redirect()->back()->with('success', 'Thêm mới giai đoạn thành công!');
    }

    public function updateStage(Request $request, $id)
    {
        $stage = VocabStage::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);
        $stage->update($data);
        return redirect()->back()->with('success', 'Cập nhật giai đoạn thành công!');
    }

    public function destroyStage($id)
    {
        $stage = VocabStage::findOrFail($id);
        $stage->delete();
        return redirect()->back()->with('success', 'Xóa giai đoạn thành công!');
    }

    // ==========================================
    // WORDS CRUD
    // ==========================================
    public function storeWord(Request $request)
    {
        $data = $request->validate([
            'word' => 'required|string|max:255|unique:vocab_words,word',
            'uk_phonetic' => 'nullable|string',
            'us_phonetic' => 'nullable|string',
        ]);
        VocabWord::create($data);
        return redirect()->back()->with('success', 'Thêm từ gốc mới thành công!');
    }

    public function updateWord(Request $request, $id)
    {
        $word = VocabWord::findOrFail($id);
        $data = $request->validate([
            'word' => 'required|string|max:255|unique:vocab_words,word,' . $word->id,
            'uk_phonetic' => 'nullable|string',
            'us_phonetic' => 'nullable|string',
        ]);
        $word->update($data);
        return redirect()->back()->with('success', 'Cập nhật từ gốc thành công!');
    }

    public function destroyWord($id)
    {
        $word = VocabWord::findOrFail($id);
        $word->delete();
        return redirect()->back()->with('success', 'Xóa từ gốc thành công!');
    }

    // ==========================================
    // PROPERTIES CRUD
    // ==========================================
    public function storeProperty(Request $request)
    {
        $data = $request->validate([
            'word_id' => 'required|exists:vocab_words,id',
            'stage_id' => 'required|exists:vocab_stages,id',
            'part_of_speech' => 'required|string|max:255',
            'meaning' => 'required|string',
            'example_sentence' => 'nullable|string',
            'example_translation' => 'nullable|string',
            'topic' => 'nullable|string',
            'reference' => 'nullable|string',
        ]);
        VocabProperty::create($data);
        return redirect()->back()->with('success', 'Thêm nét nghĩa/thuộc tính mới thành công!');
    }

    public function updateProperty(Request $request, $id)
    {
        $property = VocabProperty::findOrFail($id);
        $data = $request->validate([
            'word_id' => 'required|exists:vocab_words,id',
            'stage_id' => 'required|exists:vocab_stages,id',
            'part_of_speech' => 'required|string|max:255',
            'meaning' => 'required|string',
            'example_sentence' => 'nullable|string',
            'example_translation' => 'nullable|string',
            'topic' => 'nullable|string',
            'reference' => 'nullable|string',
        ]);
        $property->update($data);
        return redirect()->back()->with('success', 'Cập nhật nét nghĩa/thuộc tính thành công!');
    }

    public function destroyProperty($id)
    {
        $property = VocabProperty::findOrFail($id);
        $property->delete();
        return redirect()->back()->with('success', 'Xóa nét nghĩa/thuộc tính thành công!');
    }
}
