<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vocabulary\VocabStudyLog;
use App\Models\Vocabulary\VocabReview;
use App\Models\Grammar\GrammarStudyLog;
use App\Models\Grammar\GrammarReview;
use App\Models\Grammar\GrammarCategory;
use App\Models\Grammar\GrammarSectionReview;
use App\Models\Vocabulary\VocabWord;
use App\Models\UserAssessment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ProfileController extends Controller
{
    /**
     * Display the student personal profile & learning analytics dashboard.
     */
    public function index()
    {
        // Get the active student (for demo/standalone durability, fallback to first user if none authenticated)
        $user = auth()->user() ?? User::first();

        if (!$user) {
            // Create a default user if database is completely fresh
            $user = User::create([
                'name' => 'Học Viên Anh Ngữ',
                'email' => 'student@englishhub.edu.vn',
                'password' => bcrypt('123456'),
                'english_level' => 'B1',
                'target_goal' => 'IELTS 7.0 & Giao tiếp trôi chảy',
                'learning_speed' => 'Trung bình',
                'retention_rate' => 75
            ]);
        }

        // Auto-fix any section reviews that are actually passed but stuck in 'Chưa đạt' status due to legacy min_review_time condition
        $allSectionRevs = GrammarSectionReview::with('lesson')->get();
        foreach ($allSectionRevs as $sec) {
            $passingScore = optional($sec->lesson)->passing_score ?? 80;
            if ($sec->review_score >= $passingScore && $sec->status !== 'Đạt') {
                $sec->status = 'Đạt';
                $sec->save();
            }
        }

            // 1. Dynamic Vocabulary Stats
            $vocabWordsCount = VocabStudyLog::where('user_id', $user->id)->count();
            $vocabReviewsCount = VocabReview::where('user_id', $user->id)->count();
            $passedVocabReviews = VocabReview::where('user_id', $user->id)->where('passed', true)->count();
            $avgVocabScore = VocabReview::where('user_id', $user->id)->avg('score') ?? 0;
            $avgVocabDuration = VocabReview::where('user_id', $user->id)->avg('duration_seconds') ?? 0;

            // 2. Dynamic Grammar Stats
            $grammarLessonsCount = GrammarStudyLog::count();
            $grammarReviewsCount = GrammarReview::count();

            // 3. Dynamic Assessments Stats
            $assessmentsTaken = UserAssessment::where('user_id', $user->id)->count();
            $avgAssessmentScore = UserAssessment::where('user_id', $user->id)->avg('score') ?? 0;

            // 4. Computed Smart Analytics
            // A. Actual Memory Retention Percentage (based on passed vocabulary reviews)
            $actualRetentionRate = $vocabReviewsCount > 0
                ? round(($passedVocabReviews / $vocabReviewsCount) * 100)
                : $user->retention_rate;

            // B. Actual Learning Speed (computed from average active recall response time per word)
            $actualLearningSpeed = 'Trung bình';
            if ($avgVocabDuration > 0) {
                if ($avgVocabDuration <= 45) {
                    $actualLearningSpeed = 'Nhanh (Thao tác phản xạ tốt)';
                } elseif ($avgVocabDuration <= 85) {
                    $actualLearningSpeed = 'Trung bình (Đầy đủ suy nghĩ)';
                } else {
                    $actualLearningSpeed = 'Chậm (Cần nhiều thời gian suy ngẫm)';
                }
            } else {
                $actualLearningSpeed = $user->learning_speed . ' (Dựa trên tự đánh giá)';
            }

            // C. Study Consistency Streak (calculated mock baseline or logs frequency)
            $streakDays = $vocabReviewsCount > 0 ? min(15, $vocabReviewsCount * 2) : 3;

            // 5. Full Review History Timeline (merged vocab + grammar, sorted by date desc)
            $vocabReviewHistory = VocabReview::where('user_id', $user->id)
                ->with('word')
                ->latest()
                ->get()
                ->map(function ($review) {
                    return [
                        'type' => 'vocab',
                        'icon' => '📘',
                        'label' => '🔤 Ôn Tập Từ Vựng',
                        'subject' => optional($review->word)->word ?? 'Từ vựng',
                        'score' => $review->score,
                        'passed' => $review->passed,
                        'duration' => $review->duration_seconds,
                        'created_at' => $review->created_at,
                        'detail_url' => null, // vocab review detail không có riêng url
                    ];
                });

            $grammarReviewHistory = GrammarReview::with('lesson')
                ->latest()
                ->get()
                ->map(function ($review) {
                    return [
                        'type' => 'grammar',
                        'icon' => '📖',
                        'label' => '📝 Ôn Tập Ngữ Pháp',
                        'subject' => optional($review->lesson)->title ?? 'Bài học ngữ pháp',
                        'score' => $review->score,
                        'passed' => $review->passed,
                        'duration' => $review->duration_seconds,
                        'created_at' => $review->created_at,
                        'detail_url' => $review->id ? route('grammar.reviews.show', $review->id) : null,
                    ];
                });

            // Merge & sort by created_at descending — latest activity first
            $reviewTimeline = $vocabReviewHistory->toBase()
                ->merge($grammarReviewHistory->toBase())
                ->sortByDesc('created_at')
                ->values();

            // Monthly stats for sparkline (last 6 months)
            $monthlyStats = $reviewTimeline->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['created_at'])->format('Y-m');
            })->map(fn($group) => $group->count())->take(6);

            // -----------------------------------------------------------------------
            // 6. KNOWLEDGE PROGRESS — Danh sách kiến thức đã học & hoàn thành
            // -----------------------------------------------------------------------

            // 6A. Grammar: Category → Topic → Lessons với trạng thái hoàn thành từng bài
            $grammarCategories = GrammarCategory::with([
                'topics.lessons.sectionReviews',
                'topics.lessons.studyLogs',
            ])->orderBy('order')->get()->map(function ($category) {
                $totalLessons = 0;
                $doneLessons = 0;
                $inProgressLessons = 0;

                $topics = $category->topics->sortBy('order')->map(
                    function ($topic) use (&$totalLessons, &$doneLessons, &$inProgressLessons) {
                        $lessons = $topic->lessons->sortBy('order')->map(
                            function ($lesson) use (&$totalLessons, &$doneLessons, &$inProgressLessons) {
                                $totalLessons++;

                                $sections = $lesson->sectionReviews;
                                $formulaPassed = $sections->where('section_name', 'Công Thức (Formula)')->where('status', 'Đạt')->isNotEmpty();
                                $usagePassed = $sections->where('section_name', 'Cách Dùng (Usage)')->where('status', 'Đạt')->isNotEmpty();
                                $examplePassed = $sections->where('section_name', 'Ví Dụ (Examples)')->where('status', 'Đạt')->isNotEmpty();

                                $passedSections = 0;
                                if ($formulaPassed)
                                    $passedSections++;
                                if ($usagePassed)
                                    $passedSections++;
                                if ($examplePassed)
                                    $passedSections++;

                                $hasStudied = $lesson->studyLogs->isNotEmpty();

                                if ($passedSections >= 3) {
                                    $status = 'completed';
                                    $doneLessons++;
                                } elseif ($passedSections > 0 || $hasStudied) {
                                    $status = 'in_progress';
                                    $inProgressLessons++;
                                } else {
                                    $status = 'not_started';
                                }

                                return [
                                    'id' => $lesson->id,
                                    'title' => $lesson->title,
                                    'status' => $status,
                                    'passed_sections' => $passedSections,
                                    'sections_status' => [
                                        'formula' => $formulaPassed,
                                        'usage' => $usagePassed,
                                        'example' => $examplePassed,
                                    ],
                                    'pct' => round(($passedSections / 3) * 100),
                                ];
                            }
                        )->values();

                        return [
                            'id' => $topic->id,
                            'title' => $topic->title,
                            'lessons' => $lessons,
                            'total' => $lessons->count(),
                            'done' => $lessons->where('status', 'completed')->count(),
                        ];
                    }
                )->values();

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'topics' => $topics,
                    'total' => $totalLessons,
                    'done' => $doneLessons,
                    'in_progress' => $inProgressLessons,
                    'pct' => $totalLessons > 0 ? round(($doneLessons / $totalLessons) * 100) : 0,
                ];
            });

            // 6B. Vocabulary: Từ đã học & trạng thái thành thạo
            $studiedWordIds = VocabStudyLog::where('user_id', $user->id)->pluck('word_id');
            $masteredWordIds = VocabReview::where('user_id', $user->id)
                ->where('passed', true)->pluck('word_id')->unique();

            $studiedWords = VocabWord::with('properties.stage')
                ->whereIn('id', $studiedWordIds)
                ->orderBy('word')
                ->get()
                ->map(function ($word) use ($masteredWordIds) {
                    $maxStage = $word->properties->max(fn($p) => optional($p->stage)->order ?? 0);
                    return [
                        'id' => $word->id,
                        'word' => $word->word,
                        'phonetic' => $word->uk_phonetic,
                        'mastered' => $masteredWordIds->contains($word->id),
                        'max_stage' => $maxStage,
                        'stage_label' => match (true) {
                            $maxStage >= 3 => 'Nâng cao',
                            $maxStage == 2 => 'Phát triển',
                            default => 'Nền tảng',
                        },
                    ];
                });

            $totalVocabStudied = $studiedWords->count();
            $totalVocabMastered = $studiedWords->where('mastered', true)->count();

            // -----------------------------------------------------------------------
            // 7. ACHIEVEMENTS — Thành tựu học tập (computed dynamically from DB achievements table)
            // -----------------------------------------------------------------------
            // Run real-time check & unlock sync
            \App\Services\AchievementService::checkAndUnlock($user);

            $grammarDoneTotal = $grammarCategories->sum('done');
            $totalReviewsAll = $vocabReviewsCount + $grammarReviewsCount;

            // Fetch user unlocked achievement pivot metadata
            $unlockedAchievements = $user->achievements()->withPivot('unlocked_at')->get()->keyBy('id');

            $achievements = \App\Models\Achievement::all()->map(function ($ach) use ($unlockedAchievements, $totalVocabStudied, $totalVocabMastered, $grammarLessonsCount, $grammarDoneTotal, $totalReviewsAll, $actualRetentionRate, $streakDays) {
                $unlocked = $unlockedAchievements->has($ach->id);
                $unlockedRecord = $unlocked ? $unlockedAchievements->get($ach->id) : null;

                $currentVal = 0;
                switch ($ach->condition_type) {
                    case 'vocab_studied':
                        $currentVal = $totalVocabStudied;
                        break;
                    case 'vocab_mastered':
                        $currentVal = $totalVocabMastered;
                        break;
                    case 'grammar_lessons_done':
                        $currentVal = $grammarDoneTotal;
                        break;
                    case 'reviews_done':
                        $currentVal = $totalReviewsAll;
                        break;
                    case 'retention_rate':
                        $currentVal = $actualRetentionRate;
                        break;
                    case 'streak_days':
                        $currentVal = $streakDays;
                        break;
                }

                $pct = $ach->target_value > 0 ? min(100, round(($currentVal / $ach->target_value) * 100)) : 0;

                return [
                    'id' => $ach->id,
                    'cat' => $ach->category,
                    'icon' => $ach->icon,
                    'title' => $ach->title,
                    'desc' => $ach->description,
                    'earned' => $unlocked,
                    'progress' => $pct,
                    'target' => $ach->target_value,
                    'current' => $currentVal,
                    'unlocked_at' => $unlockedRecord ? $unlockedRecord->pivot->unlocked_at : null,
                ];
            });

            $earnedCount = $achievements->where('earned', true)->count();
            $totalAchievements = $achievements->count();

            // -----------------------------------------------------------------------
            // 8. PERSONALIZED RECOMMENDATIONS — Gợi ý học tập cá nhân hóa
            // -----------------------------------------------------------------------
            // 8A. Vocabulary: 3 từ yếu nhất (hoặc đã học mà chưa ôn) để ôn lại gấp
            $weakestWords = VocabReview::where('user_id', $user->id)
                ->with('word')
                ->orderBy('score', 'asc')
                ->get()
                ->unique('word_id')
                ->take(3)
                ->map(function ($rev) {
                    return [
                        'id' => $rev->word_id,
                        'word' => optional($rev->word)->word,
                        'score' => $rev->score,
                        'phonetic' => optional($rev->word)->uk_phonetic,
                    ];
                });

            if ($weakestWords->count() < 3) {
                $reviewedWordIds = VocabReview::where('user_id', $user->id)->pluck('word_id')->unique()->toArray();
                $unreviewedWords = VocabWord::whereIn('id', $studiedWordIds)
                    ->whereNotIn('id', $reviewedWordIds)
                    ->take(3 - $weakestWords->count())
                    ->get()
                    ->map(function ($word) {
                        return [
                            'id' => $word->id,
                            'word' => $word->word,
                            'score' => null, // Chưa ôn tập
                            'phonetic' => $word->uk_phonetic,
                        ];
                    });
                $weakestWords = $weakestWords->concat($unreviewedWords);
            }

            // 8B. Grammar: Tìm bài học ngữ pháp dở dang (in_progress) hoặc bài kế tiếp (not_started)
            $suggestedGrammarLesson = null;
            foreach ($grammarCategories as $cat) {
                foreach ($cat['topics'] as $topic) {
                    foreach ($topic['lessons'] as $les) {
                        if ($les['status'] === 'in_progress') {
                            $suggestedGrammarLesson = $les;
                            break 3;
                        }
                    }
                }
            }
            if (!$suggestedGrammarLesson) {
                foreach ($grammarCategories as $cat) {
                    foreach ($cat['topics'] as $topic) {
                        foreach ($topic['lessons'] as $les) {
                            if ($les['status'] === 'not_started') {
                                $suggestedGrammarLesson = $les;
                                break 3;
                            }
                        }
                    }
                }
            }

            // -----------------------------------------------------------------------
            // 9. CUSTOM LEARNING ROADMAP — Lộ trình học tự xây dựng
            // -----------------------------------------------------------------------
            $defaultRoadmap = [
                [
                    'id' => 'step_1',
                    'title' => 'Chinh phục 50 từ vựng Giai đoạn 1 (Nền tảng)',
                    'category' => 'vocab',
                    'category_label' => '📘 Từ vựng',
                    'target_date' => date('Y-m-d', strtotime('+7 days')),
                    'status' => 'completed',
                    'notes' => 'Tập trung ôn Active Recall hàng ngày.'
                ],
                [
                    'id' => 'step_2',
                    'title' => 'Nắm vững 12 thì tiếng Anh cơ bản & Công thức',
                    'category' => 'grammar',
                    'category_label' => '📖 Ngữ pháp',
                    'target_date' => date('Y-m-d', strtotime('+14 days')),
                    'status' => 'in_progress',
                    'notes' => 'Hoàn thành đủ 3/3 phần (Công thức, Cách dùng, Ví dụ).'
                ],
                [
                    'id' => 'step_3',
                    'title' => 'Hoàn thành 5 bài Luyện nghe chép chính tả (Dictation)',
                    'category' => 'listening',
                    'category_label' => '🎧 Luyện nghe',
                    'target_date' => date('Y-m-d', strtotime('+21 days')),
                    'status' => 'in_progress',
                    'notes' => 'Đạt hiệu suất 80%+ trong bài nộp đầu tiên.'
                ],
                [
                    'id' => 'step_4',
                    'title' => 'Làm bài kiểm tra Đánh giá tổng hợp (Assessment)',
                    'category' => 'assessment',
                    'category_label' => '📝 Đề thi',
                    'target_date' => date('Y-m-d', strtotime('+30 days')),
                    'status' => 'not_started',
                    'notes' => 'Đạt điểm min_score 80+.'
                ]
            ];

            $customRoadmap = is_array($user->custom_roadmap) && count($user->custom_roadmap) > 0 
                ? $user->custom_roadmap 
                : $defaultRoadmap;

            $totalRoadmapSteps = count($customRoadmap);
            $completedRoadmapSteps = collect($customRoadmap)->where('status', 'completed')->count();
            $roadmapProgressPct = $totalRoadmapSteps > 0 ? round(($completedRoadmapSteps / $totalRoadmapSteps) * 100) : 0;

            return view('profile.index', compact(
                'user',
                'vocabWordsCount',
                'vocabReviewsCount',
                'grammarLessonsCount',
                'grammarReviewsCount',
                'assessmentsTaken',
                'avgAssessmentScore',
                'actualRetentionRate',
                'actualLearningSpeed',
                'streakDays',
                'avgVocabScore',
                'avgVocabDuration',
                'reviewTimeline',
                'monthlyStats',
                'grammarCategories',
                'grammarDoneTotal',
                'studiedWords',
                'totalVocabStudied',
                'totalVocabMastered',
                'achievements',
                'earnedCount',
                'totalAchievements',
                'weakestWords',
                'suggestedGrammarLesson',
                'customRoadmap',
                'totalRoadmapSteps',
                'completedRoadmapSteps',
                'roadmapProgressPct'
            ));
    }

    /**
     * Update the student personal details and self-assessed parameters.
     */
    public function update(Request $request)
    {
        $user = auth()->user() ?? User::first();

        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng!');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'english_level' => 'required|in:A1,A2,B1,B2,C1,C2',
            'target_goal' => 'nullable|string|max:255',
            'learning_speed' => 'required|in:Nhanh,Trung bình,Chậm',
            'retention_rate' => 'required|integer|between:10,100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle Custom Roadmap json payload if submitted
        if ($request->has('custom_roadmap_json')) {
            $decoded = json_decode($request->input('custom_roadmap_json'), true);
            if (is_array($decoded)) {
                $data['custom_roadmap'] = $decoded;
            }
        }

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            // Store directly in public/uploads for easy immediate rendering without storage:link issues
            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        $user->update($data);

        return redirect()->route('profile.index')->with('success', 'Hồ sơ cá nhân và lộ trình học tập đã được cập nhật thành công!');
    }

    /**
     * AJAX Toggle completion status of a roadmap step.
     */
    public function toggleRoadmapStep(Request $request)
    {
        $user = auth()->user() ?? User::first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Người dùng không tồn tại'], 404);
        }

        $stepId = $request->input('step_id');
        $newStatus = $request->input('status'); // 'completed', 'in_progress', 'not_started'

        $roadmap = is_array($user->custom_roadmap) && count($user->custom_roadmap) > 0 
            ? $user->custom_roadmap 
            : [
                [
                    'id' => 'step_1',
                    'title' => 'Chinh phục 50 từ vựng Giai đoạn 1 (Nền tảng)',
                    'category' => 'vocab',
                    'category_label' => '📘 Từ vựng',
                    'target_date' => date('Y-m-d', strtotime('+7 days')),
                    'status' => 'completed',
                    'notes' => 'Tập trung ôn Active Recall hàng ngày.'
                ],
                [
                    'id' => 'step_2',
                    'title' => 'Nắm vững 12 thì tiếng Anh cơ bản & Công thức',
                    'category' => 'grammar',
                    'category_label' => '📖 Ngữ pháp',
                    'target_date' => date('Y-m-d', strtotime('+14 days')),
                    'status' => 'in_progress',
                    'notes' => 'Hoàn thành đủ 3/3 phần (Công thức, Cách dùng, Ví dụ).'
                ],
                [
                    'id' => 'step_3',
                    'title' => 'Hoàn thành 5 bài Luyện nghe chép chính tả (Dictation)',
                    'category' => 'listening',
                    'category_label' => '🎧 Luyện nghe',
                    'target_date' => date('Y-m-d', strtotime('+21 days')),
                    'status' => 'in_progress',
                    'notes' => 'Đạt hiệu suất 80%+ trong bài nộp đầu tiên.'
                ],
                [
                    'id' => 'step_4',
                    'title' => 'Làm bài kiểm tra Đánh giá tổng hợp (Assessment)',
                    'category' => 'assessment',
                    'category_label' => '📝 Đề thi',
                    'target_date' => date('Y-m-d', strtotime('+30 days')),
                    'status' => 'not_started',
                    'notes' => 'Đạt điểm min_score 80+.'
                ]
            ];

        foreach ($roadmap as &$step) {
            if (($step['id'] ?? '') === $stepId) {
                $step['status'] = $newStatus;
                break;
            }
        }

        $user->custom_roadmap = $roadmap;
        $user->save();

        $totalSteps = count($roadmap);
        $completedSteps = collect($roadmap)->where('status', 'completed')->count();
        $progressPct = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật mốc lộ trình!',
            'completed' => $completedSteps,
            'total' => $totalSteps,
            'pct' => $progressPct,
        ]);
    }
    /**
     * Display the dedicated Custom Learning Roadmap dashboard (/roadmap).
     */
    public function showRoadmap()
    {
        $user = auth()->user() ?? User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Học Viên Anh Ngữ',
                'email' => 'student@englishhub.edu.vn',
                'password' => bcrypt('123456'),
                'english_level' => 'B1',
                'target_goal' => 'IELTS 7.0 & Giao tiếp trôi chảy',
                'learning_speed' => 'Trung bình',
                'retention_rate' => 75
            ]);
        }

        $defaultRoadmap = [
            [
                'id' => 'step_1',
                'title' => 'Từ hỏi cơ bản',
                'description' => 'What, Who, Where, When, Why, Which, How... → nắm toàn bộ cách dùng',
                'icon' => '❓',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '6 – 8 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1 – 1,5 giờ',
                'target_date' => date('Y-m-d', strtotime('+7 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_2',
                'title' => 'Động từ / Trợ động từ',
                'description' => 'do, does, did; cách đặt câu hỏi & phủ định',
                'icon' => '📗',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '8 – 10 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1,5 – 2 giờ',
                'target_date' => date('Y-m-d', strtotime('+10 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_3',
                'title' => 'Câu điều kiện',
                'description' => 'loại 0, 1, 2, 3 và cách nhận biết',
                'icon' => '🔀',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '8 – 10 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '2 – 2,5 giờ',
                'target_date' => date('Y-m-d', strtotime('+14 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_4',
                'title' => 'Wish',
                'description' => 'Wish hiện tại, quá khứ, tương lai',
                'icon' => '⭐',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '5 – 6 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1 – 1,5 giờ',
                'target_date' => date('Y-m-d', strtotime('+17 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_5',
                'title' => 'Câu trực tiếp → câu gián tiếp',
                'description' => 'lùi thì, đổi đại từ, đổi trạng từ thời gian/nơi chốn',
                'icon' => '🔄',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '10 – 12 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '2,5 – 3 giờ',
                'target_date' => date('Y-m-d', strtotime('+21 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_6',
                'title' => 'So sánh',
                'description' => 'tính từ ngắn, tính từ dài; so sánh hơn, nhất, bằng',
                'icon' => '📊',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '8 – 10 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '2 giờ',
                'target_date' => date('Y-m-d', strtotime('+24 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_7',
                'title' => 'So / Such / Too / Enough',
                'description' => 'cấu trúc và cách phân biệt',
                'icon' => '🔤',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '6 – 8 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1,5 – 2 giờ',
                'target_date' => date('Y-m-d', strtotime('+27 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_8',
                'title' => 'Đại từ quan hệ',
                'description' => 'Who – Whom – Which',
                'icon' => '🔗',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '6 – 8 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1,5 – 2 giờ',
                'target_date' => date('Y-m-d', strtotime('+30 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_9',
                'title' => 'Where',
                'description' => 'cách sử dụng Where trong mệnh đề / câu',
                'icon' => '📍',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '4 – 5 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '45 – 60 phút',
                'target_date' => date('Y-m-d', strtotime('+33 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_10',
                'title' => 'When – That',
                'description' => 'cách dùng trong câu và mệnh đề quan hệ',
                'icon' => '⏰',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '5 – 6 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1 – 1,5 giờ',
                'target_date' => date('Y-m-d', strtotime('+36 days')),
                'status' => 'not_started',
                'notes' => ''
            ],
            [
                'id' => 'step_11',
                'title' => 'Though – Although – Even though – Despite – In spite of',
                'description' => 'nhượng bộ',
                'icon' => '🔃',
                'category' => 'grammar',
                'category_label' => '📖 Ngữ pháp',
                'pages' => '6 – 8 trang',
                'material' => 'Gốc ngữ pháp Tiếng Anh – Basic',
                'estimated_time' => '1,5 – 2 giờ',
                'target_date' => date('Y-m-d', strtotime('+40 days')),
                'status' => 'not_started',
                'notes' => ''
            ]
        ];

        $customRoadmap = is_array($user->custom_roadmap) && count($user->custom_roadmap) > 0 
            ? $user->custom_roadmap 
            : $defaultRoadmap;

        $totalRoadmapSteps = count($customRoadmap);
        $completedRoadmapSteps = collect($customRoadmap)->where('status', 'completed')->count();
        $inProgressRoadmapSteps = collect($customRoadmap)->where('status', 'in_progress')->count();
        $notStartedRoadmapSteps = collect($customRoadmap)->where('status', 'not_started')->count();
        $roadmapProgressPct = $totalRoadmapSteps > 0 ? round(($completedRoadmapSteps / $totalRoadmapSteps) * 100) : 0;

        return view('roadmap.index', compact(
            'user',
            'customRoadmap',
            'totalRoadmapSteps',
            'completedRoadmapSteps',
            'inProgressRoadmapSteps',
            'notStartedRoadmapSteps',
            'roadmapProgressPct'
        ));
    }
}
