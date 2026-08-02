<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grammar\GrammarStudyLog;
use App\Models\Grammar\GrammarSectionReview;
use App\Models\Grammar\GrammarLesson;
use App\Models\Grammar\GrammarReview;

class GrammarStudyLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing logs
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GrammarStudyLog::truncate();
        GrammarSectionReview::truncate();
        GrammarReview::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get lessons in Topic 1 (12 Thì cơ bản)
        $lessons = GrammarLesson::whereHas('topic', function ($query) {
            $query->where('title', 'like', '%12 Thì%');
        })->orderBy('order', 'asc')->get();

        if ($lessons->isEmpty()) {
            return;
        }

        // --- SEED STUDY LOGS (Trực quan hóa số buổi học và ôn) ---
        
        // 1. Thì Hiện Tại Đơn (Lesson 1) - Học sinh đã học và ôn rất nhiều lần
        $l1 = $lessons->where('order', 1)->first();
        if ($l1) {
            GrammarStudyLog::create([
                'lesson_id' => $l1->id,
                'session_date' => '2026-05-10',
                'study_time' => 45, // 45 phút học lý thuyết ban đầu
                'review_time' => 0,
                'sessions_count' => 1
            ]);
            GrammarStudyLog::create([
                'lesson_id' => $l1->id,
                'session_date' => '2026-05-12',
                'study_time' => 0,
                'review_time' => 20, // 20 phút ôn tập
                'sessions_count' => 1
            ]);
            GrammarStudyLog::create([
                'lesson_id' => $l1->id,
                'session_date' => '2026-05-15',
                'study_time' => 0,
                'review_time' => 15, // 15 phút ôn tập lần 2
                'sessions_count' => 1
            ]);

            // Chi tiết từng phần (Formula, Usage, Examples)
            GrammarSectionReview::create([
                'lesson_id' => $l1->id,
                'section_name' => 'Công Thức (Formula)',
                'study_time' => 15,
                'min_review_time' => 10,
                'review_count' => 3,
                'review_time' => 15,
                'review_score' => 90, // Đạt (>= 80% & >= min_review_time)
                'status' => 'Đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l1->id,
                'section_name' => 'Cách Dùng (Usage)',
                'study_time' => 20,
                'min_review_time' => 15,
                'review_count' => 2,
                'review_time' => 12, // Chưa đạt do thời gian ôn tập < ôn tập tối thiểu (12 < 15)
                'review_score' => 75,
                'status' => 'Chưa đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l1->id,
                'section_name' => 'Ví Dụ (Examples)',
                'study_time' => 10,
                'min_review_time' => 10,
                'review_count' => 2,
                'review_time' => 8, // Chưa đạt do thời gian ôn tập < tối thiểu
                'review_score' => 85,
                'status' => 'Chưa đạt'
            ]);

            // Seed a sample detailed review attempt
            GrammarReview::create([
                'lesson_id' => $l1->id,
                'score' => 87,
                'duration_seconds' => 145,
                'passed' => true,
                'answers' => [
                    'formulas' => [
                        'affirmative' => [
                            'name' => 'Thể khẳng định (+)',
                            'student' => 'S + V(s/es) + O',
                            'actual' => 'S + V(s/es) / S + am/is/are + O',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        'negative' => [
                            'name' => 'Thể phủ định (-)',
                            'student' => 'S + do/does not + V',
                            'actual' => 'S + do/does + not + V / S + am/is/are + not + O',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        'question' => [
                            'name' => 'Thể nghi vấn (?)',
                            'student' => 'Do/Does + S + V?',
                            'actual' => 'Do/Does + S + V? / Am/Is/Are + S + O?',
                            'is_correct' => true,
                            'score' => 10
                        ]
                    ],
                    'usages' => [
                        [
                            'name' => 'Cách dùng 1',
                            'student' => 'Diễn tả hành động thói quen lặp đi lặp lại ở hiện tại',
                            'actual' => 'Diễn tả thói quen hoặc hành động lặp đi lặp lại ở hiện tại.',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        [
                            'name' => 'Cách dùng 2',
                            'student' => 'Diễn tả chân lý sự thật hiển nhiên',
                            'actual' => 'Diễn tả sự thật hiển nhiên, chân lý khoa học.',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        [
                            'name' => 'Cách dùng 3',
                            'student' => 'Diễn tả một thời gian biểu lịch trình tàu xe',
                            'actual' => 'Diễn tả lịch trình, thời gian biểu cố định.',
                            'is_correct' => true,
                            'score' => 10
                        ]
                    ],
                    'examples' => [
                        [
                            'vietnamese' => 'Diễn tả thói quen',
                            'student' => 'He plays football every Sunday',
                            'actual' => 'He plays football every Sunday.',
                            'is_correct' => true,
                            'score' => 13.3
                        ],
                        [
                            'vietnamese' => 'Diễn tả chân lý sự thật hiển nhiên',
                            'student' => 'Water boil at 100 degrees Celsius',
                            'actual' => 'Water boils at 100 degrees Celsius.',
                            'is_correct' => false, // incorrect due to 'boil' instead of 'boils'
                            'score' => 0
                        ],
                        [
                            'vietnamese' => 'Diễn tả lịch trình',
                            'student' => 'The train leaves at 8 AM',
                            'actual' => 'The train leaves at 8 AM.',
                            'is_correct' => true,
                            'score' => 13.3
                        ]
                    ]
                ]
            ]);

            // Seed a second (earlier) review attempt with a lower score
            GrammarReview::create([
                'lesson_id' => $l1->id,
                'score' => 65,
                'duration_seconds' => 195,
                'passed' => false,
                'created_at' => '2026-05-14 10:15:00',
                'answers' => [
                    'formulas' => [
                        'affirmative' => [
                            'name' => 'Thể khẳng định (+)',
                            'student' => 'S + V',
                            'actual' => 'S + V(s/es) / S + am/is/are + O',
                            'is_correct' => false,
                            'score' => 0
                        ],
                        'negative' => [
                            'name' => 'Thể phủ định (-)',
                            'student' => 'S + not + V',
                            'actual' => 'S + do/does + not + V / S + am/is/are + not + O',
                            'is_correct' => false,
                            'score' => 0
                        ],
                        'question' => [
                            'name' => 'Thể nghi vấn (?)',
                            'student' => 'Do/Does + S + V?',
                            'actual' => 'Do/Does + S + V? / Am/Is/Are + S + O?',
                            'is_correct' => true,
                            'score' => 10
                        ]
                    ],
                    'usages' => [
                        [
                            'name' => 'Cách dùng 1',
                            'student' => 'Diễn tả thói quen',
                            'actual' => 'Diễn tả thói quen hoặc hành động lặp đi lặp lại ở hiện tại.',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        [
                            'name' => 'Cách dùng 2',
                            'student' => 'Diễn tả chân lý',
                            'actual' => 'Diễn tả sự thật hiển nhiên, chân lý khoa học.',
                            'is_correct' => true,
                            'score' => 10
                        ],
                        [
                            'name' => 'Cách dùng 3',
                            'student' => 'Không biết',
                            'actual' => 'Diễn tả lịch trình, thời gian biểu cố định.',
                            'is_correct' => false,
                            'score' => 0
                        ]
                    ],
                    'examples' => [
                        [
                            'vietnamese' => 'Diễn tả thói quen',
                            'student' => 'He plays soccer',
                            'actual' => 'He plays football every Sunday.',
                            'is_correct' => false,
                            'score' => 0
                        ],
                        [
                            'vietnamese' => 'Diễn tả chân lý sự thật hiển nhiên',
                            'student' => 'Water boils at 100 degrees Celsius',
                            'actual' => 'Water boils at 100 degrees Celsius.',
                            'is_correct' => true,
                            'score' => 13.3
                        ],
                        [
                            'vietnamese' => 'Diễn tả lịch trình',
                            'student' => 'The train leaves at 8 AM',
                            'actual' => 'The train leaves at 8 AM.',
                            'is_correct' => true,
                            'score' => 13.3
                        ]
                    ]
                ]
            ]);
        }

        // 2. Thì Hiện Tại Tiếp Diễn (Lesson 2)
        $l2 = $lessons->where('order', 2)->first();
        if ($l2) {
            GrammarStudyLog::create([
                'lesson_id' => $l2->id,
                'session_date' => '2026-05-11',
                'study_time' => 35,
                'review_time' => 0,
                'sessions_count' => 1
            ]);
            GrammarStudyLog::create([
                'lesson_id' => $l2->id,
                'session_date' => '2026-05-13',
                'study_time' => 0,
                'review_time' => 15,
                'sessions_count' => 1
            ]);

            // Chi tiết từng phần
            GrammarSectionReview::create([
                'lesson_id' => $l2->id,
                'section_name' => 'Công Thức (Formula)',
                'study_time' => 10,
                'min_review_time' => 10,
                'review_count' => 2,
                'review_time' => 12,
                'review_score' => 95,
                'status' => 'Đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l2->id,
                'section_name' => 'Cách Dùng (Usage)',
                'study_time' => 15,
                'min_review_time' => 10,
                'review_count' => 1,
                'review_time' => 8, // Chưa đạt vì thời gian ôn < tối thiểu và điểm < 80
                'review_score' => 60,
                'status' => 'Chưa đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l2->id,
                'section_name' => 'Ví Dụ (Examples)',
                'study_time' => 10,
                'min_review_time' => 8,
                'review_count' => 2,
                'review_time' => 10,
                'review_score' => 90,
                'status' => 'Đạt'
            ]);
        }

        // 3. Thì Hiện Tại Hoàn Thành (Lesson 3)
        $l3 = $lessons->where('order', 3)->first();
        if ($l3) {
            GrammarStudyLog::create([
                'lesson_id' => $l3->id,
                'session_date' => '2026-05-14',
                'study_time' => 50,
                'review_time' => 0,
                'sessions_count' => 1
            ]);
            GrammarStudyLog::create([
                'lesson_id' => $l3->id,
                'session_date' => '2026-05-16',
                'study_time' => 0,
                'review_time' => 25,
                'sessions_count' => 1
            ]);

            // Chi tiết từng phần
            GrammarSectionReview::create([
                'lesson_id' => $l3->id,
                'section_name' => 'Công Thức (Formula)',
                'study_time' => 20,
                'min_review_time' => 12,
                'review_count' => 3,
                'review_time' => 15,
                'review_score' => 85,
                'status' => 'Đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l3->id,
                'section_name' => 'Cách Dùng (Usage)',
                'study_time' => 15,
                'min_review_time' => 12,
                'review_count' => 2,
                'review_time' => 14,
                'review_score' => 90,
                'status' => 'Đạt'
            ]);
            GrammarSectionReview::create([
                'lesson_id' => $l3->id,
                'section_name' => 'Ví Dụ (Examples)',
                'study_time' => 15,
                'min_review_time' => 10,
                'review_count' => 1,
                'review_time' => 6, // Chưa đạt vì thời gian ôn < 10 và điểm < 80
                'review_score' => 70,
                'status' => 'Chưa đạt'
            ]);
        }

        // 4. Các thì tiếp theo - Học sinh mới học lý thuyết ban đầu (Chưa ôn tập)
        for ($i = 4; $i <= 6; $i++) {
            $lesson = $lessons->where('order', $i)->first();
            if ($lesson) {
                GrammarStudyLog::create([
                    'lesson_id' => $lesson->id,
                    'session_date' => date('Y-m-d', strtotime("2026-05-10 + $i days")),
                    'study_time' => 30,
                    'review_time' => 0,
                    'sessions_count' => 1
                ]);

                // Chi tiết các phần (Chưa ôn tập)
                GrammarSectionReview::create([
                    'lesson_id' => $lesson->id,
                    'section_name' => 'Công Thức (Formula)',
                    'study_time' => 10,
                    'min_review_time' => 8,
                    'review_count' => 0,
                    'review_time' => 0,
                    'review_score' => 0,
                    'status' => 'Chưa đạt'
                ]);
                GrammarSectionReview::create([
                    'lesson_id' => $lesson->id,
                    'section_name' => 'Cách Dùng (Usage)',
                    'study_time' => 10,
                    'min_review_time' => 10,
                    'review_count' => 0,
                    'review_time' => 0,
                    'review_score' => 0,
                    'status' => 'Chưa đạt'
                ]);
                GrammarSectionReview::create([
                    'lesson_id' => $lesson->id,
                    'section_name' => 'Ví Dụ (Examples)',
                    'study_time' => 10,
                    'min_review_time' => 8,
                    'review_count' => 0,
                    'review_time' => 0,
                    'review_score' => 0,
                    'status' => 'Chưa đạt'
                ]);
            }
        }
    }
}
