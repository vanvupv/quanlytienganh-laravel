<!-- Header -->
@include("layouts/header")

<!-- Style -->
<style>
  /* Container form */
  .container {
    max-width: 600px;
    margin: 30px auto;
    padding: 20px 25px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  /* Tiêu đề */
  .container h1 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
  }

  /* Labels */
  .form-label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
    color: #555;
  }

  /* Inputs, selects, textarea */
  .form-control,
  .form-select,
  textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 15px;
    margin-bottom: 15px;
    transition: all 0.2s;
  }

  .form-control:focus,
  .form-select:focus,
  textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    outline: none;
  }

  /* Button */
  .btn-primary {
    display: block;
    width: 100%;
    padding: 12px;
    font-size: 16px;
    background-color: #007bff;
    border: none;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  /* Alerts */
  .alert {
    padding: 12px 15px;
    border-radius: 5px;
    margin-bottom: 20px;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }
</style>

<!--  -->
@if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif


<div class="container">
  <h1 class="mb-4">Tạo Buổi Đánh Giá</h1>

  <!-- Hiển thị lỗi validate -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Form tạo buổi đánh giá -->
  <form action="{{ route('assessments.store') }}" method="POST">
    @csrf

    <!-- Chủ đề -->
    <div class="mb-3">
      <label for="topic" class="form-label">Chủ đề đánh giá</label>
      <select class="form-select" name="topic" id="topic" required>
        <option value="">-- Chọn chủ đề --</option>
        <option value="Từ vựng">Từ vựng</option>
        <option value="Ngữ pháp">Ngữ pháp</option>
        <option value="Đọc hiểu">Đọc hiểu</option>
        <option value="Nghe">Nghe</option>
      </select>
    </div>

    <!-- Khối lượng kiến thức -->
    <div class="mb-3">
      <label for="quantity" class="form-label">Khối lượng kiến thức</label>
      <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Ví dụ: 50 từ" required>
    </div>

    <!-- Thời gian -->
    <div class="mb-3">
      <label for="duration" class="form-label">Thời gian (phút)</label>
      <input type="number" class="form-control" name="duration" id="duration" placeholder="Ví dụ: 60" required>
    </div>

    <!-- Phương pháp đánh giá -->
    <div class="mb-3">
      <label for="method" class="form-label">Phương pháp đánh giá</label>
      <select class="form-select" name="method" id="method" required>
        <option value="">-- Chọn phương pháp --</option>
        <option value="Trắc nghiệm">Trắc nghiệm</option>
        <option value="Tự luận">Tự luận</option>
        <option value="Viết nghĩa từ">Viết nghĩa từ</option>
      </select>
    </div>

    <!-- Ngày giờ -->
    <div class="mb-3">
      <label for="start_time" class="form-label">Ngày giờ bắt đầu</label>
      <input type="datetime-local" class="form-control" name="start_time" id="start_time" required>
    </div>

    <div class="mb-3">
      <label for="end_time" class="form-label">Ngày giờ kết thúc</label>
      <input type="datetime-local" class="form-control" name="end_time" id="end_time" required>
    </div>

    <!-- Nội dung bổ sung -->
    <div class="mb-3">
      <label for="note" class="form-label">Nội dung bổ sung (tuỳ chọn)</label>
      <textarea class="form-control" name="note" id="note" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Tạo buổi đánh giá</button>
  </form>
</div>

@include("layouts/footer")