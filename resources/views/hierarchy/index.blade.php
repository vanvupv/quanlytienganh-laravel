@extends('grammars.grammar')

@section('content_grammar')
<div class="container-fluid py-4" style="background: #f8fafc; min-height: 100vh;">
  <!-- Title and Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h1 class="fw-extrabold text-dark mb-1" style="font-size: 26px; letter-spacing: -0.5px;">🌳 Quản Lý Cây Phân Cấp</h1>
      <p class="text-muted small mb-0">Thiết lập các sơ đồ, lộ trình học tập dạng cây thư mục kéo thả linh hoạt cho toàn hệ thống.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
      <strong>🎉 Thành công!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- Cột trái: Danh sách các cây hiện có -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
        <h3 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2" style="font-size: 18px;">
          📋 Danh Sách Cây Phân Cấp Hiện Có
        </h3>

        @if($trees->isEmpty())
          <div class="text-center py-5">
            <div class="display-4 text-muted mb-3">🌳</div>
            <h5 class="text-secondary fw-semibold">Chưa có cây phân cấp nào</h5>
            <p class="text-muted small">Hãy nhập thông tin ở bảng bên phải để tạo cây phân cấp đầu tiên!</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Tên cây</th>
                  <th>Mã Code</th>
                  <th class="text-center">Phân hệ</th>
                  <th class="text-center">Số nút</th>
                  <th class="text-end">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($trees as $tree)
                  <tr>
                    <td>
                      <div class="fw-bold text-dark">{{ $tree->name }}</div>
                      <div class="text-muted small">{{ Str::limit($tree->description, 60) ?: 'Không có mô tả.' }}</div>
                    </td>
                    <td>
                      <code class="px-2 py-1 bg-light text-primary rounded" style="font-size: 12px;">{{ $tree->code }}</code>
                    </td>
                    <td class="text-center">
                      <span class="badge rounded-pill px-2.5 py-1" style="font-size: 11px;
                        @if($tree->module === 'grammar') background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;
                        @elseif($tree->module === 'vocabulary') background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
                        @elseif($tree->module === 'listening') background-color: #fff7ed; color: #9a3412; border: 1px solid #fed7aa;
                        @else background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;
                        @endif">
                        {{ strtoupper($tree->module) }}
                      </span>
                    </td>
                    <td class="text-center fw-bold text-secondary">
                      {{ $tree->nodes_count }} nút
                    </td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-1.5">
                        <a href="{{ route('hierarchy.manage', $tree->id) }}" class="btn btn-sm btn-primary fw-bold shadow-sm" style="border-radius: 8px; font-size: 12px; padding: 6px 12px;">
                          ⚙️ Cấu hình
                        </a>
                        <a href="{{ route('hierarchy.export-pdf', $tree->id) }}" class="btn btn-sm btn-outline-danger fw-bold shadow-sm" style="border-radius: 8px; font-size: 12px; padding: 6px 12px;">
                          📄 Xuất PDF
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- Cột phải: Form tạo cây mới -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
        <h3 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2" style="font-size: 18px;">
          ➕ Tạo Cây Phân Cấp Mới
        </h3>

        <form action="{{ route('hierarchy.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="name" class="form-label fw-bold text-secondary small">Tên cây phân cấp</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Ví dụ: Lộ trình từ vựng IELTS" value="{{ old('name') }}" required style="border-radius: 8px;">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="code" class="form-label fw-bold text-secondary small">Mã Code (Duy nhất)</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Ví dụ: vocab_ielts_roadmap" value="{{ old('code') }}" required style="border-radius: 8px;">
            <div class="form-text text-muted" style="font-size: 11px;">Chỉ dùng chữ thường, số và dấu gạch dưới.</div>
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="module" class="form-label fw-bold text-secondary small">Thuộc phân hệ</label>
            <select class="form-select @error('module') is-invalid @enderror" id="module" name="module" required style="border-radius: 8px;">
              <option value="grammar" {{ old('module') == 'grammar' ? 'selected' : '' }}>Ngữ pháp (Grammar)</option>
              <option value="vocabulary" {{ old('module') == 'vocabulary' ? 'selected' : '' }}>Từ vựng (Vocabulary)</option>
              <option value="listening" {{ old('module') == 'listening' ? 'selected' : '' }}>Luyện nghe (Listening)</option>
              <option value="custom" {{ old('module') == 'custom' ? 'selected' : '' }}>Khác (Custom)</option>
            </select>
            @error('module')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-bold text-secondary small">Mô tả ngắn</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Mô tả mục tiêu của cây này..." style="border-radius: 8px;">{{ old('description') }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2 shadow" style="border-radius: 8px;">
            🚀 Khởi Tạo Cây
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
