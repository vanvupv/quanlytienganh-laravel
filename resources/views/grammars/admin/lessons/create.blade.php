@extends('grammars.grammar')

@section('title', 'Thêm Bài Học Mới')

@section('content_grammar')
  <div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">
    <!-- Back Header -->
    <div class="mb-4">
      <a href="{{ route('grammar.admin.index') }}" class="text-decoration-none text-muted fw-bold small d-inline-flex align-items-center gap-1">
        ← Quay lại bảng điều khiển
      </a>
      <h1 class="fw-bold text-dark mt-2 mb-1" style="font-size: 28px; letter-spacing: -0.5px;">➕ Thêm Bài Học Ngữ Pháp</h1>
      <p class="text-muted mb-0">Tạo mới phần kiến thức chi tiết (như "Thì hiện tại đơn") dưới một lộ trình đã có.</p>
    </div>

    <!-- Main Creation Form Card -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white; max-width: 900px;">
      <form action="{{ route('grammar.admin.lessons.store') }}" method="POST">
        @csrf

        <!-- 1. Basic Info Section -->
        <h4 class="fw-bold text-primary mb-3 pb-2 border-bottom" style="font-size: 18px;">📋 1. Thông Tin Chung</h4>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="topic_id" class="form-label fw-bold">Chọn Lộ Trình <span class="text-danger">*</span></label>
            <select class="form-select" name="topic_id" required style="border-radius: 8px;">
              <option value="" disabled selected>--- Chọn lộ trình phù hợp ---</option>
              @foreach($topics as $topic)
                <option value="{{ $topic->id }}">
                  {{ $topic->title }} ({{ $topic->category->name ?? '' }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label for="title" class="form-label fw-bold">Tên Bài Học <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" placeholder="Ví dụ: Thì Hiện Tại Đơn (Simple Present)" required style="border-radius: 8px;">
          </div>
          <div class="col-md-6">
            <label for="order" class="form-label fw-bold">Thứ tự sắp xếp trong lộ trình</label>
            <input type="number" class="form-control" name="order" value="0" style="border-radius: 8px;">
          </div>
        </div>

        <!-- 1b. Customizable Time Targets and Passing Score -->
        <h4 class="fw-bold text-primary mb-3 pb-2 border-bottom" style="font-size: 18px;">⏱️ 1.5 Cấu hình Thời gian & Điểm chuẩn ôn tập</h4>
        <div class="row g-3 mb-4">
          <div class="col-md-4 col-sm-6">
            <label for="formula_study_time" class="form-label fw-bold small text-secondary">Công Thức - Học Lý Thuyết (Phút)</label>
            <input type="number" class="form-control" name="formula_study_time" value="15" required style="border-radius: 8px;">
          </div>
          <div class="col-md-4 col-sm-6">
            <label for="formula_min_review_time" class="form-label fw-bold small text-secondary">Công Thức - Ôn Tối Thiểu (Phút)</label>
            <input type="number" class="form-control" name="formula_min_review_time" value="10" required style="border-radius: 8px;">
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="passing_score" class="form-label fw-bold small text-success">Điểm Chuẩn Đạt Bài Ôn Tập (0-100)</label>
            <input type="number" class="form-control text-success fw-bold" name="passing_score" value="80" min="0" max="100" required style="border-radius: 8px;">
          </div>

          <div class="col-md-4 col-sm-6">
            <label for="usage_study_time" class="form-label fw-bold small text-secondary">Cách Dùng - Học Lý Thuyết (Phút)</label>
            <input type="number" class="form-control" name="usage_study_time" value="20" required style="border-radius: 8px;">
          </div>
          <div class="col-md-4 col-sm-6">
            <label for="usage_min_review_time" class="form-label fw-bold small text-secondary">Cách Dùng - Ôn Tối Thiểu (Phút)</label>
            <input type="number" class="form-control" name="usage_min_review_time" value="15" required style="border-radius: 8px;">
          </div>
          <div class="col-md-4 col-sm-12"></div>

          <div class="col-md-4 col-sm-6">
            <label for="example_study_time" class="form-label fw-bold small text-secondary">Ví Dụ - Học Lý Thuyết (Phút)</label>
            <input type="number" class="form-control" name="example_study_time" value="10" required style="border-radius: 8px;">
          </div>
          <div class="col-md-4 col-sm-6">
            <label for="example_min_review_time" class="form-label fw-bold small text-secondary">Ví Dụ - Ôn Tối Thiểu (Phút)</label>
            <input type="number" class="form-control" name="example_min_review_time" value="10" required style="border-radius: 8px;">
          </div>
        </div>

        <!-- 2. Formula (Công Thức) Section -->
        <h4 class="fw-bold text-primary mb-3 pb-2 border-bottom" style="font-size: 18px;">🔹 2. Công Thức (Formula)</h4>
        <div class="row g-3 mb-4">
          <div class="col-12">
            <label for="formula_affirmative" class="form-label fw-bold">Thể khẳng định (+)</label>
            <input type="text" class="form-control font-monospace text-primary" name="formula_affirmative" placeholder="Ví dụ: S + V(s/es) / S + am/is/are + O" required style="border-radius: 8px;">
          </div>
          <div class="col-12">
            <label for="formula_negative" class="form-label fw-bold">Thể phủ định (-)</label>
            <input type="text" class="form-control font-monospace text-danger" name="formula_negative" placeholder="Ví dụ: S + do/does + not + V / S + am/is/are + not + O" required style="border-radius: 8px;">
          </div>
          <div class="col-12">
            <label for="formula_question" class="form-label fw-bold">Thể nghi vấn (?)</label>
            <input type="text" class="form-control font-monospace text-success" name="formula_question" placeholder="Ví dụ: Do/Does + S + V? / Am/Is/Are + S + O?" required style="border-radius: 8px;">
          </div>
        </div>

        <!-- 3. Usage Rules (Cách Dùng) Section -->
        <h4 class="fw-bold text-primary mb-3 pb-2 border-bottom" style="font-size: 18px;">📘 3. Quy Tắc & Cách Dùng (Usage)</h4>
        <div class="mb-4">
          <label for="usage_text" class="form-label fw-bold">Nhập các cách dùng của bài học <span class="text-danger">*</span></label>
          <div class="form-text text-muted mb-2">Mỗi dòng tương ứng với 1 cách dùng cụ thể. Hệ thống sẽ tự phân tích và thêm số thứ tự tự động.</div>
          <textarea class="form-control" name="usage_text" rows="5" placeholder="Ví dụ:
Diễn tả thói quen hoặc hành động lặp đi lặp lại ở hiện tại.
Diễn tả sự thật hiển nhiên, chân lý khoa học.
Diễn tả lịch trình, thời gian biểu cố định." required style="border-radius: 10px;"></textarea>
        </div>

        <!-- 4. Examples (Ví Dụ Minh Họa) Section -->
        <h4 class="fw-bold text-primary mb-3 pb-2 border-bottom d-flex justify-between align-items-center" style="font-size: 18px;">
          <span>✏️ 4. Ví Dụ Minh Họa (Examples)</span>
          <button type="button" class="btn btn-outline-primary btn-sm fw-bold ms-auto" id="add-example-row" style="border-radius: 6px;">
            + Thêm ví dụ
          </button>
        </h4>
        <div class="mb-4">
          <div class="table-responsive">
            <table class="table table-bordered align-middle" id="examples-table">
              <thead class="table-light">
                <tr>
                  <th>Câu Ví Dụ Tiếng Anh</th>
                  <th>Giải Thích / Ghi Chú Tiếng Việt</th>
                  <th style="width: 80px; text-align: center;">Xóa</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input type="text" class="form-control" name="ex_sentence[]" placeholder="Ví dụ: He plays football every Sunday." required style="border-radius: 6px;">
                  </td>
                  <td>
                    <input type="text" class="form-control" name="ex_note[]" placeholder="Ví dụ: Diễn tả thói quen" required style="border-radius: 6px;">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row-btn" style="border-radius: 6px;">✕</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex justify-content-end gap-3 pt-3 border-top mt-4">
          <a href="{{ route('grammar.admin.index') }}" class="btn btn-light px-4" style="border-radius: 8px;">Hủy</a>
          <button type="submit" class="btn btn-success px-5 fw-bold" style="border-radius: 8px; background-color: #10b981; border-color: #10b981;">Lưu bài học</button>
        </div>

      </form>
    </div>
  </div>

  <!-- Javascript for dynamic rows -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tableBody = document.querySelector('#examples-table tbody');
      const addBtn = document.getElementById('add-example-row');

      // Add Row
      addBtn.addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>
            <input type="text" class="form-control" name="ex_sentence[]" placeholder="Ví dụ: Water boils at 100 degrees Celsius." required style="border-radius: 6px;">
          </td>
          <td>
            <input type="text" class="form-control" name="ex_note[]" placeholder="Ví dụ: Diễn tả sự thật hiển nhiên" required style="border-radius: 6px;">
          </td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger remove-row-btn" style="border-radius: 6px;">✕</button>
          </td>
        `;
        tableBody.appendChild(newRow);
        attachRemoveEvent(newRow.querySelector('.remove-row-btn'));
      });

      // Attach remove event helper
      function attachRemoveEvent(btn) {
        btn.addEventListener('click', function () {
          const rowCount = tableBody.querySelectorAll('tr').length;
          if (rowCount > 1) {
            this.closest('tr').remove();
          } else {
            alert('Cần có ít nhất một dòng ví dụ!');
          }
        });
      }

      // Initialize remove event for the first row
      document.querySelectorAll('.remove-row-btn').forEach(btn => {
        attachRemoveEvent(btn);
      });
    });
  </script>
@endsection
