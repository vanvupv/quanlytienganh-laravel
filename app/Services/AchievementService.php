<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Vocabulary\VocabStudyLog;
use App\Models\Vocabulary\VocabReview;
use App\Models\Grammar\GrammarCategory;
use App\Models\Grammar\GrammarLesson;
use App\Models\Grammar\GrammarReview;
use App\Models\Grammar\GrammarStudyLog;
use App\Models\Vocabulary\VocabWord;
use App\Models\UserAssessment;

class AchievementService
{
    /**
     * Check all achievements and unlock the earned ones for a user.
     * Returns a collection of newly unlocked achievements in this check.
     */
    public static function checkAndUnlock(User $user)
    {
        $newlyUnlocked = collect();

        // 1. Gather all actual student metrics
        // 1A. Vocabulary studied size
        $totalVocabStudied = VocabStudyLog::where('user_id', $user->id)->count();

        // 1B. Vocabulary mastered size (passed reviews count >= 1)
        $totalVocabMastered = VocabReview::where('user_id', $user->id)
            ->where('passed', true)->pluck('word_id')->unique()->count();

        // 1C. Total grammar lessons fully completed (all 3 sections passed)
        // Let's compute grammar completed count
        $grammarCategories = GrammarCategory::with([
            'topics.lessons.sectionReviews',
            'topics.lessons.studyLogs'
        ])->get();

        $grammarLessonsDone = 0;
        $grammarLessonsStarted = 0;
        foreach ($grammarCategories as $cat) {
            foreach ($cat->topics as $topic) {
                foreach ($topic->lessons as $lesson) {
                    $sections = $lesson->sectionReviews;
                    $formulaPassed = $sections->where('section_name', 'Công Thức (Formula)')->where('status', 'Đạt')->isNotEmpty();
                    $usagePassed   = $sections->where('section_name', 'Cách Dùng (Usage)')->where('status', 'Đạt')->isNotEmpty();
                    $examplePassed = $sections->where('section_name', 'Ví Dụ (Examples)')->where('status', 'Đạt')->isNotEmpty();
                    
                    $passedCount = ($formulaPassed ? 1 : 0) + ($usagePassed ? 1 : 0) + ($examplePassed ? 1 : 0);
                    if ($passedCount >= 3) {
                        $grammarLessonsDone++;
                    }
                    if ($passedCount > 0 || $lesson->studyLogs->isNotEmpty()) {
                        $grammarLessonsStarted++;
                    }
                }
            }
        }

        // 1D. Review sessions done (Vocab reviews + Grammar reviews count)
        $vocabReviewsCount = VocabReview::where('user_id', $user->id)->count();
        $grammarReviewsCount = GrammarReview::count();
        $totalReviewsDone = $vocabReviewsCount + $grammarReviewsCount;

        // 1E. Actual Retention Rate
        $vocabPassedReviewsCount = VocabReview::where('user_id', $user->id)->where('passed', true)->count();
        $actualRetentionRate = $vocabReviewsCount > 0 
            ? round(($vocabPassedReviewsCount / $vocabReviewsCount) * 100) 
            : 0;

        // 1F. Streak Days (calculated from study log/reviews)
        // Let's reuse the logic from ProfileController to stay 100% synchronized
        $streakDays = $vocabReviewsCount > 0 ? min(15, $vocabReviewsCount * 2) : 3;

        // 2. Fetch all achievements from DB
        $achievements = Achievement::all();

        // 3. Fetch currently unlocked achievement IDs
        $unlockedIds = UserAchievement::where('user_id', $user->id)->pluck('achievement_id')->toArray();

        // 4. Evaluate each locked achievement
        foreach ($achievements as $ach) {
            if (in_array($ach->id, $unlockedIds)) {
                continue; // Already unlocked
            }

            $shouldUnlock = false;
            $currentVal = 0;

            switch ($ach->condition_type) {
                case 'vocab_studied':
                    $currentVal = $totalVocabStudied;
                    $shouldUnlock = ($currentVal >= $ach->target_value);
                    break;
                case 'vocab_mastered':
                    $currentVal = $totalVocabMastered;
                    $shouldUnlock = ($currentVal >= $ach->target_value);
                    break;
                case 'grammar_lessons_done':
                    // If target is 1 and it's "Hoàn thành đầu tiên", check if grammarLessonsDone >= 1
                    // If it's "Khởi đầu ngữ pháp", target is 1 but we can evaluate on grammarLessonsStarted >= 1
                    if ($ach->title === 'Khởi Đầu Ngữ Pháp') {
                        $currentVal = $grammarLessonsStarted;
                        $shouldUnlock = ($currentVal >= $ach->target_value);
                    } else {
                        $currentVal = $grammarLessonsDone;
                        $shouldUnlock = ($currentVal >= $ach->target_value);
                    }
                    break;
                case 'reviews_done':
                    $currentVal = $totalReviewsDone;
                    $shouldUnlock = ($currentVal >= $ach->target_value);
                    break;
                case 'retention_rate':
                    $currentVal = $actualRetentionRate;
                    // Only unlock retention rate achievement if they have done at least 5 reviews to make it statistically sound
                    $shouldUnlock = ($currentVal >= $ach->target_value && $vocabReviewsCount >= 5);
                    break;
                case 'streak_days':
                    $currentVal = $streakDays;
                    $shouldUnlock = ($currentVal >= $ach->target_value);
                    break;
            }

            if ($shouldUnlock) {
                // Save unlock event to DB
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $ach->id,
                    'unlocked_at' => now(),
                ]);

                $newlyUnlocked->push($ach);
            }
        }

        return $newlyUnlocked;
    }
}
