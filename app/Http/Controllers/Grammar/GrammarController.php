<?php

namespace App\Http\Controllers\Grammar;

use App\Http\Controllers\Controller;
use App\Models\Grammar\GrammarCategory;
use App\Models\Grammar\GrammarTopic;
use App\Models\Grammar\GrammarLesson;
use Illuminate\Http\Request;

class GrammarController extends Controller
{
    /**
     * Display the dynamic Grammar Timeline & Roadmap.
     */
    public function index()
    {
        $categories = GrammarCategory::with(['topics' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'topics.lessons' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'topics.lessons.reviews', 'topics.lessons.progress'])->orderBy('order', 'asc')->get();

        $dueLessons = collect();

        // Calculate SRS status for each lesson
        foreach ($categories as $cat) {
            foreach ($cat->topics as $topic) {
                foreach ($topic->lessons as $lesson) {
                    $lesson->srs_status = null;
                    $lesson->memory_strength = 100;
                    $lesson->next_review_at = null;
                    
                    $isCompleted = $lesson->progress->contains('knowledge_key', 'lesson_completed');
                    if (!$isCompleted) {
                        continue; // If not completed, it has no SRS status yet
                    }

                    $reviews = $lesson->reviews->sortByDesc('created_at');
                    $latestReview = $reviews->first();

                    // Count consecutive passed reviews (score >= 80)
                    $consecutivePasses = 0;
                    foreach ($reviews as $rev) {
                        if ($rev->score >= 80) {
                            $consecutivePasses++;
                        } else {
                            break; // Stop at the first failed review
                        }
                    }

                    // SRS interval calculation
                    if ($latestReview && $latestReview->score < 80) {
                        $intervalDays = 1; // Reset to 1 day if failed
                    } else {
                        switch ($consecutivePasses) {
                            case 0:
                                $intervalDays = 1;
                                break;
                            case 1:
                                $intervalDays = 1;
                                break;
                            case 2:
                                $intervalDays = 3;
                                break;
                            case 3:
                                $intervalDays = 7;
                                break;
                            case 4:
                                $intervalDays = 14;
                                break;
                            default:
                                $intervalDays = 30;
                                break;
                        }
                    }

                    // Last activity date is either the last review or the completion date
                    $completionRecord = $lesson->progress->firstWhere('knowledge_key', 'lesson_completed');
                    $lastActivityDate = $latestReview 
                        ? $latestReview->created_at 
                        : ($completionRecord ? $completionRecord->created_at : now());

                    $nextReviewDate = $lastActivityDate->copy()->addDays($intervalDays);
                    $lesson->next_review_at = $nextReviewDate;

                    $daysPastDue = now()->diffInDays($nextReviewDate, false); // Negative if overdue

                    if ($daysPastDue < 0) {
                        $lesson->srs_status = 'Cần ôn tập'; // Overdue
                        // Memory strength decays by 10% for each day overdue, minimum 10%
                        $lesson->memory_strength = max(10, 100 - (abs($daysPastDue) * 10));
                    } elseif ($daysPastDue <= 1) {
                        $lesson->srs_status = 'Đang phai nhạt'; // Fading (due within 1 day)
                        $lesson->memory_strength = 75;
                    } else {
                        $lesson->srs_status = 'Đã thuộc'; // Mastered
                        $lesson->memory_strength = 100;
                    }

                    // Add to due lessons list if it needs review
                    if ($lesson->srs_status === 'Cần ôn tập' || $lesson->srs_status === 'Đang phai nhạt') {
                        $dueLessons->push($lesson);
                    }
                }
            }
        }

        return view('grammars.gram', compact('categories', 'dueLessons'));
    }

    /**
     * Admin Panel Dashboard for Grammar Management.
     */
    public function adminIndex()
    {
        $categoriesCount = GrammarCategory::count();
        $topicsCount = GrammarTopic::count();
        $lessonsCount = GrammarLesson::count();

        $categories = GrammarCategory::withCount('topics')->orderBy('order', 'asc')->get();
        $topics = GrammarTopic::withCount('lessons')->with('category')->orderBy('order', 'asc')->get();
        $lessons = GrammarLesson::with('topic.category')->orderBy('order', 'asc')->paginate(10);

        // --- STUDY ANALYTICS (TỔNG KẾT HỌC TẬP GIAI ĐOẠN 1) ---
        $stage1 = GrammarCategory::where('order', 1)->first();
        $totalStage1Time = 0;
        $totalStage1Sessions = 0;
        $lessonsStats = collect();
        $overallScore = 0;
        $reviewedLessonsCount = 0;
        $totalLessonsInStage1 = 0;

        if ($stage1) {
            $stage1LessonIds = GrammarLesson::whereHas('topic', function ($query) use ($stage1) {
                $query->where('category_id', $stage1->id);
            })->pluck('id');

            $totalLessonsInStage1 = $stage1LessonIds->count();

            // Total study and review times in Giai đoạn 1 (in minutes)
            $stage1StudyTime = \App\Models\Grammar\GrammarStudyLog::whereIn('lesson_id', $stage1LessonIds)->sum('study_time');
            $stage1ReviewTime = \App\Models\Grammar\GrammarStudyLog::whereIn('lesson_id', $stage1LessonIds)->sum('review_time');
            $totalStage1Time = $stage1StudyTime + $stage1ReviewTime;

            // Total sessions in Giai đoạn 1
            $totalStage1Sessions = \App\Models\Grammar\GrammarStudyLog::whereIn('lesson_id', $stage1LessonIds)->sum('sessions_count');

            // Lessons study/review statistics
            $lessonsStats = GrammarLesson::whereHas('topic', function ($query) use ($stage1) {
                $query->where('category_id', $stage1->id);
            })->with(['studyLogs', 'sectionReviews'])
              ->orderBy('order', 'asc')
              ->get()
              ->map(function ($lesson) {
                  $learnTime = $lesson->studyLogs->sum('study_time');
                  $revTime = $lesson->studyLogs->sum('review_time');
                  $sessions = $lesson->studyLogs->sum('sessions_count');
                  
                  $sections = $lesson->sectionReviews;
                  $passedSections = $sections->where('status', 'Đạt')->count();
                  $totalSections = $sections->count();
                  
                  $averageScore = $totalSections > 0 ? round($sections->avg('review_score')) : 0;

                  return [
                      'id' => $lesson->id,
                      'title' => $lesson->title,
                      'study_time' => $learnTime,
                      'review_time' => $revTime,
                      'sessions_count' => $sessions,
                      'sections' => $sections,
                      'passed_sections' => $passedSections,
                      'total_sections' => $totalSections,
                      'average_score' => $averageScore,
                  ];
              });

            // Count lessons reviewed (review_time > 0)
            $reviewedLessonsCount = $lessonsStats->where('review_time', '>', 0)->count();

            // Calculate overall average grammar score for active lessons
            $reviewedLessons = $lessonsStats->where('sessions_count', '>', 0);
            $overallScore = $reviewedLessons->count() > 0 ? round($reviewedLessons->avg('average_score')) : 0;
        }

        return view('grammars.admin.index', compact(
            'categoriesCount', 
            'topicsCount', 
            'lessonsCount',
            'categories',
            'topics',
            'lessons',
            'totalStage1Time',
            'totalStage1Sessions',
            'lessonsStats',
            'overallScore',
            'reviewedLessonsCount',
            'totalLessonsInStage1'
        ));
    }
}
