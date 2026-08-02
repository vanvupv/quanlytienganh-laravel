<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document\Document;
use App\Models\Document\DocumentPart;
use App\Models\Document\DocumentUnit;
use App\Models\Listening\Listening;
use App\Models\Listening\ListeningTopic;
use App\Models\Listening\ListeningTranscript;
use App\Models\User;
use Illuminate\Support\Str;

class DocumentListeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create an admin user for created_by
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Quản Trị Viên',
                'email' => 'admin@quanlytienganh.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ]);
        }
        $userId = $user->id;

        // 1. Create Topics
        $topics = [
            ['name' => 'Giao tiếp hàng ngày', 'description' => 'Các bài nghe hội thoại giao tiếp cơ bản thường gặp.'],
            ['name' => 'Đời sống học đường', 'description' => 'Chủ đề trường học, lớp học và các cuộc trò chuyện học sinh.'],
            ['name' => 'Du lịch & Khám phá', 'description' => 'Hội thoại tại sân bay, khách sạn, nhà hàng.'],
            ['name' => 'Công việc & Sự nghiệp', 'description' => 'Hội thoại công sở, phỏng vấn, đàm phán.'],
        ];

        $topicIds = [];
        foreach ($topics as $t) {
            $topic = ListeningTopic::updateOrCreate(
                ['name' => $t['name']],
                [
                    'slug' => Str::slug($t['name']),
                    'description' => $t['description'],
                ]
            );
            $topicIds[$t['name']] = $topic->id;
        }

        // 2. Create Documents
        // Document 1: Tactics for Listening - Basic (A1-A2)
        $doc1 = Document::updateOrCreate(
            ['name' => 'Tactics for Listening - Basic (3rd Edition)'],
            [
                'description' => 'Bộ tài liệu rèn luyện kỹ năng nghe tiếng Anh cơ bản phù hợp cho người bắt đầu.',
                'skill' => 'listening',
                'level' => 'A2',
                'proficiency' => 'Beginner',
                'author' => 'Jack C. Richards',
                'publisher' => 'Oxford University Press',
                'published_year' => 2011,
                'version' => '3rd Edition',
                'isbn' => '978-0194013758',
                'language' => 'en',
                'cover_image' => null, // Không có sẵn file upload thực tế thì để null hoặc URL placeholder
                'page_count' => 120,
                'dimensions' => '21x29.7 cm',
                'file_size' => '45 MB',
                'file_format' => 'PDF',
                'total_lessons' => 24,
                'status' => 'active',
                'created_by' => $userId,
                'overview' => 'Giáo trình luyện nghe gồm 24 bài học với các chủ đề gần gũi như tên tuổi, mô tả người, quần áo, thời tiết.',
                'chapter_list' => ['Chương 1: Giới thiệu bản thân & Cuộc sống hàng ngày', 'Chương 2: Mua sắm & Hoạt động giải trí'],
                'chapter_contents' => [
                    'Chương 1: Giới thiệu bản thân & Cuộc sống hàng ngày' => 'Học cách nghe thông tin cá nhân, miêu tả người và đồ vật xung quanh.',
                    'Chương 2: Mua sắm & Hoạt động giải trí' => 'Luyện nghe các đoạn hội thoại mua sắm quần áo, ăn uống tại nhà hàng.'
                ],
                'chapter_volumes' => [
                    'Chương 1: Giới thiệu bản thân & Cuộc sống hàng ngày' => 'Gồm Unit 1 đến Unit 12.',
                    'Chương 2: Mua sắm & Hoạt động giải trí' => 'Gồm Unit 13 đến Unit 24.'
                ],
                'learning_objective' => 'Nắm bắt các thông tin số liệu, tên tuổi, miêu tả ngoại hình cơ bản trong hội thoại ngắn.',
                'study_duration' => 20,
                'review_duration' => 10,
                'assessment' => 'Nghe và chép lại chính tả các hội thoại quan trọng.',
                'completion_criteria' => ['min_lessons' => 12, 'min_score' => 80],
                'journey_status' => 'in_progress',
            ]
        );

        // Document 2: Cambridge IELTS 18 (C1-C2)
        $doc2 = Document::updateOrCreate(
            ['name' => 'Cambridge IELTS 18 Academic'],
            [
                'description' => 'Tài liệu luyện thi IELTS chính thức từ Đại học Cambridge với các bài thi thật.',
                'skill' => 'listening',
                'level' => 'C1',
                'proficiency' => 'Advanced',
                'author' => 'Cambridge University Press',
                'publisher' => 'Cambridge University Press',
                'published_year' => 2023,
                'version' => '1st Edition',
                'isbn' => '978-1009311847',
                'language' => 'en',
                'cover_image' => null,
                'page_count' => 140,
                'dimensions' => '21x29.7 cm',
                'file_size' => '85 MB',
                'file_format' => 'PDF',
                'total_lessons' => 16,
                'status' => 'active',
                'created_by' => $userId,
                'overview' => 'Bộ sách luyện đề chứa 4 đề thi thử đầy đủ cả 4 kỹ năng nghe, nói, đọc, viết theo đúng format bài thi IELTS thật.',
                'chapter_list' => ['Test 1', 'Test 2', 'Test 3', 'Test 4'],
                'chapter_contents' => [
                    'Test 1' => 'Luyện đề thi thử IELTS số 1 với 4 phần nghe khác nhau.',
                    'Test 2' => 'Luyện đề thi thử IELTS số 2 với 4 phần nghe khác nhau.'
                ],
                'chapter_volumes' => [
                    'Test 1' => 'Gồm 4 Sections / Parts.',
                    'Test 2' => 'Gồm 4 Sections / Parts.'
                ],
                'learning_objective' => 'Đạt mức điểm 7.0+ phần nghe tương ứng với C1.',
                'study_duration' => 40,
                'review_duration' => 20,
                'assessment' => 'Làm bài trắc nghiệm và chép lại chính tả các đoạn hội thoại độc thoại học thuật.',
                'completion_criteria' => ['min_lessons' => 4, 'min_score' => 75],
                'journey_status' => 'not_started',
            ]
        );

        // Create Chapters & Units for Doc 1
        $part1 = DocumentPart::updateOrCreate(
            ['document_id' => $doc1->id, 'name' => 'Chương 1: Giới thiệu bản thân & Cuộc sống hàng ngày'],
            ['order' => 1, 'description' => 'Mô tả bản thân, gia đình, bạn bè và sinh hoạt thường nhật.']
        );

        $unit1 = DocumentUnit::updateOrCreate(
            ['part_id' => $part1->id, 'name' => 'Unit 1: Introductions and Names'],
            ['order' => 1, 'description' => 'Luyện nghe cách phát âm tên, đánh vần chữ cái và thông tin chào hỏi đầu tiên.']
        );

        $unit2 = DocumentUnit::updateOrCreate(
            ['part_id' => $part1->id, 'name' => 'Unit 2: Describing People'],
            ['order' => 2, 'description' => 'Luyện nghe mô tả ngoại hình, chiều cao, màu tóc, độ tuổi.']
        );

        // Create Chapters & Units for Doc 2
        $part2 = DocumentPart::updateOrCreate(
            ['document_id' => $doc2->id, 'name' => 'Test 1'],
            ['order' => 1, 'description' => 'Đề thi Listening Test 1.']
        );

        $unit2_1 = DocumentUnit::updateOrCreate(
            ['part_id' => $part2->id, 'name' => 'Section 1: Transport Inquiry'],
            ['order' => 1, 'description' => 'Hội thoại hỏi thông tin chi tiết về các loại hình giao thông công cộng.']
        );

        // 3. Create Listening Files
        // Listening File 1
        $l1 = Listening::updateOrCreate(
            ['title' => 'Introductions and Greetings', 'document_id' => $doc1->id],
            [
                'link' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
                'type' => 'mp3',
                'size' => '1.5 MB',
                'duration' => 372, // Chiều dài bài test mp3
                'level' => 'A1',
                'score_level' => 10.0,
                'chapter_id' => $part1->id,
                'unit_id' => $unit1->id,
                'topic_id' => $topicIds['Giao tiếp hàng ngày'],
            ]
        );

        ListeningTranscript::updateOrCreate(
            ['listening_id' => $l1->id, 'type' => 'original'],
            [
                'language_code' => 'en',
                'transcript' => "Hello, my name is John. Nice to meet you. Hi John, I'm Sarah. Are you a new student here? Yes, I am. I just arrived yesterday.",
                'sentence_count' => 6,
                'word_count' => 27
            ]
        );

        // Seed sentences
        $l1->sentences()->delete();
        $sampleSentences = [
            ['text' => 'Hello, my name is John.', 'start' => 0.00, 'end' => 3.50],
            ['text' => 'Nice to meet you.', 'start' => 3.50, 'end' => 6.20],
            ['text' => 'Hi John, I\'m Sarah.', 'start' => 6.20, 'end' => 9.80],
            ['text' => 'Are you a new student here?', 'start' => 9.80, 'end' => 14.10],
            ['text' => 'Yes, I am.', 'start' => 14.10, 'end' => 17.00],
            ['text' => 'I just arrived yesterday.', 'start' => 17.00, 'end' => 22.50],
        ];
        foreach ($sampleSentences as $idx => $s) {
            $l1->sentences()->create([
                'sentence_text' => $s['text'],
                'start_time'    => $s['start'],
                'end_time'      => $s['end'],
                'order'         => $idx + 1,
            ]);
        }

        // Listening File 2
        $l2 = Listening::updateOrCreate(
            ['title' => 'Describing my Friend', 'document_id' => $doc1->id],
            [
                'link' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
                'type' => 'mp3',
                'size' => '2.1 MB',
                'duration' => 425,
                'level' => 'A2',
                'score_level' => 10.0,
                'chapter_id' => $part1->id,
                'unit_id' => $unit2->id,
                'topic_id' => $topicIds['Giao tiếp hàng ngày'],
            ]
        );

        ListeningTranscript::updateOrCreate(
            ['listening_id' => $l2->id, 'type' => 'original'],
            [
                'language_code' => 'en',
                'transcript' => "My best friend is tall and has short curly hair. He wears glasses and usually wears blue jeans with a white t-shirt. He is very funny.",
                'sentence_count' => 3,
                'word_count' => 28
            ]
        );

        // Listening File 3
        $l3 = Listening::updateOrCreate(
            ['title' => 'Booking a Bus Ticket', 'document_id' => $doc2->id],
            [
                'link' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
                'type' => 'mp3',
                'size' => '3.5 MB',
                'duration' => 344,
                'level' => 'C1',
                'score_level' => 10.0,
                'chapter_id' => $part2->id,
                'unit_id' => $unit2_1->id,
                'topic_id' => $topicIds['Du lịch & Khám phá'],
            ]
        );

        ListeningTranscript::updateOrCreate(
            ['listening_id' => $l3->id, 'type' => 'original'],
            [
                'language_code' => 'en',
                'transcript' => "Good morning. Local Transport Office. How can I help you? Yes, I'm looking for information on bus tickets from central station to the university. Well, we have single tickets, day passes, and monthly passes. The single ticket is three dollars and fifty cents, while a day pass is seven dollars.",
                'sentence_count' => 6,
                'word_count' => 54
            ]
        );
    }
}
