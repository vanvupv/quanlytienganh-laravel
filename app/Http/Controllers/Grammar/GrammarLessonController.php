<?php

namespace App\Http\Controllers\Grammar;

use App\Http\Controllers\Controller;
use App\Models\Grammar\GrammarLesson;
use App\Models\Grammar\GrammarTopic;
use Illuminate\Http\Request;

class GrammarLessonController extends Controller
{
    /**
     * Show a detailed view of a lesson for students.
     */
    public function show($id)
    {
        $lesson = GrammarLesson::with(['topic.category', 'reviews', 'studyLogs', 'sectionReviews', 'progress'])->findOrFail($id);

        // Auto-fix any section reviews for this lesson that are passed but stuck in 'Chưa đạt' due to legacy time conditions
        foreach ($lesson->sectionReviews as $sec) {
            if ($sec->review_score >= $lesson->passing_score && $sec->status !== 'Đạt') {
                $sec->status = 'Đạt';
                $sec->save();
            }
        }

        // Fetch previous and next lessons for navigation
        $prevLesson = GrammarLesson::where('topic_id', $lesson->topic_id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if (!$prevLesson) {
            $prevTopic = \App\Models\Grammar\GrammarTopic::where('category_id', $lesson->topic->category_id)
                ->where('order', '<', $lesson->topic->order)
                ->orderBy('order', 'desc')
                ->first();
            if ($prevTopic) {
                $prevLesson = GrammarLesson::where('topic_id', $prevTopic->id)
                    ->orderBy('order', 'desc')
                    ->first();
            }
        }

        $nextLesson = GrammarLesson::where('topic_id', $lesson->topic_id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        if (!$nextLesson) {
            $nextTopic = \App\Models\Grammar\GrammarTopic::where('category_id', $lesson->topic->category_id)
                ->where('order', '>', $lesson->topic->order)
                ->orderBy('order', 'asc')
                ->first();
            if ($nextTopic) {
                $nextLesson = GrammarLesson::where('topic_id', $nextTopic->id)
                    ->orderBy('order', 'asc')
                    ->first();
            }
        }

        $sections = $this->getNormalizedSections($lesson);
        $isHtmlFormat = ($sections !== null);
        if ($isHtmlFormat) {
            $content = null;
            $examples = [];
        } else {
            // Decode JSON content
            $content = json_decode($lesson->content, true);
            $examples = json_decode($lesson->example, true) ?? [];
        }

        $isCompleted = $lesson->progress->contains('knowledge_key', 'lesson_completed');

        return view('grammars.lesson-detail', compact('lesson', 'content', 'examples', 'prevLesson', 'nextLesson', 'isHtmlFormat', 'sections', 'isCompleted'));
    }

    /**
     * Show form to create a new lesson (Admin).
     */
    public function create()
    {
        $topics = GrammarTopic::with('category')->get();
        return view('grammars.admin.lessons.create', compact('topics'));
    }

    /**
     * Store a newly created lesson (Admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:grammar_topics,id',
            'title' => 'required|string|max:255',
            'formula_affirmative' => 'required|string',
            'formula_negative' => 'required|string',
            'formula_question' => 'required|string',
            'usage_text' => 'required|string',
            'order' => 'nullable|integer',
            'formula_study_time' => 'nullable|integer|min:0',
            'formula_min_review_time' => 'nullable|integer|min:0',
            'usage_study_time' => 'nullable|integer|min:0',
            'usage_min_review_time' => 'nullable|integer|min:0',
            'example_study_time' => 'nullable|integer|min:0',
            'example_min_review_time' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);

        $lesson = new GrammarLesson();
        $lesson->topic_id = $request->topic_id;
        $lesson->title = $request->title;
        $lesson->order = $request->order ?? 0;
        
        // Save target timers and score targets
        $lesson->formula_study_time = $request->formula_study_time ?? 15;
        $lesson->formula_min_review_time = $request->formula_min_review_time ?? 10;
        $lesson->usage_study_time = $request->usage_study_time ?? 20;
        $lesson->usage_min_review_time = $request->usage_min_review_time ?? 15;
        $lesson->example_study_time = $request->example_study_time ?? 10;
        $lesson->example_min_review_time = $request->example_min_review_time ?? 10;
        $lesson->passing_score = $request->passing_score ?? 80;

        // Structure content JSON
        $content = [
            'formula' => [
                'affirmative' => $request->formula_affirmative,
                'negative' => $request->formula_negative,
                'question' => $request->formula_question,
            ],
            'usage' => array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->usage_text)))))
        ];
        $lesson->content = json_encode($content, JSON_UNESCAPED_UNICODE);

        // Structure examples JSON
        $examples = [];
        $sentences = $request->input('ex_sentence', []);
        $notes = $request->input('ex_note', []);
        foreach ($sentences as $index => $sentence) {
            if (!empty(trim($sentence))) {
                $examples[] = [
                    'sentence' => trim($sentence),
                    'note' => trim($notes[$index] ?? '')
                ];
            }
        }
        $lesson->example = json_encode($examples, JSON_UNESCAPED_UNICODE);

        $lesson->save();

        return redirect()->route('grammar.admin.index')->with('success', 'Bài học đã được thêm thành công!');
    }

    /**
     * Show form to edit an existing lesson (Admin).
     */
    public function edit($id)
    {
        $lesson = GrammarLesson::findOrFail($id);
        $topics = GrammarTopic::with('category')->get();
        
        $content = json_decode($lesson->content, true) ?? [];
        $examples = json_decode($lesson->example, true) ?? [];

        return view('grammars.admin.lessons.edit', compact('lesson', 'topics', 'content', 'examples'));
    }

    /**
     * Update the lesson in database (Admin).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'topic_id' => 'required|exists:grammar_topics,id',
            'title' => 'required|string|max:255',
            'formula_affirmative' => 'required|string',
            'formula_negative' => 'required|string',
            'formula_question' => 'required|string',
            'usage_text' => 'required|string',
            'order' => 'nullable|integer',
            'formula_study_time' => 'nullable|integer|min:0',
            'formula_min_review_time' => 'nullable|integer|min:0',
            'usage_study_time' => 'nullable|integer|min:0',
            'usage_min_review_time' => 'nullable|integer|min:0',
            'example_study_time' => 'nullable|integer|min:0',
            'example_min_review_time' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);

        $lesson = GrammarLesson::findOrFail($id);
        $lesson->topic_id = $request->topic_id;
        $lesson->title = $request->title;
        $lesson->order = $request->order ?? 0;
        
        // Save target timers and score targets
        $lesson->formula_study_time = $request->formula_study_time ?? 15;
        $lesson->formula_min_review_time = $request->formula_min_review_time ?? 10;
        $lesson->usage_study_time = $request->usage_study_time ?? 20;
        $lesson->usage_min_review_time = $request->usage_min_review_time ?? 15;
        $lesson->example_study_time = $request->example_study_time ?? 10;
        $lesson->example_min_review_time = $request->example_min_review_time ?? 10;
        $lesson->passing_score = $request->passing_score ?? 80;

        // Structure content JSON
        $content = [
            'formula' => [
                'affirmative' => $request->formula_affirmative,
                'negative' => $request->formula_negative,
                'question' => $request->formula_question,
            ],
            'usage' => array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->usage_text)))))
        ];
        $lesson->content = json_encode($content, JSON_UNESCAPED_UNICODE);

        // Structure examples JSON
        $examples = [];
        $sentences = $request->input('ex_sentence', []);
        $notes = $request->input('ex_note', []);
        foreach ($sentences as $index => $sentence) {
            if (!empty(trim($sentence))) {
                $examples[] = [
                    'sentence' => trim($sentence),
                    'note' => trim($notes[$index] ?? '')
                ];
            }
        }
        $lesson->example = json_encode($examples, JSON_UNESCAPED_UNICODE);

        $lesson->save();

        return redirect()->route('grammar.admin.index')->with('success', 'Bài học đã được cập nhật thành công!');
    }

    /**
     * Delete the lesson (Admin).
     */
    public function destroy($id)
    {
        $lesson = GrammarLesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('grammar.admin.index')->with('success', 'Bài học đã được xóa thành công!');
    }

    /**
     * Show the interactive review recall form for a lesson.
     */
    public function review($id)
    {
        $lesson = GrammarLesson::with('topic.category')->findOrFail($id);

        $sections = $this->getNormalizedSections($lesson);
        $isHtmlFormat = ($sections !== null);
        if ($isHtmlFormat) {
            $content = null;
            $examples = [];
            $questionsBySection = $this->parseQuestionsFromSections($sections);
        } else {
            $content = json_decode($lesson->content, true) ?? [];
            $examples = json_decode($lesson->example, true) ?? [];
            $questionsBySection = [];
        }

        return view('grammars.review', compact('lesson', 'content', 'examples', 'isHtmlFormat', 'sections', 'questionsBySection'));
    }

    /**
     * Process student review attempt, score answers, and log statistics.
     */
    public function submitReview(Request $request, $id)
    {
        $lesson = GrammarLesson::findOrFail($id);

        $sections = $this->getNormalizedSections($lesson);
        $isHtmlFormat = ($sections !== null);

        $user = \Illuminate\Support\Facades\Auth::user() ?: \App\Models\User::first();
        $oldUnlockedIds = [];
        if ($user) {
            $oldUnlockedIds = $user->achievements()->pluck('achievements.id')->toArray();
        }

        $durationSeconds = intval($request->input('duration_seconds', 0));
        $reviewMinutes = max(1, round($durationSeconds / 60));
        $logDate = date('Y-m-d');

        if ($isHtmlFormat) {
            $questionsBySection = $this->parseQuestionsFromSections($sections);
            
            $totalPossibleQuestions = 0;
            $totalCorrectQuestions = 0;
            
            $sectionsScores = [];
            $allAnswersDetails = [];
            
            foreach ($sections as $sec) {
                $secId = $sec['id'];
                $parsed = $questionsBySection[$secId];
                
                $secPossible = 0;
                $secCorrect = 0;
                
                // 1. Công thức
                $secFormulaDetails = [];
                if (!empty($parsed['formulas'])) {
                    $formulaKeys = ['affirmative', 'negative', 'question'];
                    foreach ($formulaKeys as $key) {
                        if (isset($parsed['formulas'][$key]) && trim($parsed['formulas'][$key]) !== '') {
                            $correctAnswer = $parsed['formulas'][$key];
                            $studentAnswer = $request->input("answers_formula.{$secId}.{$key}", '');
                            $isCorrect = $this->isFuzzyMatch($studentAnswer, $correctAnswer);
                            
                            $secPossible++;
                            if ($isCorrect) $secCorrect++;
                            
                            $secFormulaDetails[$key] = [
                                'name' => $key === 'affirmative' ? 'Thể khẳng định (+)' : ($key === 'negative' ? 'Thể phủ định (-)' : 'Thể nghi vấn (?)'),
                                'student' => $studentAnswer,
                                'actual' => $correctAnswer,
                                'is_correct' => $isCorrect,
                                'score' => $isCorrect ? 10 : 0
                            ];
                        }
                    }
                }
                
                // 2. Cách dùng
                $secUsageDetails = [];
                if (!empty($parsed['usages'])) {
                    $studentUsages = $request->input("answers_usage.{$secId}", []);
                    foreach ($parsed['usages'] as $idx => $correctAnswer) {
                        $studentAnswer = $studentUsages[$idx] ?? '';
                        $isCorrect = $this->isFuzzyMatch($studentAnswer, $correctAnswer);
                        
                        $secPossible++;
                        if ($isCorrect) $secCorrect++;
                        
                        $secUsageDetails[] = [
                            'name' => 'Cách dùng ' . ($idx + 1),
                            'student' => $studentAnswer,
                            'actual' => $correctAnswer,
                            'is_correct' => $isCorrect,
                            'score' => $isCorrect ? 10 : 0
                        ];
                    }
                }
                
                // 3. Ví dụ
                $secExampleDetails = [];
                if (!empty($parsed['examples'])) {
                    $studentExamples = $request->input("answers_example.{$secId}", []);
                    foreach ($parsed['examples'] as $idx => $exData) {
                        $correctAnswer = $exData['sentence'];
                        $studentAnswer = $studentExamples[$idx] ?? '';
                        $isCorrect = $this->isFuzzyMatch($studentAnswer, $correctAnswer);
                        
                        $secPossible++;
                        if ($isCorrect) $secCorrect++;
                        
                        $secExampleDetails[] = [
                            'vietnamese' => $exData['note'] ?? '',
                            'student' => $studentAnswer,
                            'actual' => $correctAnswer,
                            'is_correct' => $isCorrect,
                            'score' => $isCorrect ? 10 : 0
                        ];
                    }
                }
                
                $secScore = $secPossible > 0 ? round(($secCorrect / $secPossible) * 100) : 100;
                
                $sectionsScores[$secId] = [
                    'title' => $sec['title'],
                    'score' => $secScore,
                    'possible' => $secPossible,
                    'correct' => $secCorrect,
                    'study_time' => $sec['study_time_target'] ?? 15,
                    'min_review' => $sec['min_review_time_target'] ?? 10
                ];
                
                $allAnswersDetails[$secId] = [
                    'title' => $sec['title'],
                    'formulas' => $secFormulaDetails,
                    'usages' => $secUsageDetails,
                    'examples' => $secExampleDetails,
                    'score' => $secScore
                ];
                
                $totalPossibleQuestions += $secPossible;
                $totalCorrectQuestions += $secCorrect;
            }
            
            $totalScore = $totalPossibleQuestions > 0 ? round(($totalCorrectQuestions / $totalPossibleQuestions) * 100) : 100;
            $passed = $totalScore >= $lesson->passing_score;

            // Log the review attempt
            $review = \App\Models\Grammar\GrammarReview::create([
                'lesson_id' => $lesson->id,
                'score' => $totalScore,
                'duration_seconds' => $durationSeconds,
                'passed' => $passed,
                'answers' => [
                    'is_html_format' => true,
                    'sections' => $allAnswersDetails,
                ]
            ]);

            // Update overall study logs
            $studyLog = \App\Models\Grammar\GrammarStudyLog::firstOrNew([
                'lesson_id' => $lesson->id,
                'session_date' => $logDate,
            ]);
            $studyLog->review_time += $reviewMinutes;
            $studyLog->sessions_count += 1;
            $studyLog->save();

            // Update section reviews
            $activeSectionsCount = count($sectionsScores);
            $timePerSection = max(1, round($reviewMinutes / max(1, $activeSectionsCount)));
            
            foreach ($sectionsScores as $secId => $secData) {
                $secRev = \App\Models\Grammar\GrammarSectionReview::firstOrNew([
                    'lesson_id' => $lesson->id,
                    'section_name' => $secData['title'],
                ]);
                $secRev->study_time = $secData['study_time'];
                $secRev->min_review_time = $secData['min_review'];
                $secRev->review_count += 1;
                $secRev->review_time += $timePerSection;
                $secRev->review_score = $secData['score'];
                
                if ($secRev->review_score >= $lesson->passing_score) {
                    $secRev->status = 'Đạt';
                } else {
                    $secRev->status = 'Chưa đạt';
                }
                $secRev->save();
            }

        } else {
            // Legacy JSON Format
            $correctContent = json_decode($lesson->content, true) ?? [];
            $correctExamples = json_decode($lesson->example, true) ?? [];

            $formulaAffCorrect = $correctContent['formula']['affirmative'] ?? '';
            $formulaNegCorrect = $correctContent['formula']['negative'] ?? '';
            $formulaQuesCorrect = $correctContent['formula']['question'] ?? '';
            
            $studentAff = $request->input('answers_affirmative', '');
            $studentNeg = $request->input('answers_negative', '');
            $studentQues = $request->input('answers_question', '');

            // 1. Score Formula Section (Max 30 points, 10 each)
            $affCorrect = $this->isFuzzyMatch($studentAff, $formulaAffCorrect);
            $negCorrect = $this->isFuzzyMatch($studentNeg, $formulaNegCorrect);
            $quesCorrect = $this->isFuzzyMatch($studentQues, $formulaQuesCorrect);

            $formulaScore = 0;
            if ($affCorrect) $formulaScore += 10;
            if ($negCorrect) $formulaScore += 10;
            if ($quesCorrect) $formulaScore += 10;

            $formulasDetails = [
                'affirmative' => [
                    'name' => 'Thể khẳng định (+)',
                    'student' => $studentAff,
                    'actual' => $formulaAffCorrect,
                    'is_correct' => $affCorrect,
                    'score' => $affCorrect ? 10 : 0
                ],
                'negative' => [
                    'name' => 'Thể phủ định (-)',
                    'student' => $studentNeg,
                    'actual' => $formulaNegCorrect,
                    'is_correct' => $negCorrect,
                    'score' => $negCorrect ? 10 : 0
                ],
                'question' => [
                    'name' => 'Thể nghi vấn (?)',
                    'student' => $studentQues,
                    'actual' => $formulaQuesCorrect,
                    'is_correct' => $quesCorrect,
                    'score' => $quesCorrect ? 10 : 0
                ]
            ];

            // 2. Score Usages Section (Max 30 points)
            $actualUsages = $correctContent['usage'] ?? [];
            $studentUsages = $request->input('answers_usage', []);
            $usagesDetails = [];
            $usageScore = 0;
            $usageWeight = count($actualUsages) > 0 ? (30 / count($actualUsages)) : 30;

            foreach ($actualUsages as $idx => $actualUsage) {
                $studentUsage = $studentUsages[$idx] ?? '';
                $isCorrect = $this->isFuzzyMatch($studentUsage, $actualUsage);
                if ($isCorrect) {
                    $usageScore += $usageWeight;
                }
                $usagesDetails[] = [
                    'name' => 'Cách dùng ' . ($idx + 1),
                    'student' => $studentUsage,
                    'actual' => $actualUsage,
                    'is_correct' => $isCorrect,
                    'score' => $isCorrect ? round($usageWeight, 1) : 0
                ];
            }
            $usageScore = min(30, round($usageScore));

            // 3. Score Examples Section (Max 40 points)
            $examplesDetails = [];
            $exampleScore = 0;
            $exampleWeight = count($correctExamples) > 0 ? (40 / count($correctExamples)) : 40;

            $studentExamples = $request->input('answers_example', []);
            foreach ($correctExamples as $idx => $ex) {
                $vietnamese = $ex['note'] ?? '';
                $actualEnglish = $ex['sentence'] ?? '';
                $studentEnglish = $studentExamples[$idx] ?? '';

                $isCorrect = $this->isFuzzyMatch($studentEnglish, $actualEnglish);
                if ($isCorrect) {
                    $exampleScore += $exampleWeight;
                }
                $examplesDetails[] = [
                    'vietnamese' => $vietnamese,
                    'student' => $studentEnglish,
                    'actual' => $actualEnglish,
                    'is_correct' => $isCorrect,
                    'score' => $isCorrect ? round($exampleWeight, 1) : 0
                ];
            }
            $exampleScore = min(40, round($exampleScore));

            // Total Score
            $totalScore = $formulaScore + $usageScore + $exampleScore;
            $passed = $totalScore >= $lesson->passing_score;

            // Log the review attempt
            $review = \App\Models\Grammar\GrammarReview::create([
                'lesson_id' => $lesson->id,
                'score' => $totalScore,
                'duration_seconds' => $durationSeconds,
                'passed' => $passed,
                'answers' => [
                    'formulas' => $formulasDetails,
                    'usages' => $usagesDetails,
                    'examples' => $examplesDetails,
                ]
            ]);

            // Update overall study logs
            $studyLog = \App\Models\Grammar\GrammarStudyLog::firstOrNew([
                'lesson_id' => $lesson->id,
                'session_date' => $logDate,
            ]);
            $studyLog->review_time += $reviewMinutes;
            $studyLog->sessions_count += 1;
            $studyLog->save();

            // Update section breakdowns
            $formulaScoreRatio = round(($formulaScore / 30) * 100);
            $usageScoreRatio = round(($usageScore / 30) * 100);
            $exampleScoreRatio = round(($exampleScore / 40) * 100);

            $sectionsToUpdate = [
                'Công Thức (Formula)' => [
                    'score' => $formulaScoreRatio,
                    'min_review' => $lesson->formula_min_review_time,
                    'study' => $lesson->formula_study_time,
                ],
                'Cách Dùng (Usage)' => [
                    'score' => $usageScoreRatio,
                    'min_review' => $lesson->usage_min_review_time,
                    'study' => $lesson->usage_study_time,
                ],
                'Ví Dụ (Examples)' => [
                    'score' => $exampleScoreRatio,
                    'min_review' => $lesson->example_min_review_time,
                    'study' => $lesson->example_study_time,
                ]
            ];

            foreach($sectionsToUpdate as $secName => $secData) {
                $secRev = \App\Models\Grammar\GrammarSectionReview::firstOrNew([
                    'lesson_id' => $lesson->id,
                    'section_name' => $secName,
                ]);
                $secRev->study_time = $secData['study'];
                $secRev->min_review_time = $secData['min_review'];
                $secRev->review_count += 1;
                $secRev->review_time += max(1, round($reviewMinutes / 3));
                $secRev->review_score = $secData['score'];
                
                if ($secRev->review_score >= $lesson->passing_score) {
                    $secRev->status = 'Đạt';
                } else {
                    $secRev->status = 'Chưa đạt';
                }
                $secRev->save();
            }
        }

        // Check achievements
        if ($user) {
            \App\Services\AchievementService::checkAndUnlock($user);
            $newUnlocked = $user->achievements()->whereNotIn('achievements.id', $oldUnlockedIds)->get();
            if ($newUnlocked->isNotEmpty()) {
                session()->flash('newly_unlocked_achievements', $newUnlocked);
            }
        }

        return redirect()->route('grammar.reviews.show', $review->id)
            ->with('success', 'Nộp bài ôn tập thành công! Điểm số đạt được: ' . $totalScore);
    }

    /**
     * Show the detailed comparative report of a review attempt.
     */
    public function showReview($id)
    {
        $review = \App\Models\Grammar\GrammarReview::with('lesson.topic.category')->findOrFail($id);
        return view('grammars.review-detail', compact('review'));
    }

    /**
     * Helper to perform fuzzy string comparisons.
     * Ignores case, all whitespace, and basic punctuation.
     */
    private function isFuzzyMatch($str1, $str2)
    {
        $normalize = function ($s) {
            $s = mb_strtolower($s, 'UTF-8');
            // Strip common symbols
            $s = preg_replace('/[.,?!;:+\-_\/*()\'"“”`~<>?@#$%^&|\\\]/', '', $s);
            // Strip all whitespace characters
            $s = preg_replace('/\s+/', '', $s);
            return trim($s);
        };
        return $normalize($str1) === $normalize($str2);
    }

    /**
     * Save or update the learning progress for a specific knowledge key of a lesson.
     */
    public function saveProgress(Request $request, $id)
    {
        $request->validate([
            'knowledge_key' => 'required|string',
            'learned_date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $lesson = GrammarLesson::findOrFail($id);
        $learnedDate = $request->input('learned_date') ?: date('Y-m-d');
        
        $progress = \App\Models\Grammar\GrammarLessonProgress::updateOrCreate(
            [
                'lesson_id' => $lesson->id,
                'knowledge_key' => $request->input('knowledge_key'),
            ],
            [
                'learned_date' => $learnedDate,
                'note' => $request->input('note'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu phần kiến thức này là đã học.',
            'progress' => [
                'knowledge_key' => $progress->knowledge_key,
                'learned_date' => $progress->learned_date->format('d/m/Y'),
                'note' => $progress->note,
            ]
        ]);
    }

    /**
     * Delete the learning progress for a specific knowledge key of a lesson.
     */
    public function deleteProgress(Request $request, $id)
    {
        $request->validate([
            'knowledge_key' => 'required|string',
        ]);

        $lesson = GrammarLesson::findOrFail($id);

        \App\Models\Grammar\GrammarLessonProgress::where('lesson_id', $lesson->id)
            ->where('knowledge_key', $request->input('knowledge_key'))
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tiến trình học của phần kiến thức này.'
        ]);
    }

    /**
     * Reset all learning progress and reviews for a lesson.
     */
    public function resetProgress($id)
    {
        $lesson = GrammarLesson::findOrFail($id);

        // Delete all progress logs
        \App\Models\Grammar\GrammarLessonProgress::where('lesson_id', $lesson->id)->delete();

        // Delete all review attempts
        \App\Models\Grammar\GrammarReview::where('lesson_id', $lesson->id)->delete();

        // Delete all section reviews
        \App\Models\Grammar\GrammarSectionReview::where('lesson_id', $lesson->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã reset toàn bộ tiến trình và lịch sử ôn tập của bài học này về 0%.'
        ]);
    }

    /**
     * Save HTML content edited inline.
     */
    public function saveHtmlContent(Request $request, $id)
    {
        $request->validate([
            'html_content' => 'required|string',
        ]);

        $lesson = GrammarLesson::findOrFail($id);
        $lesson->content = $request->input('html_content');
        $lesson->save();

        return response()->json([
            'success' => true,
            'message' => 'Lưu giáo trình thành công!'
        ]);
    }

    /**
     * Helper to check if a string is valid JSON.
     */
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get normalized sections array for a lesson.
     */
    private function getNormalizedSections($lesson)
    {
        $content = $lesson->content;
        if (!$this->isJson($content)) {
            // Đây là legacy HTML (như bài 13 cũ). Tự động chuyển đổi thành 1 Section mặc định.
            return [
                [
                    'id' => 'sec_default',
                    'title' => '1. Nội dung bài học',
                    'html' => $content,
                    'study_time_target' => $lesson->formula_study_time + $lesson->usage_study_time + $lesson->example_study_time,
                    'min_review_time_target' => $lesson->formula_min_review_time + $lesson->usage_min_review_time + $lesson->example_min_review_time,
                ]
            ];
        }

        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            if (isset($decoded[0]['html'])) {
                // Đây là định dạng Hybrid JSON-HTML mới
                return $decoded;
            }

            // Đây là định dạng JSON cũ (cấu trúc tĩnh). Tự động chuyển đổi sang định dạng Hybrid JSON-HTML mới!
            $html = '';
            
            // 1. Công thức
            if (isset($decoded['formula'])) {
                $html .= '<h4 class="fw-bold text-dark mt-3 mb-2" style="font-size: 15.5px;">📋 Công thức cấu trúc tổng quát:</h4>';
                $html .= '<ul style="padding-left: 20px; list-style-type: disc;">';
                if (!empty($decoded['formula']['affirmative'])) {
                    $html .= '<li class="mb-2">Thể khẳng định (+): <span class="grammar-highlight hl-red" data-category="red" data-name="Thể khẳng định (+)">' . e($decoded['formula']['affirmative']) . '</span></li>';
                }
                if (!empty($decoded['formula']['negative'])) {
                    $html .= '<li class="mb-2">Thể phủ định (-): <span class="grammar-highlight hl-red" data-category="red" data-name="Thể phủ định (-)">' . e($decoded['formula']['negative']) . '</span></li>';
                }
                if (!empty($decoded['formula']['question'])) {
                    $html .= '<li class="mb-2">Thể nghi vấn (?): <span class="grammar-highlight hl-red" data-category="red" data-name="Thể nghi vấn (?)">' . e($decoded['formula']['question']) . '</span></li>';
                }
                $html .= '</ul>';
            }
            
            // 2. Cách dùng
            if (!empty($decoded['usage'])) {
                $html .= '<h4 class="fw-bold text-dark mt-4 mb-2" style="font-size: 15.5px;">📘 Hướng dẫn cách dùng cốt lõi:</h4>';
                $html .= '<ol style="padding-left: 20px; list-style-type: decimal;">';
                foreach ($decoded['usage'] as $idx => $use) {
                    $html .= '<li class="mb-2"><span class="grammar-highlight hl-blue" data-category="blue" data-name="Cách dùng ' . ($idx + 1) . '">' . e($use) . '</span></li>';
                }
                $html .= '</ol>';
            }
            
            // 3. Ví dụ
            $examplesDecoded = json_decode($lesson->example, true) ?? [];
            if (!empty($examplesDecoded)) {
                $html .= '<h4 class="fw-bold text-dark mt-4 mb-2" style="font-size: 15.5px;">✏️ Ví dụ minh họa học tập:</h4>';
                $html .= '<ul style="padding-left: 20px; list-style-type: circle;">';
                foreach ($examplesDecoded as $ex) {
                    $note = !empty($ex['note']) ? $ex['note'] : 'Ví dụ';
                    $html .= '<li class="mb-2">' . e($note) . ': <span class="grammar-highlight hl-green" data-category="green" data-name="' . e($note) . '">' . e($ex['sentence']) . '</span></li>';
                }
                $html .= '</ul>';
            }
            
            return [
                [
                    'id' => 'sec_default',
                    'title' => '1. Nội dung bài học',
                    'html' => $html,
                    'study_time_target' => $lesson->formula_study_time + $lesson->usage_study_time + $lesson->example_study_time,
                    'min_review_time_target' => $lesson->formula_min_review_time + $lesson->usage_min_review_time + $lesson->example_min_review_time,
                ]
            ];
        }

        return null;
    }

    /**
     * Parse questions from all sections of a lesson.
     */
    private function parseQuestionsFromSections($sections)
    {
        $questionsBySection = [];
        foreach ($sections as $sec) {
            $parsed = $this->parseQuestionsFromHtml($sec['html']);
            $questionsBySection[$sec['id']] = [
                'title' => $sec['title'],
                'formulas' => $parsed['formulas'],
                'usages' => $parsed['usages'],
                'examples' => $parsed['examples']
            ];
        }
        return $questionsBySection;
    }

    /**
     * Parse formulas, usages, and examples from HTML content.
     */
    private function parseQuestionsFromHtml($htmlContent)
    {
        $formulas = [];
        $usages = [];
        $examples = [];

        if (empty($htmlContent)) {
            return compact('formulas', 'usages', 'examples');
        }

        // Prevent HTML5 warning tags
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//span[contains(@class, 'grammar-highlight')]");

        $formulaCount = 0;

        foreach ($nodes as $node) {
            $text = trim($node->textContent);
            $category = $node->getAttribute('data-category');
            $name = $node->getAttribute('data-name') ?: $text;

            if ($category === 'red' || $category === 'formula') {
                if ($formulaCount === 0) {
                    $formulas['affirmative'] = $text;
                } elseif ($formulaCount === 1) {
                    $formulas['negative'] = $text;
                } else {
                    $formulas['question'] = $text;
                }
                $formulaCount++;
            } elseif ($category === 'blue' || $category === 'usage') {
                $usages[] = $text;
            } elseif ($category === 'green' || $category === 'example') {
                $examples[] = [
                    'sentence' => $text,
                    'note' => $name
                ];
            }
        }

        return compact('formulas', 'usages', 'examples');
    }
}




