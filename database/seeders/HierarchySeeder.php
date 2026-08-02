<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hierarchy\HierarchyTree;
use App\Models\Hierarchy\HierarchyNode;
use App\Models\Grammar\GrammarLesson;
use App\Models\Vocabulary\VocabWord;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================================================================
        // 1. NGỮ PHÁP (GRAMMAR ROADMAP)
        // =========================================================================
        $tree = HierarchyTree::updateOrCreate(
            ['code' => 'grammar_roadmap'],
            [
                'name' => '🌳 Lộ Trình Học Ngữ Pháp Toàn Diện',
                'module' => 'grammar',
                'description' => 'Lộ trình học, ôn tập và đánh giá năng lực ngữ pháp tiếng Anh toàn diện chia theo các cấp độ.'
            ]
        );

        // Clear existing nodes for this tree to avoid duplicates on re-run
        $tree->nodes()->delete();

        // Fetch some real grammar lessons from database to link them
        $lessons = GrammarLesson::take(4)->get();

        // Create Stage 1
        $stage1 = HierarchyNode::create([
            'tree_id' => $tree->id,
            'parent_id' => null,
            'title' => 'Giai đoạn 1: Nền tảng cơ bản',
            'icon' => '🌱',
            'order' => 1
        ]);

        // Create Topic Under Stage 1
        $topic1 = HierarchyNode::create([
            'tree_id' => $tree->id,
            'parent_id' => $stage1->id,
            'title' => 'Các thì thời tiếng Anh (Tenses)',
            'icon' => '⏱️',
            'order' => 1
        ]);

        // Link lessons under Topic 1
        $order = 1;
        foreach ($lessons->take(2) as $lesson) {
            HierarchyNode::create([
                'tree_id' => $tree->id,
                'parent_id' => $topic1->id,
                'title' => $lesson->title,
                'icon' => '📖',
                'nodeable_type' => GrammarLesson::class,
                'nodeable_id' => $lesson->id,
                'order' => $order++
            ]);
        }

        // Create Stage 2
        $stage2 = HierarchyNode::create([
            'tree_id' => $tree->id,
            'parent_id' => null,
            'title' => 'Giai đoạn 2: Bứt phá & Nâng cao',
            'icon' => '🚀',
            'order' => 2
        ]);

        // Create Topic Under Stage 2
        $topic2 = HierarchyNode::create([
            'tree_id' => $tree->id,
            'parent_id' => $stage2->id,
            'title' => 'Cấu trúc câu phức hợp (Clauses & Conditionals)',
            'icon' => '🧩',
            'order' => 1
        ]);

        // Link remaining lessons under Topic 2
        foreach ($lessons->skip(2)->take(2) as $lesson) {
            HierarchyNode::create([
                'tree_id' => $tree->id,
                'parent_id' => $topic2->id,
                'title' => $lesson->title,
                'icon' => '📝',
                'nodeable_type' => GrammarLesson::class,
                'nodeable_id' => $lesson->id,
                'order' => $order++
            ]);
        }

        // Create a static reference node
        HierarchyNode::create([
            'tree_id' => $tree->id,
            'parent_id' => $stage2->id,
            'title' => 'Tài liệu tham khảo thêm',
            'icon' => '📚',
            'order' => 2
        ]);

        // =========================================================================
        // 2. TỪ VỰNG (CẤU TRÚC GIẢI PHẪU TỪ VỰNG: RUN)
        // =========================================================================
        $runTree = HierarchyTree::updateOrCreate(
            ['code' => 'word_run_tree'],
            [
                'name' => '🌳 Cấu Trúc Từ Vựng: Run',
                'module' => 'vocabulary',
                'description' => 'Sơ đồ phân tích chi tiết toàn bộ thuộc tính cấu trúc của từ "Run" bao gồm phiên âm, từ loại, ý nghĩa, ví dụ, chủ đề và nguồn tài liệu tham khảo.'
            ]
        );

        $runTree->nodes()->delete();

        // Lấy từ gốc "Run" trong database
        $runWord = VocabWord::where('word', 'Run')->first();

        // 2.1. Nút gốc: Từ (Word)
        $rootWordNode = HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => null,
            'title' => 'Word (Từ gốc): Run',
            'icon' => '🔤',
            'order' => 1
        ]);

        // 2.2. Phiên âm Anh (UK Phonetic)
        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $rootWordNode->id,
            'title' => 'UK Phonetic (Phiên âm Anh): /rʌn/',
            'icon' => '🇬🇧',
            'order' => 1
        ]);

        // 2.3. Phiên âm Mỹ (US Phonetic)
        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $rootWordNode->id,
            'title' => 'US Phonetic (Phiên âm Mỹ): /rʌn/',
            'icon' => '🇺🇸',
            'order' => 2
        ]);

        // 2.4. Nhánh thuộc tính nghĩa 1 (Giai đoạn 1 - Nền tảng)
        $branch1 = HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $rootWordNode->id,
            'title' => 'Nghĩa 1 (Giai đoạn 1: Nền tảng)',
            'icon' => '🌱',
            'order' => 3,
            'metadata' => ['color' => '#10b981']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch1->id,
            'title' => 'POS (Từ loại): Verb',
            'icon' => '🏷️',
            'order' => 1
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch1->id,
            'title' => 'Meaning (Ý nghĩa): Chạy bộ',
            'icon' => '💡',
            'order' => 2
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch1->id,
            'title' => 'Example (Ví dụ): He runs every morning.',
            'icon' => '📝',
            'order' => 3,
            'metadata' => ['description' => 'Bản dịch: Anh ấy chạy bộ mỗi sáng.']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch1->id,
            'title' => 'Topic (Chủ đề): Hoạt động',
            'icon' => '📌',
            'order' => 4
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch1->id,
            'title' => 'Reference (Tài liệu): TACB',
            'icon' => '📖',
            'order' => 5
        ]);

        // 2.5. Nhánh thuộc tính nghĩa 2 (Giai đoạn 2 - Phát triển)
        $branch2 = HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $rootWordNode->id,
            'title' => 'Nghĩa 2 (Giai đoạn 2: Phát triển)',
            'icon' => '🚀',
            'order' => 4,
            'metadata' => ['color' => '#3b82f6']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch2->id,
            'title' => 'POS (Từ loại): Noun',
            'icon' => '🏷️',
            'order' => 1
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch2->id,
            'title' => 'Meaning (Ý nghĩa): Cuộc chạy / Quãng đường chạy',
            'icon' => '💡',
            'order' => 2
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch2->id,
            'title' => 'Example (Ví dụ): I went for a run.',
            'icon' => '📝',
            'order' => 3,
            'metadata' => ['description' => 'Bản dịch: Tôi đã đi chạy một vòng.']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch2->id,
            'title' => 'Topic (Chủ đề): Thể thao',
            'icon' => '📌',
            'order' => 4
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch2->id,
            'title' => 'Reference (Tài liệu): Oxford 3000',
            'icon' => '📖',
            'order' => 5
        ]);

        // 2.6. Nhánh thuộc tính nghĩa 3 (Giai đoạn 3 - Nâng cao)
        $branch3 = HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $rootWordNode->id,
            'title' => 'Nghĩa 3 (Giai đoạn 3: Nâng cao)',
            'icon' => '🏆',
            'order' => 5,
            'metadata' => ['color' => '#8b5cf6']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch3->id,
            'title' => 'POS (Từ loại): Verb',
            'icon' => '🏷️',
            'order' => 1
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch3->id,
            'title' => 'Meaning (Ý nghĩa): Điều hành / Quản lý',
            'icon' => '💡',
            'order' => 2
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch3->id,
            'title' => 'Example (Ví dụ): She runs a company.',
            'icon' => '📝',
            'order' => 3,
            'metadata' => ['description' => 'Bản dịch: Cô ấy điều hành một công ty.']
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch3->id,
            'title' => 'Topic (Chủ đề): Kinh doanh',
            'icon' => '📌',
            'order' => 4
        ]);

        HierarchyNode::create([
            'tree_id' => $runTree->id,
            'parent_id' => $branch3->id,
            'title' => 'Reference (Tài liệu): Oxford 3000',
            'icon' => '📖',
            'order' => 5
        ]);

        $this->command->info('HierarchySeeder: Seeded grammar and vocabulary roadmaps successfully!');
    }
}
