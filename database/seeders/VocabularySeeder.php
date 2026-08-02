<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vocabulary\VocabWord;
use App\Models\Vocabulary\VocabStage;
use App\Models\Vocabulary\VocabProperty;
use App\Models\Vocabulary\VocabStudyLog;
use App\Models\Vocabulary\VocabReview;
use App\Models\User;

class VocabularySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables to make the seeder idempotent and fresh by disabling constraints temporarily
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        VocabReview::truncate();
        VocabStudyLog::truncate();
        VocabProperty::truncate();
        VocabStage::truncate();
        VocabWord::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Ensure default student user exists
        $user = User::where('email', 'hocvien@quanlytienganh.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Học Viên Anh Văn',
                'email' => 'hocvien@quanlytienganh.com',
                'password' => bcrypt('123456'),
                'role' => 'student',
            ]);
        }

        // Ensure default admin user exists
        $admin = User::where('email', 'admin@quanlytienganh.com')->first();
        if (!$admin) {
            User::create([
                'name' => 'Quản Trị Viên',
                'email' => 'admin@quanlytienganh.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ]);
        }

        // 1. Create Vocabulary Growth Stages
        $stage1 = VocabStage::create([
            'name' => 'Giai đoạn 1',
            'description' => 'Nền tảng',
            'order' => 1
        ]);

        $stage2 = VocabStage::create([
            'name' => 'Giai đoạn 2',
            'description' => 'Phát triển',
            'order' => 2
        ]);

        $stage3 = VocabStage::create([
            'name' => 'Giai đoạn 3',
            'description' => 'Nâng cao',
            'order' => 3
        ]);

        // 2. Create Base Words and their progressive lexical properties
        
        // --- Word 1: Run ---
        $run = VocabWord::create([
            'word' => 'Run',
            'uk_phonetic' => '/rʌn/',
            'us_phonetic' => '/rʌn/'
        ]);

        // Run - Stage 1 (Verb -> Chạy)
        VocabProperty::create([
            'word_id' => $run->id,
            'stage_id' => $stage1->id,
            'part_of_speech' => 'Verb',
            'meaning' => 'Chạy',
            'example_sentence' => 'He runs every morning.',
            'example_translation' => 'Anh ấy chạy bộ mỗi sáng.',
            'topic' => 'Hoạt động',
            'reference' => 'TACB'
        ]);

        // Run - Stage 2 (Noun -> Cuộc chạy)
        VocabProperty::create([
            'word_id' => $run->id,
            'stage_id' => $stage2->id,
            'part_of_speech' => 'Noun',
            'meaning' => 'Cuộc chạy / Quãng đường chạy',
            'example_sentence' => 'I went for a run.',
            'example_translation' => 'Tôi đã đi chạy một vòng.',
            'topic' => 'Thể thao',
            'reference' => 'Oxford 3000'
        ]);

        // Run - Stage 3 (Verb -> Điều hành)
        VocabProperty::create([
            'word_id' => $run->id,
            'stage_id' => $stage3->id,
            'part_of_speech' => 'Verb',
            'meaning' => 'Điều hành / Quản lý',
            'example_sentence' => 'She runs a company.',
            'example_translation' => 'Cô ấy điều hành một công ty.',
            'topic' => 'Kinh doanh',
            'reference' => 'Oxford 3000'
        ]);

        // --- Word 2: Book ---
        $book = VocabWord::create([
            'word' => 'Book',
            'uk_phonetic' => '/bʊk/',
            'us_phonetic' => '/bʊk/'
        ]);

        // Book - Stage 1 (Noun -> Quyển sách)
        VocabProperty::create([
            'word_id' => $book->id,
            'stage_id' => $stage1->id,
            'part_of_speech' => 'Noun',
            'meaning' => 'Quyển sách',
            'example_sentence' => 'I am reading a new book.',
            'example_translation' => 'Tôi đang đọc một cuốn sách mới.',
            'topic' => 'Học tập',
            'reference' => 'Oxford 3000'
        ]);

        // Book - Stage 2 (Verb -> Đặt vé / Đặt phòng)
        VocabProperty::create([
            'word_id' => $book->id,
            'stage_id' => $stage2->id,
            'part_of_speech' => 'Verb',
            'meaning' => 'Đặt trước (vé, phòng khách sạn, bàn ăn...)',
            'example_sentence' => 'We booked a hotel room last night.',
            'example_translation' => 'Chúng tôi đã đặt một phòng khách sạn tối qua.',
            'topic' => 'Du lịch',
            'reference' => 'TACB'
        ]);

        // Book - Stage 3 (Noun -> Sổ sách kế toán / Hồ sơ)
        VocabProperty::create([
            'word_id' => $book->id,
            'stage_id' => $stage3->id,
            'part_of_speech' => 'Noun',
            'meaning' => 'Sổ sách ghi chép tài chính / Sổ cái',
            'example_sentence' => 'The accountant is checking the company\'s books.',
            'example_translation' => 'Kế toán đang kiểm tra sổ sách tài chính của công ty.',
            'topic' => 'Kinh doanh',
            'reference' => 'Cambridge Dictionary'
        ]);

        // --- Word 3: Family ---
        $family = VocabWord::create([
            'word' => 'Family',
            'uk_phonetic' => '/ˈfæm.əl.i/',
            'us_phonetic' => '/ˈfæm.ə.li/'
        ]);

        // Family - Stage 1 (Noun -> Gia đình)
        VocabProperty::create([
            'word_id' => $family->id,
            'stage_id' => $stage1->id,
            'part_of_speech' => 'Noun',
            'meaning' => 'Gia đình',
            'example_sentence' => 'I love my family.',
            'example_translation' => 'Tôi yêu gia đình của tôi.',
            'topic' => 'Gia đình',
            'reference' => 'Oxford 3000'
        ]);

        // Family - Stage 2 (Adjective -> Thuộc về gia đình)
        VocabProperty::create([
            'word_id' => $family->id,
            'stage_id' => $stage2->id,
            'part_of_speech' => 'Adjective',
            'meaning' => 'Dành cho gia đình / Thích hợp cho gia đình',
            'example_sentence' => 'We watched a family movie.',
            'example_translation' => 'Chúng tôi đã xem một bộ phim gia đình.',
            'topic' => 'Giải trí',
            'reference' => 'Oxford 3000'
        ]);

        // Family - Stage 3 (Noun -> Gia tộc / Họ nhà sinh vật)
        VocabProperty::create([
            'word_id' => $family->id,
            'stage_id' => $stage3->id,
            'part_of_speech' => 'Noun',
            'meaning' => 'Họ sinh học / Gia tộc lớn',
            'example_sentence' => 'Lions belong to the cat family.',
            'example_translation' => 'Sư tử thuộc họ nhà mèo.',
            'topic' => 'Khoa học',
            'reference' => 'Cambridge Dictionary'
        ]);

        // 3. Create Student Study Logs (Learning Tracker)
        VocabStudyLog::create([
            'user_id' => $user->id,
            'word_id' => $run->id,
            'sessions_count' => 5,
            'study_time_minutes' => 30,
            'review_time_minutes' => 20
        ]);

        VocabStudyLog::create([
            'user_id' => $user->id,
            'word_id' => $book->id,
            'sessions_count' => 3,
            'study_time_minutes' => 15,
            'review_time_minutes' => 10
        ]);

        VocabStudyLog::create([
            'user_id' => $user->id,
            'word_id' => $family->id,
            'sessions_count' => 4,
            'study_time_minutes' => 25,
            'review_time_minutes' => 15
        ]);

        // 4. Create Student Recall Quiz Reviews History
        VocabReview::create([
            'user_id' => $user->id,
            'word_id' => $run->id,
            'score' => 90,
            'duration_seconds' => 75,
            'passed' => true,
            'answers' => [
                'spelling' => [
                    'student' => 'run',
                    'actual' => 'run',
                    'is_correct' => true
                ],
                'meanings' => [
                    [
                        'stage' => 'Giai đoạn 1',
                        'pos' => 'Verb',
                        'student' => 'Chạy',
                        'actual' => 'Chạy',
                        'is_correct' => true
                    ],
                    [
                        'stage' => 'Giai đoạn 2',
                        'pos' => 'Noun',
                        'student' => 'Cuộc chạy bộ',
                        'actual' => 'Cuộc chạy / Quãng đường chạy',
                        'is_correct' => true
                    ],
                    [
                        'stage' => 'Giai đoạn 3',
                        'pos' => 'Verb',
                        'student' => 'Điều hành',
                        'actual' => 'Điều hành / Quản lý',
                        'is_correct' => true
                    ]
                ],
                'examples' => [
                    [
                        'vietnamese' => 'Cô ấy điều hành một công ty.',
                        'student' => 'She runs a company',
                        'actual' => 'She runs a company.',
                        'is_correct' => true
                    ]
                ]
            ]
        ]);

        VocabReview::create([
            'user_id' => $user->id,
            'word_id' => $run->id,
            'score' => 60,
            'duration_seconds' => 110,
            'passed' => false,
            'answers' => [
                'spelling' => [
                    'student' => 'ran',
                    'actual' => 'run',
                    'is_correct' => false
                ],
                'meanings' => [
                    [
                        'stage' => 'Giai đoạn 1',
                        'pos' => 'Verb',
                        'student' => 'Chạy',
                        'actual' => 'Chạy',
                        'is_correct' => true
                    ],
                    [
                        'stage' => 'Giai đoạn 2',
                        'pos' => 'Noun',
                        'student' => 'Không biết',
                        'actual' => 'Cuộc chạy / Quãng đường chạy',
                        'is_correct' => false
                    ],
                    [
                        'stage' => 'Giai đoạn 3',
                        'pos' => 'Verb',
                        'student' => 'Điều khiển',
                        'actual' => 'Điều hành / Quản lý',
                        'is_correct' => true
                    ]
                ],
                'examples' => [
                    [
                        'vietnamese' => 'Cô ấy điều hành một công ty.',
                        'student' => 'She run a company',
                        'actual' => 'She runs a company.',
                        'is_correct' => false
                    ]
                ]
            ]
        ]);
    }
}
