# 📋 DANH SÁCH VIỆC CẦN LÀM & LỘ TRÌNH PHÁT TRIỂN (PROJECT TODO LIST)

Tài liệu này tổng hợp toàn bộ các nhiệm vụ chưa hoàn thành, các tính năng tích hợp trong tương lai và các cải tiến kỹ thuật cho dự án **Quản lý Tiếng Anh** (quanlytienganh).

---

## 🎧 1. PHÂN HỆ LUYỆN NGHE (LISTENING MODULE)
*Các hạng mục được lên kế hoạch tích hợp trong tương lai theo [Listening.md](file:///d:/xampp/htdocs/quanlytienganh/Listening.md):*

- [ ] **Dictation Mode (Chế độ nghe chép chính tả nâng cao):**
  - [ ] Phát triển giao diện nghe từng câu, tự động tua hoặc lặp lại đoạn âm thanh.
  - [ ] Tự động cuộn transcript khớp theo mốc thời gian (Audio Timestamp Matching).
- [ ] **Leaderboard (Bảng xếp hạng Luyện nghe):**
  - [ ] Xếp hạng hiệu suất nghe (`efficiency_score`) của tất cả học sinh trong lớp.
  - [ ] Bảng vinh danh học viên có số giờ luyện nghe nhiều nhất trong tuần/tháng.
- [ ] **Progress Tracking (Theo dõi tiến trình tài liệu):**
  - [ ] Hiển thị thanh tiến trình tổng thể (%) hoàn thành của từng cuốn sách/tài liệu.
  - [ ] Ghi nhận trực quan số bài đã đạt trên tổng số bài trong tài liệu (`document.show`).
- [ ] **Spaced Repetition for Listening (Lặp lại ngắt quãng cho Luyện nghe):**
  - [ ] Thuật toán gợi ý các file nghe cần ôn tập lại dựa trên lịch sử điểm số và thời gian kể từ lần nghe cuối.
- [ ] **Export Reports (Xuất báo cáo tiến trình):**
  - [ ] Tạo tính năng xuất báo cáo tiến trình học nghe ra định dạng PDF dành cho phụ huynh hoặc giáo viên.

---

## 🌳 2. PHÂN HỆ TỪ VỰNG (VOCABULARY MODULE)
*Các tính năng bổ sung cao cấp đang được lên kế hoạch theo [Vocabulary.md](file:///d:/xampp/htdocs/quanlytienganh/Vocabulary.md):*

- [ ] **🔊 Tích hợp phát âm tự động (Auto-Audio Pronunciation API):**
  - [ ] Kết nối API phát âm miễn phí (Google TTS hoặc Dictionary API).
  - [ ] Hiển thị biểu tượng loa và phát âm chuẩn giọng Anh-Anh (UK) và Anh-Mỹ (US) ngay cạnh từ vựng ở giao diện học và ôn tập.
- [ ] **📈 Biểu đồ tăng trưởng từ vựng (Vocabulary Growth Chart):**
  - [ ] Vẽ biểu đồ trực quan (sử dụng Chart.js) hiển thị "kích thước kho từ vựng" của học sinh phình to theo thời gian qua từng giai đoạn học tập.
- [ ] **🗂️ Quản lý theo Bộ tài liệu & Chủ đề (Tags & Folders):**
  - [ ] Phát triển bộ lọc từ vựng theo giáo trình học (ví dụ: `Oxford 3000`, `TACB 1&2`) hoặc theo chủ đề (gia đình, kinh doanh, du lịch).
- [ ] **📊 Báo cáo tiến trình học tập cá nhân nâng cao:**
  - [ ] Thống kê chi tiết tỷ lệ phân bổ từ vựng của học viên theo 3 giai đoạn (Nền tảng - Phát triển - Nâng cao).
  - [ ] Thống kê số từ đã đạt chuẩn "Mastered" (Mastered rate).

---

## 📖 3. PHÂN HỆ NGỮ PHÁP (GRAMMAR MODULE)
*Các hạng mục cải tiến và nội dung ngữ pháp theo [Grammar.md](file:///d:/xampp/htdocs/quanlytienganh/Grammar.md):*

- [ ] **🔄 Spaced Repetition cho Ngữ Pháp:**
  - [ ] Đề xuất thời điểm cần ôn tập lại bài học dựa trên mốc thời gian tối thiểu của các phần (Công thức, Cách dùng, Ví dụ).
- [ ] **📚 Làm phong phú ngân hàng câu hỏi:**
  - [ ] Bổ sung thêm nhiều câu hỏi thực tế trong cơ sở dữ liệu cho các bài học phổ biến (Các thì, Câu điều kiện, Mệnh đề quan hệ).
- [ ] **🎨 Tương tác visual hóa công thức:**
  - [ ] Chuyển các công thức dạng text JSON thành các khối màu trực quan hoặc sơ đồ cây sinh động hơn trên giao diện.

---

## 👤 4. HỒ SƠ & ANNALYTICS (USER & ANALYTICS)
*Các nâng cấp cho phân hệ Học Viên theo [User.md](file:///d:/xampp/htdocs/quanlytienganh/User.md):*

- [ ] **🤖 Tích hợp AI Study Coach thực tế (Đề xuất thông minh):**
  - [ ] Sử dụng Gemini API/OpenAI API để phân tích điểm yếu của học viên dựa trên lịch sử làm bài và tự động viết lời khuyên cá nhân hóa động thay vì các gợi ý cố định.
- [ ] **🎖️ Mở rộng danh sách Huy hiệu (Achievements):**
  - [ ] Thêm các huy hiệu đặc thù cho kỹ năng Luyện nghe (ví dụ: *Tai Vàng* - nghe đạt 5 bài 100% hiệu suất).
- [ ] **🤝 Tính năng chia sẻ Mạng xã hội:**
  - [ ] Cho phép học sinh tạo ảnh chụp đẹp (Image Generator) từ trang Profile để chia sẻ trực tiếp lên Facebook/Zalo.

---

## 🛠️ 5. CẢI TIẾN KỸ THUẬT & HỆ THỐNG (TECHNICAL & INFRASTRUCTURE)

- [ ] **🧪 Viết Kiểm Thử Tự Động (Automated Testing):**
  - [ ] Xây dựng các Unit Test & Feature Test bằng PHPUnit cho các controller chấm điểm (`AssessmentController`, `VocabularyController`, `GrammarController`).
  - [ ] Viết test case xác minh thuật toán chuẩn hóa chuỗi Fuzzy Matching hoạt động chuẩn xác trong mọi trường hợp dấu câu và khoảng trắng.
- [ ] **⚡ Tối ưu hóa truy vấn Database (N+1 Query Detection):**
  - [ ] Sử dụng Laravel Debugbar để kiểm tra và loại bỏ hoàn toàn các lỗi truy vấn N+1 khi load cây dữ liệu Profile và Lộ trình học.
  - [ ] Sử dụng Eager Loading (`with()`) triệt để cho các mối quan hệ đa cấp.
- [ ] **📁 Refactoring Asset Management:**
  - [ ] Đồng bộ hóa các thư viện CSS/JS qua Vite (thay vì gọi CDN trực tiếp ở Blade layout) để tăng tốc độ tải trang.
- [ ] **⏰ Cron Jobs & Queue:**
  - [ ] Thiết lập queue để chạy các tác vụ chấm điểm chép chính tả dài hoặc xử lý tính toán bảng thống kê tuần `listening_weekly_stats` vào cuối tuần để không làm chậm trải nghiệm của người dùng.

---
*Cập nhật lần cuối: 21/06/2026*  
*Người lập: Antigravity AI Assistant*
