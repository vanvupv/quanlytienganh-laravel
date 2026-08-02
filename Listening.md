# Luồng xử lý — Chức năng Listening

> Tài liệu này mô tả đầy đủ kiến trúc, luồng dữ liệu, cơ sở dữ liệu và logic nghiệp vụ của chức năng Listening trong hệ thống Quản lý Tiếng Anh.

---

## 1. Tổng quan kiến trúc

```
┌─────────────────────────────────────────────────────────────────┐
│                     CHỨC NĂNG LISTENING                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  [Tài liệu]                                                       │
│    Document (6 nhóm thông tin + hành trình học)                   │
│      └─ DocumentPart (Chương)                                     │
│           └─ DocumentUnit (Bài)                                   │
│                └─ Listening (File nghe)                           │
│                     ├─ ListeningTranscript (Transcript gốc/dịch)  │
│                     ├─ ListeningSession (Lần nghe)                │
│                     ├─ ListeningAssessment (Kết quả đánh giá)     │
│                     └─ ListeningWeeklyStat (Thống kê tuần)        │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Cơ sở dữ liệu

### 2.1. Bảng `documents` — Tài liệu chính

| Nhóm | Trường |
|---|---|
| **① Cơ bản** | `name`, `description`, `skill`, `level`, `proficiency` |
| **② Xuất bản** | `author`, `publisher`, `published_year`, `version`, `isbn`, `language` |
| **③ File & Vật lý** | `cover_image`, `page_count`, `dimensions`, `file_size`, `file_format`, `total_lessons` |
| **④ Trạng thái** | `status`, `created_by`, `created_at`, `updated_at` |
| **⑤ Cấu trúc** | `overview` (TEXT), `chapter_list` (JSON), `chapter_contents` (JSON), `chapter_volumes` (JSON) |
| **⑥ Hành trình** | `learning_objective`, `study_duration`, `review_duration`, `assessment`, `completion_criteria` (JSON), `journey_status`, `is_passed` |

### 2.2. Bảng `document_parts` — Chương
```
id, document_id, name, order, description
```

### 2.3. Bảng `document_units` — Bài trong chương
```
id, part_id, name, order, description
```

### 2.4. Bảng `listening_topics` — Chủ đề
```
id, name, slug, description
```

### 2.5. Bảng `listening` — File nghe

| Nhóm | Trường |
|---|---|
| **Cơ bản** | `title`, `link`, `type`, `size`, `duration` |
| **Transcript** | Lưu trong bảng riêng `listening_transcripts` |
| **Chủ đề** | `topic_id` (FK → `listening_topics`) |
| **Cấp độ** | `level` |
| **Mức điểm** | `score_level` |
| **Tài liệu** | `document_id`, `chapter_id`, `unit_id` |
| **Số lần nghe** | `listen_count` |

### 2.6. Bảng `listening_transcripts` — Transcript
```
id, listening_id, type (original/translation/subtitle), language_code, transcript, sentence_count, word_count
```

### 2.7. Bảng `listening_sessions` — Phiên nghe
```
id, listening_id, user_id, attempt_number, duration_seconds, 
is_completed, is_passed, efficiency_score, listened_at
```

### 2.8. Bảng `listening_assessments` — Đánh giá viết lại Transcript
```
id, listening_id, user_id, session_id, attempt_number,
submitted_transcript, original_transcript,
score_percent, correct_words, total_words,
duration_spent, is_passed, efficiency_score, assessed_at
```

### 2.9. Bảng `listening_weekly_stats` — Thống kê tuần
```
id, listening_id, user_id, week_start (date), year, week_number,
listen_count, pass_count, total_duration, avg_score, best_efficiency
```

---

## 3. Luồng xử lý Luyện nghe & Đánh giá

```
Bước 1: User chọn file nghe từ danh sách
         GET /listening/{id}  → practice.blade.php

Bước 2: User nhấn "Bắt đầu nghe"
         POST /listening/{id}/session/start
         → Tạo ListeningSession (attempt_number = listen_count + 1)
         → Tăng listening.listen_count += 1
         → Trả về session_id

Bước 3: User nghe audio (trình duyệt phát file)
         Timer bắt đầu đếm (JavaScript)

Bước 4: User viết lại Transcript vào textarea
         (Tùy chọn: Xem/Ẩn transcript gốc)

Bước 5: User nhấn "Nộp & Chấm điểm"
         POST /listening/{id}/assessment/submit
         Body: { submitted_transcript, session_id, duration_spent }

Bước 6: Server tính điểm (ListeningAssessment::calculateScore)
         - Chuẩn hoá văn bản (lowercase, bỏ dấu câu)
         - Đếm số từ khớp (multiset matching)
         - score_percent = (correct_words / total_words) * 100
         - is_passed = score_percent >= 80%

Bước 7: Tính hiệu suất
         - attempt_number = số lần đã nộp + 1
         - efficiency = max(10, 100 / attempt_number)
         
Bước 8: Lưu kết quả
         - Lưu ListeningAssessment (kết quả chấm điểm)
         - Cập nhật ListeningSession (is_passed, efficiency_score)
         - Gọi ListeningWeeklyStat::recordSession() → cập nhật thống kê tuần

Bước 9: Trả về kết quả cho client
         { score_percent, correct_words, total_words, is_passed, efficiency, attempt_number }
         → Hiển thị score circle animation trên giao diện
```

---

## 4. Công thức Hiệu suất

### Nguyên tắc:
> **Nghe 1 lần và hiểu được hết Transcript = 100%**
> Hiệu suất giảm dần theo số lần nghe cần thiết.

### Công thức:
```
efficiency = max(10, round(100 / attempt_number, 2))
```

| Lần nghe | Hiệu suất |
|---|---|
| 1 | 100% |
| 2 | 50% |
| 3 | 33.33% |
| 4 | 25% |
| 5 | 20% |
| 10+ | 10% (tối thiểu) |

### Lưu ý:
- `attempt_number` = số lần đã **nộp transcript** (không phải số lần nhấn play)
- Mỗi lần nhấn "Bắt đầu nghe" = tạo 1 session mới
- Mỗi lần nhấn "Nộp" = tạo 1 assessment mới
- Hiệu suất cuối cùng = dựa trên số lần đã nộp

---

## 5. Cấu trúc Giao diện Chi tiết Tài liệu (Document Detail Page)

Trang chi tiết tài liệu được thiết kế lại tối giản theo phong cách "Mục lục sách" (Book Table of Contents), tập trung trả lời 3 câu hỏi chính của người học:
1. Tài liệu này có bao nhiêu giai đoạn (Chương)?
2. Mỗi giai đoạn có bao nhiêu Unit, bao nhiêu bài và mất bao lâu?
3. Tổng cộng cả tài liệu có bao nhiêu bài và tổng thời lượng chuẩn/thực tế là bao nhiêu?

### 5.1. Bố cục trang (Layout):
* **Hero Header (Thông tin chung):**
  * Ảnh bìa (Cover Image).
  * Tiêu đề tài liệu & Badge trạng thái (`Chưa học` / `Đang học` / `Hoàn thành`).
  * Các chỉ số tổng quan: Tổng số Units, Tổng số bài nghe, Tổng thời lượng chuẩn.
  * Trình đo tiến độ hình tròn (Circular Progress Bar) hiển thị `% Hoàn thành` (Số bài đã nghe đạt / Tổng số bài).
  * Nhóm nút hành động: `▶ Tiếp tục học` và `🎧 Học từ đầu`.
* **Thống kê nhanh (Quick Stats Row):**
  * Hàng gồm 4 thẻ chỉ số: Tổng số giai đoạn (Chương), Tổng số Units, Tổng số bài nghe, Tổng thời lượng chuẩn.
* **Cấu trúc 2 cột chính:**
  * **Cột trái (Nội dung tài liệu):**
    * Tiêu đề: "Nội dung tài liệu" kèm nút toggle "Thu gọn tất cả" / "Mở rộng tất cả".
    * Accordion danh sách các **Giai đoạn (Chương - Parts)**:
      * Mặc định chỉ mở Giai đoạn 1. Các giai đoạn khác ở trạng thái thu gọn.
      * Header hiển thị: Tên giai đoạn, số lượng units, số lượng bài nghe và tổng thời lượng của giai đoạn đó. Có màu sắc chủ đề khác nhau (Xanh lá, Cam, Xám...) tùy theo thứ tự giai đoạn.
      * Khi mở một Giai đoạn, hiển thị bảng danh sách các **Unit**:
        * Cột: `UNIT` (có badge số thứ tự), `SỐ BÀI`, `THỜI LƯỢNG (CHUẨN)`.
        * Nhấp vào một dòng Unit sẽ **mở rộng/thu gọn** danh sách chi tiết các file nghe thuộc Unit đó (chứa nút bấm `▶ Luyện nghe` và trạng thái hoàn thành `✅` hoặc `🎧`).
  * **Cột phải (Tổng kết toàn bộ tài liệu):**
    * Thống kê tổng kết toàn bộ quá trình học của học viên đối với tài liệu này:
      * **Tổng số bài nghe:** Lấy từ database.
      * **Tổng thời lượng chuẩn:** Thời lượng gốc của toàn bộ file nghe.
      * **Tổng thời gian thực tế:** Tổng thời gian học viên đã nghe thực tế (tổng `duration_seconds` của tất cả các sessions).
      * **Chênh lệch:** Khoảng chênh lệch giữa thời gian thực tế và thời gian chuẩn (hiển thị dạng `+X giờ Y phút`).
      * **Tổng lượt nghe:** Tổng số lượt đã nhấn nghe (số lượng sessions).
      * **Nghe để hiểu:** Số lượt nghe đã vượt qua bài đánh giá (số lượng sessions có `is_passed = true`).
      * **Tốc độ trung bình:** Tốc độ nghe trung bình.
      * **Hiệu suất học:** Điểm hiệu suất học tập trung bình của tài liệu.

### 5.2. Công thức tính toán động (Blade Logic):
* **Tổng thời lượng chuẩn:** `SUM(listening.duration)` của toàn bộ bài nghe trong tài liệu.
* **Tổng thời gian thực tế:** `SUM(listening_sessions.duration_seconds)` liên kết với các bài nghe của tài liệu này.
* **% Hoàn thành:** `(Số bài nghe có ít nhất 1 lần đạt / Tổng số bài nghe) * 100`.

---
### Models
```
app/Models/
├── Document/
│   ├── Document.php            ← 6 nhóm fillable, casts, relationships
│   ├── DocumentPart.php        ← Chương, relationships, duration helpers
│   └── DocumentUnit.php        ← Bài trong chương
└── Listening/
    ├── Listening.php           ← File nghe, efficiency(), listenCountForUser()
    ├── ListeningTopic.php      ← Chủ đề
    ├── ListeningTranscript.php ← Transcript (original/translation)
    ├── ListeningSession.php    ← Phiên nghe
    ├── ListeningAssessment.php ← Đánh giá + calculateScore()
    └── ListeningWeeklyStat.php ← Thống kê tuần + recordSession()
```

### Controllers
```
app/Http/Controllers/
├── Document/
│   └── DocumentController.php  ← CRUD + 6 nhóm + thống kê chương
└── Listening/
    └── ListeningController.php ← CRUD + session + assessment + result
```

### Views
```
resources/views/skills/listening/
├── index.blade.php              ← Layout wrapper
├── listening.blade.php          ← Trang chủ Listening
├── tree.blade.php               ← Legacy tree view
├── document/
│   ├── index.blade.php          ← Danh mục tài liệu (grouped by level)
│   ├── show.blade.php           ← Chi tiết tài liệu (6 tabs + bảng chương)
│   └── form.blade.php           ← Form thêm/sửa tài liệu
├── session/
│   ├── form.blade.php           ← Form thêm/sửa file nghe
│   └── practice.blade.php      ← Giao diện luyện nghe + đánh giá
└── components/
    ├── performance.blade.php
    ├── table.blade.php
    ├── tableofcontent.blade.php
    └── tree_timeline.blade.php
```

### Migrations
```
database/migrations/
├── 2026_06_11_000000_create_document_tables.php
│   → documents, document_parts, document_units
├── 2026_06_11_000001_create_listening_tables.php
│   → listening_topics, listening, listening_transcripts
├── 2026_06_11_000003_create_listening_sessions_table.php
├── 2026_06_11_000004_create_listening_assessments_table.php
└── 2026_06_11_000005_create_listening_weekly_stats_table.php
```

---

## 7. Routes

### Listening
| Method | URL | Action | Tên |
|---|---|---|---|
| GET | /listening | index | listening.index |
| GET | /listening/create | create | listening.create |
| POST | /listening | store | listening.store |
| GET | /listening/{id} | show (practice) | listening.show |
| GET | /listening/{id}/edit | edit | listening.edit |
| PUT | /listening/{id} | update | listening.update |
| DELETE | /listening/{id} | destroy | listening.destroy |
| POST | /listening/{id}/session/start | startSession | listening.session.start |
| POST | /listening/{id}/assessment/submit | submitAssessment | listening.assessment.submit |
| GET | /listening/{id}/result | result | listening.result |
| GET | /listening/tree/{id} | showDocumentTree | listening.tree |

### Document
| Method | URL | Action | Tên |
|---|---|---|---|
| GET | /document | index | document.index |
| GET | /document/create | create | document.create |
| POST | /document | store | document.store |
| GET | /document/{id} | show | document.show |
| GET | /document/{id}/edit | edit | document.edit |
| PUT | /document/{id} | update | document.update |
| DELETE | /document/{id} | destroy | document.destroy |

### API
| Method | URL | Mô tả |
|---|---|---|
| GET | /api/document/{id}/parts | Lấy danh sách chương theo tài liệu |

---

## 8. Tích hợp tương lai

- [ ] **Dictation Mode**: Chế độ nghe từng câu, tự động chuyển transcript theo thời gian
- [ ] **Leaderboard**: Xếp hạng hiệu suất nghe theo người dùng
- [ ] **Progress tracking**: Thanh tiến trình tổng thể theo tài liệu
- [ ] **Spaced Repetition**: Gợi ý file nghe cần ôn dựa trên lịch sử
- [ ] **Export**: Xuất báo cáo tiến trình học nghe (PDF)

---

*Cập nhật lần cuối: {{ date('d/m/Y') }}*
*Tác giả: Antigravity AI Assistant*
