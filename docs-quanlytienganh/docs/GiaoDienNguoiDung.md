# 🎨 TÀI LIỆU THIẾT KẾ & HƯỚNG DẪN GIAO DIỆN NGƯỜI DÙNG (USER INTERFACE SPECIFICATION)

Tài liệu này đặc tả hệ thống thiết kế (Design System), triết lý UI/UX và các thành phần giao diện tương tác cao cấp (Premium UI Components) đang được áp dụng trong ứng dụng **Quản lý Tiếng Anh** (quanlytienganh).

---

## 🌌 1. PHONG CÁCH CHỦ ĐẠO (DESIGN SYSTEM & VISUAL STYLE)

Ứng dụng hướng tới trải nghiệm học tập hiện đại, kích thích thị giác, sử dụng các tiêu chuẩn thiết kế UI/UX cao cấp:

### A. Triết lý Thiết kế (Design Philosophy)
*   **Sleek Dark Mode:** Nền tối sâu kết hợp với màu chữ trắng/xám sáng giúp giảm mỏi mắt khi học thời gian dài.
*   **Glassmorphism (Kính mờ):** Sử dụng các tấm thẻ có nền nửa trong suốt, kết hợp bộ lọc làm mờ hậu cảnh (`backdrop-filter: blur(12px)`) và viền mảnh bán trong suốt tạo chiều sâu không gian.
*   **Glowing Blobs (Đốm sáng động):** Các khối hình tròn gradient chuyển màu mềm mại chuyển động mờ phía sau các tấm kính Glassmorphism để tạo hiệu ứng huyền ảo, cao cấp.

### B. Bảng màu Taylored (Harmonious Palette)
Hệ thống sử dụng dải màu HSL tùy biến để tạo sự đồng bộ:
*   **Màu chủ đạo (Primary - Accent):** Gradient từ Xanh dương hoàng gia (`#2563eb` / `hsl(221, 83%, 53%)`) sang Tím Neon (`#8b5cf6` / `hsl(258, 90%, 66%)`).
*   **Màu trạng thái (Status Colors):**
    *   🟢 **Đạt / Thành thạo (Success):** Emerald Green (`#10b981` / `hsl(142, 70%, 45%)`) - Biểu thị cho tri thức đã đạt chuẩn.
    *   🟡 **Đang luyện / Cảnh báo (Warning):** Amber Gold (`#f59e0b` / `hsl(38, 92%, 50%)`) - Cần lưu ý, đang tiến hành.
    *   🔴 **Chưa đạt / Yếu (Danger):** Rose Red (`#ef4444` / `hsl(0, 84%, 60%)`) - Cần ôn tập gấp.
    *   ⚪ **Chưa bắt đầu (Muted/Inactive):** Slate/Gray (`#6b7280` / `hsl(215, 16%, 47%)`) - Trạng thái tĩnh.

### C. Typography & Font chữ
*   **Font chữ:** Ưu tiên sử dụng các font Sans-serif hiện đại từ Google Fonts như **Inter** hoặc **Outfit**.
*   **Kích thước & Trọng số:** Thiết lập cấu trúc phân cấp rõ ràng (từ `h1` lớn đậm đến `span` chú thích nhỏ mảnh).

---

## 🗂️ 2. BỐ CỤC & THANH ĐIỀU HƯỚNG (LAYOUT & NAVIGATION)

### A. Sidebar Thông Minh (Smart Sidebar Layout)
Sidebar đóng vai trò là xương sống định tuyến của ứng dụng, tự động kết nối và phản ứng linh hoạt theo trạng thái người dùng:
*   **Khi chưa đăng nhập (Guest Mode):**
    *   Hiển thị thẻ kêu gọi hành động (CTA Card) viền nét đứt (dashed border).
    *   Có nút bấm "Đăng nhập ngay" để dẫn hướng người dùng.
    *   **Guest-Fallback:** Tự động hiển thị dữ liệu mẫu của học viên đầu tiên trên hệ thống để người dùng trải nghiệm thử mà không bị lỗi crash trang.
*   **Khi đã đăng nhập (Authenticated Mode):**
    *   Hiển thị thẻ tài khoản Premium (Premium User Card).
    *   Ảnh đại diện (Avatar) hình tròn bo viền phát sáng kèm chấm xanh báo trạng thái hoạt động (Online Dot).
    *   Hiển thị Tên học sinh và cấp độ CEFR hiện tại.
    *   Có các lối tắt đi nhanh tới Hồ sơ cá nhân (`/profile`) và nút Đăng xuất an toàn.

---

## 🌲 3. CÁC THÀNH PHẦN GIAO DIỆN TƯƠNG TÁC ĐẶC TRƯNG

### A. Cây Phát Triển Từ Vựng (Vocabulary Growth Tree)
*   **Visual Concept:** Từ vựng được hiển thị như một cấu trúc cây phát triển động phân nhánh qua các Giai đoạn (Stages).
*   **Quy tắc màu sắc nút:**
    *   `new` (Màu xanh lá): Các nghĩa/từ loại mới học được ở Giai đoạn hiện tại.
    *   `old` (Màu xám): Các nghĩa/từ loại đã học được tích lũy từ các giai đoạn trước đó để học viên ôn tập tổng thể.
*   **File view liên quan:** [voca.blade.php](file:///d:/xampp/htdocs/quanlytienganh/resources/views/vocabularies/voca.blade.php)

### B. Radar Skill Map (Bản Đồ Năng Lực 5 Chiều)
*   **Công nghệ:** Tích hợp **Chart.js** vẽ biểu đồ mạng nhện (Radar Chart).
*   **5 Chiều đo lường:**
    1.  *Vocabulary Size:* Quy mô từ vựng đã học.
    2.  *Grammar Mastery:* Tỉ lệ hoàn thành bài ngữ pháp.
    3.  *Response Speed:* Tốc độ phản xạ trung bình khi ôn tập.
    4.  *Recall Retention:* Tỷ lệ ghi nhớ thực tế qua các bài test.
    5.  *Streak & Discipline:* Mức độ kiên trì học tập liên tục.
*   **Hiệu ứng:** Vẽ hover tooltip hiển thị thông số chi tiết khi di chuột qua các góc của biểu đồ.

### C. Đồng Hồ Đếm Ngược Di Động (Draggable Countdown Timer)
*   **Áp dụng:** Trong giao diện thi/đánh giá [do.blade.php](file:///d:/xampp/htdocs/quanlytienganh/resources/views/assessments/do.blade.php).
*   **UX Feature:** Đồng hồ đếm ngược hiển thị dưới dạng một widget tròn sang trọng. Học sinh có thể click và kéo thả (draggable) widget này đến bất kỳ vị trí nào trên màn hình để tránh che khuất nội dung câu hỏi.
*   **Tự động Submit:** Khi đồng hồ về `00:00`, màn hình sẽ tự động khóa lại (freeze) và tự động kích hoạt gửi bài thi lên server.

### D. Accordion Tiến Trình Ngữ Pháp & 3 Dấu Chấm (Pips) Trạng Thái
*   **Visual Progress:** Lộ trình ngữ pháp hiển thị dưới dạng Accordion xếp gọn theo Category và Topic.
*   **3 Chấm Pips (Mini Progress Indicators):**
    Mỗi bài học ngữ pháp đi kèm với 3 dấu chấm nhỏ thể hiện tiến trình hoàn thành 3 phần bắt buộc:
    *   Chấm 1: **Công thức (Formula)**
    *   Chấm 2: **Cách dùng (Usage)**
    *   Chấm 3: **Ví dụ (Examples)**
    *   *Màu xanh lá:* Phần đó đã đạt (hoàn thành đủ thời gian và điểm số >= passing_score).
    *   *Màu xám:* Phần đó chưa hoàn thành hoặc chưa đạt chuẩn.

### E. Thẻ Huy Hiệu Thành Tựu (Dynamic Achievement Badges)
*   **Phân chia trạng thái:**
    *   *Đã mở khóa (Earned - Colored):* Huy hiệu hiển thị đầy đủ màu sắc sắc nét kèm theo dòng chữ thời gian đạt cúp (Ví dụ: `Đạt ngày: 18/05/2026`).
    *   *Chưa mở khóa (Locked - Grayscale):* Huy hiệu chuyển thành màu xám, giảm độ mờ (opacity), hiển thị thanh tiến trình nhỏ (ProgressBar) kèm chỉ số tiến trình hiện tại trên mục tiêu (Ví dụ: `3 / 10 lượt`).
*   **File view liên quan:** [profile/index.blade.php](file:///d:/xampp/htdocs/quanlytienganh/resources/views/profile/index.blade.php)

---

## ⚡ 4. HIỆU ỨNG CHUYỂN ĐỘNG & ĐỘNG LỰC HỌC TẬP (GAMIFICATION)

### A. Hiệu ứng Pháo Hoa Chúc Mừng (Canvas-Confetti)
*   **Trigger:** Khi học viên nộp bài thi ôn tập đạt điểm Mastery (`>= 80đ`) hoặc mở khóa thành công một Huy hiệu mới.
*   **UX Feature:** Hệ thống kích hoạt thư viện `canvas-confetti` bắn pháo hoa giấy nhiều màu sắc rực rỡ từ hai bên góc màn hình bay lên, tạo cảm giác ăn mừng chiến thắng và nâng cao hứng thú học tập.

### B. Viền Phát Sáng Động (Glowing borders)
*   Các trường nhập liệu (inputs) trong form đăng nhập, đăng ký và ô điền câu trả lời sẽ phát ra một quầng sáng mềm màu xanh dương/tím nhạt (box-shadow neon) khi học viên nhấp chọn (focus) để thu hút sự tập trung.

---

## 📱 5. TIÊU CHUẨN ĐÁP ỨNG DI ĐỘNG (RESPONSIVE STANDARDS)

Toàn bộ hệ thống giao diện được tối ưu hóa hiển thị trên mọi kích thước màn hình:
*   **Desktop (>= 1024px):** Layout chia cột cân đối, hiển thị Sidebar cố định bên trái, nội dung chính ở giữa, và các widget thống kê/AI Coach ở bên phải.
*   **Tablet (768px - 1023px):** Sidebar tự động thu gọn thành dạng mini-icon hoặc thanh điều hướng ngang dưới đáy.
*   **Mobile (< 768px):** 
    *   Các bảng biểu chuyển sang dạng danh sách trượt dọc (card-list).
    *   Sidebar ẩn vào menu Hambuger ở góc trên.
    *   Các nút bấm nộp bài, điều hướng câu hỏi thi được làm to hơn (tối thiểu `44px x 44px`) để dễ dàng tương tác bằng ngón tay.
