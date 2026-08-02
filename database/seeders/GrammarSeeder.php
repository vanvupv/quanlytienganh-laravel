<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grammar\GrammarCategory;
use App\Models\Grammar\GrammarTopic;
use App\Models\Grammar\GrammarLesson;

class GrammarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean truncation
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GrammarLesson::truncate();
        GrammarTopic::truncate();
        GrammarCategory::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Categories (Giai đoạn)
        $giaiDoan1 = GrammarCategory::create([
            'name' => 'Giai đoạn 1 (Nền tảng)',
            'description' => 'Trang bị các kiến thức ngữ pháp cơ bản nhất bao gồm các thì cơ bản, các cấu trúc so sánh và câu trực tiếp - gián tiếp.',
            'order' => 1
        ]);

        $giaiDoan2 = GrammarCategory::create([
            'name' => 'Giai đoạn 2 (Phát triển)',
            'description' => 'Mở rộng kiến thức với các dạng câu đặc biệt, câu bị động và các cấu trúc câu điều kiện, điều ước.',
            'order' => 2
        ]);

        $giaiDoan3 = GrammarCategory::create([
            'name' => 'Giai đoạn 3 (Nâng cao)',
            'description' => 'Làm chủ các mệnh đề quan hệ phức tạp và các liên từ nâng cao.',
            'order' => 3
        ]);

        // 2. Create Topics (Lộ trình)
        $topic12Thi = GrammarTopic::create([
            'category_id' => $giaiDoan1->id,
            'title' => '12 Thì cơ bản trong Tiếng Anh',
            'description' => 'Tìm hiểu chi tiết công thức, cách dùng, dấu hiệu nhận biết và ví dụ thực tế của 12 thì cơ bản.',
            'order' => 1
        ]);

        $topicSoSanh = GrammarTopic::create([
            'category_id' => $giaiDoan1->id,
            'title' => 'Cấu trúc so sánh',
            'description' => 'So sánh hơn, so sánh nhất, so sánh bằng và các cấu trúc So - Such, Too - Enough.',
            'order' => 2
        ]);

        $topicCauTrucTiepGianTiep = GrammarTopic::create([
            'category_id' => $giaiDoan1->id,
            'title' => 'Câu trực tiếp - gián tiếp',
            'description' => 'Tìm hiểu cách chuyển đổi từ câu trực tiếp sang câu gián tiếp, quy tắc lùi thì, đổi ngôi và biến đổi trạng từ.',
            'order' => 3
        ]);

        $topicCauDacBiet = GrammarTopic::create([
            'category_id' => $giaiDoan2->id,
            'title' => 'Câu đặc biệt & Điều kiện',
            'description' => 'Câu điều kiện loại 1, 2, 3, câu điều ước (Wish) và các cấu trúc giả định.',
            'order' => 1
        ]);

        $topicBiDong = GrammarTopic::create([
            'category_id' => $giaiDoan2->id,
            'title' => 'Thể bị động',
            'description' => 'Cách chuyển đổi từ câu chủ động sang câu bị động theo quy tắc và các dạng đặc biệt.',
            'order' => 2
        ]);

        $topicCauPhuc = GrammarTopic::create([
            'category_id' => $giaiDoan3->id,
            'title' => 'Câu phức & Mệnh đề',
            'description' => 'Mệnh đề quan hệ (Who, Whom, Which, Whose, Where, When, That).',
            'order' => 1
        ]);

        $topicLienTu = GrammarTopic::create([
            'category_id' => $giaiDoan3->id,
            'title' => 'Liên từ nâng cao',
            'description' => 'Các liên từ chỉ sự nhượng bộ (Although, Even though, Despite, In spite of) và các cấu trúc nối câu phức tạp.',
            'order' => 2
        ]);

        // 3. Create Lessons for "12 Thì cơ bản trong Tiếng Anh"
        // Lesson 1: Thì hiện tại đơn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Hiện Tại Đơn (Simple Present Tense)',
            'order' => 1,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + V(s/es) / S + am/is/are + O',
                    'negative' => 'S + do/does + not + V / S + am/is/are + not + O',
                    'question' => 'Do/Does + S + V? / Am/Is/Are + S + O?'
                ],
                'usage' => [
                    'Diễn tả một thói quen hoặc hành động lặp đi lặp lại ở hiện tại.',
                    'Diễn tả một sự thật hiển nhiên, một chân lý khoa học.',
                    'Diễn tả một lịch trình, thời gian biểu cố định (tàu xe, máy bay...).'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'He plays football every Sunday.', 'note' => 'Minh họa cho hành động lặp đi lặp lại.'],
                ['sentence' => 'Water boils at 100 degrees Celsius.', 'note' => 'Minh họa cho sự thật hiển nhiên.'],
                ['sentence' => 'The train leaves at 8:00 AM tomorrow.', 'note' => 'Minh họa cho lịch trình cố định.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 2: Thì hiện tại tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Hiện Tại Tiếp Diễn (Present Continuous Tense)',
            'order' => 2,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + am/is/are + V-ing',
                    'negative' => 'S + am/is/are + not + V-ing',
                    'question' => 'Am/Is/Are + S + V-ing?'
                ],
                'usage' => [
                    'Diễn tả hành động đang diễn ra tại thời điểm nói.',
                    'Diễn tả hành động sắp xảy ra theo một kế hoạch đã lên lịch sẵn.',
                    'Diễn tả sự ca thán, phàn nàn về một thói quen xấu (thường đi với "always").'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'She is watching television right now.', 'note' => 'Hành động đang diễn ra ngay lúc nói.'],
                ['sentence' => 'We are flying to Paris tonight.', 'note' => 'Kế hoạch đã định sẵn.'],
                ['sentence' => 'He is always losing his keys!', 'note' => 'Phàn nàn về thói quen xấu.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 3: Thì hiện tại hoàn thành
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Hiện Tại Hoàn Thành (Present Perfect Tense)',
            'order' => 3,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + have/has + V3/ed',
                    'negative' => 'S + have/has + not + V3/ed',
                    'question' => 'Have/Has + S + V3/ed?'
                ],
                'usage' => [
                    'Diễn tả hành động đã xảy ra trong quá khứ và vẫn còn kéo dài hoặc có ảnh hưởng ở hiện tại.',
                    'Diễn tả trải nghiệm, kinh nghiệm sống (tính đến thời điểm hiện tại).',
                    'Diễn tả hành động vừa mới xảy ra (thường đi kèm "just", "already", "recently").'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'I have lived in Hanoi for five years.', 'note' => 'Bắt đầu trong quá khứ và vẫn tiếp tục.'],
                ['sentence' => 'Have you ever eaten sushi?', 'note' => 'Hỏi về trải nghiệm trong đời.'],
                ['sentence' => 'They have just finished their project.', 'note' => 'Hành động vừa mới hoàn thành.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 4: Hiện tại hoàn thành tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Hiện Tại Hoàn Thành Tiếp Diễn (Present Perfect Continuous)',
            'order' => 4,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + have/has + been + V-ing',
                    'negative' => 'S + have/has + not + been + V-ing',
                    'question' => 'Have/Has + S + been + V-ing?'
                ],
                'usage' => [
                    'Nhấn mạnh tính liên tục của hành động bắt đầu trong quá khứ kéo dài đến hiện tại.',
                    'Diễn tả hành động vừa mới chấm dứt nhưng kết quả của nó vẫn còn rõ rệt ở hiện tại.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'It has been raining all morning.', 'note' => 'Nhấn mạnh mưa liên tục suốt buổi sáng.'],
                ['sentence' => 'I am tired because I have been working out.', 'note' => 'Vừa chấm dứt nhưng kết quả mệt mỏi vẫn còn.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 5: Quá khứ đơn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Quá Khứ Đơn (Simple Past Tense)',
            'order' => 5,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + V2/ed / S + was/were + O',
                    'negative' => 'S + did + not + V / S + was/were + not + O',
                    'question' => 'Did + S + V? / Was/Were + S + O?'
                ],
                'usage' => [
                    'Diễn tả hành động đã xảy ra và chấm dứt hoàn toàn trong quá khứ, biết rõ thời gian.',
                    'Diễn tả một chuỗi các hành động xảy ra liên tiếp trong quá khứ.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'We visited the museum yesterday.', 'note' => 'Hành động kết thúc trong quá khứ.'],
                ['sentence' => 'He opened the door, turned on the light and sat down.', 'note' => 'Chuỗi hành động liên tiếp.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 6: Quá khứ tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Quá Khứ Tiếp Diễn (Past Continuous Tense)',
            'order' => 6,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + was/were + V-ing',
                    'negative' => 'S + was/were + not + V-ing',
                    'question' => 'Was/Were + S + V-ing?'
                ],
                'usage' => [
                    'Diễn tả hành động đang diễn ra tại một thời điểm xác định trong quá khứ.',
                    'Diễn tả hành động đang xảy ra thì có một hành động khác xen vào (hành động xen vào dùng Quá khứ đơn).'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'I was doing homework at 8 PM last night.', 'note' => 'Đang diễn ra tại mốc giờ cụ thể.'],
                ['sentence' => 'She was cooking when the phone rang.', 'note' => 'Đang nấu ăn thì điện thoại reo (xen vào).']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 7: Quá khứ hoàn thành
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Quá Khứ Hoàn Thành (Past Perfect Tense)',
            'order' => 7,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + had + V3/ed',
                    'negative' => 'S + had + not + V3/ed',
                    'question' => 'Had + S + V3/ed?'
                ],
                'usage' => [
                    'Diễn tả một hành động xảy ra trước một hành động khác hoặc trước một thời điểm khác trong quá khứ.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'The train had left when I arrived at the station.', 'note' => 'Tàu chạy trước khi tôi tới ga.'],
                ['sentence' => 'We had lived there before moving here in 2020.', 'note' => 'Sống ở đó trước mốc thời gian 2020.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 8: Quá khứ hoàn thành tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Quá Khứ Hoàn Thành Tiếp Diễn (Past Perfect Continuous)',
            'order' => 8,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + had + been + V-ing',
                    'negative' => 'S + had + not + been + V-ing',
                    'question' => 'Had + S + been + V-ing?'
                ],
                'usage' => [
                    'Nhấn mạnh tính liên tục của một hành động xảy ra trước một hành động khác trong quá khứ.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'He had been running for an hour before he collapsed.', 'note' => 'Nhấn mạnh việc chạy liên tục 1 tiếng trước khi ngã.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 9: Tương lai đơn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Tương Lai Đơn (Simple Future Tense)',
            'order' => 9,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + will + V-inf',
                    'negative' => 'S + will + not + V-inf',
                    'question' => 'Will + S + V-inf?'
                ],
                'usage' => [
                    'Diễn tả quyết định đưa ra ngay tại thời điểm nói.',
                    'Diễn tả một dự đoán chủ quan không có căn cứ thực tế rõ ràng.',
                    'Dùng trong lời hứa, lời đe dọa hoặc lời đề nghị.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'It is cold. I will close the window.', 'note' => 'Quyết định tức thời ngay khi nói.'],
                ['sentence' => 'I think she will pass the exam.', 'note' => 'Dự đoán chủ quan.'],
                ['sentence' => 'I will help you with your homework.', 'note' => 'Lời hứa giúp đỡ.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 10: Tương lai tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Tương Lai Tiếp Diễn (Future Continuous Tense)',
            'order' => 10,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + will + be + V-ing',
                    'negative' => 'S + will + not + be + V-ing',
                    'question' => 'Will + S + be + V-ing?'
                ],
                'usage' => [
                    'Diễn tả hành động đang diễn ra tại một thời điểm xác định trong tương lai.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'At this time tomorrow, I will be sitting on the plane.', 'note' => 'Đang diễn ra tại đúng giờ này ngày mai.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 11: Tương lai hoàn thành
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Tương Lai Hoàn Thành (Future Perfect Tense)',
            'order' => 11,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + will + have + V3/ed',
                    'negative' => 'S + will + not + have + V3/ed',
                    'question' => 'Will + S + have + V3/ed?'
                ],
                'usage' => [
                    'Diễn tả hành động sẽ được hoàn thành trước một thời điểm hoặc một hành động khác trong tương lai.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'I will have finished my homework by 10 PM.', 'note' => 'Xong bài tập trước mốc 10 giờ tối.'],
                ['sentence' => 'She will have graduated by next summer.', 'note' => 'Tốt nghiệp trước mùa hè tới.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson 12: Tương lai hoàn thành tiếp diễn
        GrammarLesson::create([
            'topic_id' => $topic12Thi->id,
            'title' => 'Thì Tương Lai Hoàn Thành Tiếp Diễn (Future Perfect Continuous)',
            'order' => 12,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'S + will + have + been + V-ing',
                    'negative' => 'S + will + not + have + been + V-ing',
                    'question' => 'Will + S + have + been + V-ing?'
                ],
                'usage' => [
                    'Nhấn mạnh tính liên tục của một hành động xảy ra trước một mốc thời gian khác trong tương lai.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'By next month, I will have been working here for three years.', 'note' => 'Nhấn mạnh tính liên tục suốt 3 năm tính đến tháng sau.']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson: Câu trực tiếp - gián tiếp
        GrammarLesson::create([
            'topic_id' => $topicCauTrucTiepGianTiep->id,
            'title' => 'Câu Trực Tiếp - Gián Tiếp (Reported Speech)',
            'order' => 1,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'Direct: S + said, "S + V" -> Indirect: S + said (that) + S + V (lùi thì)',
                    'negative' => 'Direct: S + said, "S + helper + not + V" -> Indirect: S + said (that) + S + helper (lùi thì) + not + V',
                    'question' => 'Yes/No: S + asked + if/whether + S + V (lùi thì) | Wh-question: S + asked + Wh-word + S + V (lùi thì)'
                ],
                'usage' => [
                    'Quy tắc 1: Thay đổi thì của động từ (lùi thì) khi động từ dẫn ở quá khứ.',
                    'Quy tắc 2: Thay đổi đại từ nhân xưng, đại từ sở hữu và tính từ sở hữu phù hợp ngữ cảnh.',
                    'Quy tắc 3: Thay đổi trạng từ chỉ thời gian và nơi chốn (here -> there, now -> then, today -> that day, tomorrow -> the next day).'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'He said, "I am tired." -> He said that he was tired.', 'note' => 'Hiện tại đơn (am) lùi thành Quá khứ đơn (was), đại từ "I" đổi thành "he".'],
                ['sentence' => 'She said, "I will call you tomorrow." -> She said she would call me the next day.', 'note' => '"will" lùi thành "would", "tomorrow" đổi thành "the next day".'],
                ['sentence' => 'They asked, "Do you live here?" -> They asked if I lived there.', 'note' => 'Câu hỏi Yes/No, thêm "if", "live" lùi thành "lived", "here" đổi thành "there".']
            ], JSON_UNESCAPED_UNICODE)
        ]);

        // Lesson: Cấu trúc so sánh
        GrammarLesson::create([
            'topic_id' => $topicSoSanh->id,
            'title' => 'Cấu trúc so sánh (Comparison)',
            'order' => 1,
            'content' => json_encode([
                'formula' => [
                    'affirmative' => 'Bằng: S + V + as + adj/adv + as + N/Pron | Hơn (ngắn): S + V + adj-er/adv-er + than + N/Pron | Hơn (dài): S + V + more + adj/adv + than + N/Pron',
                    'negative' => 'Bằng phủ định: S + V + not + so/as + adj/adv + as + N/Pron',
                    'question' => 'Nhất (ngắn): S + V + the + adj-est/adv-est + N/Pron | Nhất (dài): S + V + the + most + adj/adv + N/Pron'
                ],
                'usage' => [
                    'So sánh bằng: diễn tả hai đối tượng có tính chất, đặc điểm tương đương nhau.',
                    'So sánh hơn: so sánh giữa hai đối tượng, chỉ ra một đối tượng có tính chất vượt trội hơn.',
                    'So sánh nhất: so sánh từ ba đối tượng trở lên, chỉ ra đối tượng có tính chất nổi bật nhất nhóm.'
                ]
            ], JSON_UNESCAPED_UNICODE),
            'example' => json_encode([
                ['sentence' => 'He is as tall as his father.', 'note' => 'Ví dụ so sánh bằng sử dụng tính từ ngắn "tall".'],
                ['sentence' => 'This book is more interesting than that one.', 'note' => 'Ví dụ so sánh hơn sử dụng tính từ dài "interesting".'],
                ['sentence' => 'She is the smartest student in my class.', 'note' => 'Ví dụ so sánh nhất sử dụng tính từ ngắn "smart".']
            ], JSON_UNESCAPED_UNICODE)
        ]);
    }
}
