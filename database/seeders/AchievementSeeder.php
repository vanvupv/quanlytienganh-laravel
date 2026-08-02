<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // ── TỪ VỰNG (VOCAB) ───────────────────────────────────────────────────
            [
                'title' => 'Chập Chững Bước Đầu',
                'description' => 'Bắt đầu học từ vựng đầu tiên',
                'icon' => '🌱',
                'category' => 'vocab',
                'condition_type' => 'vocab_studied',
                'target_value' => 1,
            ],
            [
                'title' => 'Tích Lũy Từ Vựng',
                'description' => 'Học được 10 từ vựng',
                'icon' => '📘',
                'category' => 'vocab',
                'condition_type' => 'vocab_studied',
                'target_value' => 10,
            ],
            [
                'title' => 'Học Giả Từ Vựng',
                'description' => 'Học được 30 từ vựng',
                'icon' => '📚',
                'category' => 'vocab',
                'condition_type' => 'vocab_studied',
                'target_value' => 30,
            ],
            [
                'title' => 'Thành Thạo Đầu Tiên',
                'description' => 'Đạt 5 từ vựng ở mức thành thạo',
                'icon' => '⭐',
                'category' => 'vocab',
                'condition_type' => 'vocab_mastered',
                'target_value' => 5,
            ],
            [
                'title' => 'Kho Từ Vựng Vàng',
                'description' => 'Đạt 20 từ vựng ở mức thành thạo',
                'icon' => '🏆',
                'category' => 'vocab',
                'condition_type' => 'vocab_mastered',
                'target_value' => 20,
            ],

            // ── NGỮ PHÁP (GRAMMAR) ────────────────────────────────────────────────
            [
                'title' => 'Khởi Đầu Ngữ Pháp',
                'description' => 'Học bài lý thuyết ngữ pháp đầu tiên',
                'icon' => '📖',
                'category' => 'grammar',
                'condition_type' => 'grammar_lessons_done',
                'target_value' => 1,
            ],
            [
                'title' => 'Hoàn Thành Đầu Tiên',
                'description' => 'Hoàn thành trọn vẹn 1 bài ngữ pháp (3/3 phần)',
                'icon' => '✅',
                'category' => 'grammar',
                'condition_type' => 'grammar_lessons_done',
                'target_value' => 1, // Sẽ có logic kiểm tra hoàn thành trọn vẹn
            ],
            [
                'title' => 'Học Sinh Chăm Chỉ',
                'description' => 'Hoàn thành trọn vẹn 5 bài ngữ pháp',
                'icon' => '🎯',
                'category' => 'grammar',
                'condition_type' => 'grammar_lessons_done',
                'target_value' => 5,
            ],

            // ── ÔN TẬP (REVIEW) ──────────────────────────────────────────────────
            [
                'title' => 'Lần Đầu Ôn Tập',
                'description' => 'Hoàn thành bài ôn tập đầu tiên',
                'icon' => '🎓',
                'category' => 'review',
                'condition_type' => 'reviews_done',
                'target_value' => 1,
            ],
            [
                'title' => 'Lửa Học Tập',
                'description' => 'Hoàn thành 10 lượt ôn tập tích lũy',
                'icon' => '🔥',
                'category' => 'review',
                'condition_type' => 'reviews_done',
                'target_value' => 10,
            ],
            [
                'title' => 'Trí Nhớ Siêu Phàm',
                'description' => 'Đạt tỷ lệ ghi nhớ thực tế từ 80% trở lên',
                'icon' => '🧠',
                'category' => 'review',
                'condition_type' => 'retention_rate',
                'target_value' => 80,
            ],

            // ── KỶ LUẬT STREAK (STREAK) ──────────────────────────────────────────
            [
                'title' => 'Kiên Trì 3 Ngày',
                'description' => 'Duy trì streak học 3 ngày liên tục',
                'icon' => '⚡',
                'category' => 'streak',
                'condition_type' => 'streak_days',
                'target_value' => 3,
            ],
            [
                'title' => 'Một Tuần Bất Bại',
                'description' => 'Duy trì streak học 7 ngày liên tục',
                'icon' => '🌟',
                'category' => 'streak',
                'condition_type' => 'streak_days',
                'target_value' => 7,
            ],
            [
                'title' => 'Huyền Thoại 15 Ngày',
                'description' => 'Duy trì streak học 15 ngày liên tục',
                'icon' => '🏅',
                'category' => 'streak',
                'condition_type' => 'streak_days',
                'target_value' => 15,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(
                ['title' => $ach['title']],
                $ach
            );
        }
    }
}
