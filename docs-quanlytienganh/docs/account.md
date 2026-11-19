# 🔑 DANH SÁCH TÀI KHOẢN HỆ THỐNG (SYSTEM ACCOUNTS)

Tài liệu này lưu trữ danh sách các tài khoản truy cập mặc định và tài khoản thử nghiệm của hệ thống **Quản lý Tiếng Anh** (quanlytienganh).

---

## 👥 1. DANH SÁCH TÀI KHOẢN MẶC ĐỊNH (DEFAULT ACCOUNTS)

Dưới đây là các tài khoản mặc định được khởi tạo từ Seeder dữ liệu (`VocabularySeeder.php` / `DatabaseSeeder.php`):

| STT | Vai Trò (Role) | Họ & Tên | Địa Chỉ Email | Mật Khẩu Mặc Định | Quyền Hạn & Chức Năng |
| :---: | :--- | :--- | :--- | :---: | :--- |
| **1** | 🛡️ **Admin** (Quản trị) | Quản Trị Viên | `admin@quanlytienganh.com` | `123456` | Quản trị hệ thống, CRUD danh mục Ngữ pháp, Từ vựng, Tài liệu Luyện nghe, Cây phân cấp (Hierarchy Tree), Quản lý Huy hiệu & Thành tựu. |
| **2** | 🎓 **Student** (Học viên) | Học Viên Anh Văn | `hocvien@quanlytienganh.com` | `123456` | Luyện học từ vựng, ngữ pháp, thực hiện bài kiểm tra Đánh giá (Assessment), làm bài Luyện nghe (Dictation), theo dõi Radar Chart năng lực & Hồ sơ cá nhân. |

---

## 🔐 2. HƯỚNG DẪN TRUY CẬP & ĐĂNG NHẬP

1. **Đường dẫn đăng nhập:** `/login` (Ví dụ: `http://127.0.0.1:8000/login`)
2. **Chế độ Khách (Guest Mode):** Nếu chưa đăng nhập, hệ thống sẽ tự động dùng cơ chế **Guest-Fallback** ánh xạ dữ liệu của `hocvien@quanlytienganh.com` để trải nghiệm giao diện mà không bị crash trang.
3. **Đăng ký tài khoản mới:** Bạn có thể tự do khởi tạo tài khoản học viên mới tại đường dẫn `/register`. Hệ thống sẽ tự động thiết lập thuộc tính lộ trình học mặc định (`Level: B1`, `Retention Rate: 75%`, `Learning Speed: Trung bình`).

---

## ⚙️ 3. TÁI TẠO TÀI KHOẢN MẶC ĐỊNH (RESEED DATABASE)

Nếu tài khoản bị xóa hoặc cần khôi phục lại dữ liệu thử nghiệm ban đầu, bạn có thể chạy lệnh Seeder trong Terminal:

```powershell
php artisan db:seed --class=VocabularySeeder
```

Lệnh này sẽ tự động kiểm tra và khởi tạo lại 2 tài khoản `admin@quanlytienganh.com` và `hocvien@quanlytienganh.com` nếu chưa tồn tại.
