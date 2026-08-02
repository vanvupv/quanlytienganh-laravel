@extends('vocabularies.vocabulary')

@section('title', 'Quản Lý Huy Hiệu Thành Tựu (Admin)')

@section('content_voca')
<div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">
    
    <!-- Premium Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">🏆 Quản Lý Huy Hiệu & Thách Thức</h1>
            <p class="text-muted mb-0">Cài đặt, thêm mới hoặc sửa đổi các thách thức và phần thưởng huy hiệu động trên hệ thống</p>
        </div>
        <div>
            <a href="{{ route('profile.index') }}" class="btn btn-outline-primary px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px; font-size: 14.5px;">
                👤 Xem Profile Học Viên
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
            <strong>🎉 Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
            <strong>⚠️ Lỗi nhập liệu:</strong> Vui lòng kiểm tra lại các thông tin bên dưới.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 text-start">
        <!-- FORM THÊM MỚI -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: white;">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">➕ Thêm Huy Hiệu Mới</h5>
                <form action="{{ route('achievements.admin.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tên Huy Hiệu:</label>
                        <input type="text" name="title" class="form-control" placeholder="Ví dụ: Bác Học Từ Vựng" required style="border-radius: 8px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mô Tả Nhiệm Vụ:</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Ví dụ: Học và ghi nhớ thành công 100 từ vựng" required style="border-radius: 8px;"></textarea>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Icon (Emoji):</label>
                            <input type="text" name="icon" class="form-control text-center font-monospace" placeholder="🏆" required style="border-radius: 8px; font-size: 18px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Nhóm Category:</label>
                            <select name="category" class="form-select" required style="border-radius: 8px;">
                                <option value="vocab">Từ Vựng</option>
                                <option value="grammar">Ngữ Pháp</option>
                                <option value="review">Ôn Tập</option>
                                <option value="streak">Kỷ Luật</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Loại Điều Kiện (Condition Type):</label>
                        <select name="condition_type" class="form-select" required style="border-radius: 8px; font-size: 13px;">
                            <option value="vocab_studied">Số từ đã học (vocab_studied)</option>
                            <option value="vocab_mastered">Số từ thành thạo (vocab_mastered)</option>
                            <option value="grammar_lessons_done">Bài ngữ pháp hoàn thành 3/3 (grammar_lessons_done)</option>
                            <option value="reviews_done">Tổng số lượt ôn tập (reviews_done)</option>
                            <option value="retention_rate">Tỷ lệ nhớ tối thiểu % (retention_rate)</option>
                            <option value="streak_days">Số ngày học liên tục (streak_days)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Con Số Mục Tiêu (Target Value):</label>
                        <input type="number" name="target_value" class="form-control font-monospace" min="1" value="10" required style="border-radius: 8px;">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 shadow-sm" style="border-radius: 10px; background-color: #2563eb; border: none;">
                        💾 Lưu Huy Hiệu
                    </button>
                </form>
            </div>
        </div>

        <!-- DANH SÁCH HUY HIỆU -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: white;">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">📋 Danh Sách Huy Hiệu Hiện Tại</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle border text-start" style="border-radius: 12px; overflow: hidden; font-size: 13px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 8%;">Icon</th>
                                <th style="width: 25%;">Tên Huy Hiệu</th>
                                <th style="width: 22%;">Nhóm / Loại Điều Kiện</th>
                                <th style="width: 12%;">Mục Tiêu</th>
                                <th style="width: 20%;">Mô Tả Chi Tiết</th>
                                <th style="width: 13%; text-align: center;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($achievements as $ach)
                                @php
                                    $catColor = '#64748b';
                                    $catText = 'Kỷ Luật';
                                    if($ach->category == 'vocab') { $catColor = '#10b981'; $catText = 'Từ Vựng'; }
                                    elseif($ach->category == 'grammar') { $catColor = '#3b82f6'; $catText = 'Ngữ Pháp'; }
                                    elseif($ach->category == 'review') { $catColor = '#f59e0b'; $catText = 'Ôn Tập'; }
                                @endphp
                                <tr>
                                    <td class="text-center" style="font-size: 28px; line-height: 1;">{{ $ach->icon }}</td>
                                    <td>
                                        <strong class="text-dark">{{ $ach->title }}</strong>
                                        <div class="text-muted" style="font-size: 10.5px;">ID: #{{ $ach->id }}</div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: {{ $catColor }}; color: white; font-size: 9px; padding: 3px 6px;">{{ $catText }}</span>
                                        <div class="font-monospace text-muted mt-1" style="font-size: 9px;">{{ $ach->condition_type }}</div>
                                    </td>
                                    <td class="font-monospace fw-bold text-primary" style="font-size: 14px;">{{ $ach->target_value }}</td>
                                    <td class="text-muted" style="font-size: 11.5px; line-height: 1.4;">{{ $ach->description }}</td>
                                    <td style="text-align: center;">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-outline-warning" style="border-radius: 6px; padding: 2px 6px;" data-bs-toggle="modal" data-bs-target="#editAchModal-{{ $ach->id }}">✏️</button>
                                            <form action="{{ route('achievements.admin.destroy', $ach->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa huy hiệu này? Toàn bộ lịch sử mở khóa của học viên liên quan cũng sẽ bị xóa bỏ!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px; padding: 2px 6px;">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL SỬA HUY HIỆU -->
                                <div class="modal fade" id="editAchModal-{{ $ach->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="border-radius: 20px;">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">✏️ Sửa Huy Hiệu: {{ $ach->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('achievements.admin.update', $ach->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Tên Huy Hiệu:</label>
                                                        <input type="text" name="title" class="form-control" value="{{ $ach->title }}" required style="border-radius: 8px;">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Mô Tả Nhiệm Vụ:</label>
                                                        <textarea name="description" class="form-control" rows="2" required style="border-radius: 8px;">{{ $ach->description }}</textarea>
                                                    </div>

                                                    <div class="row g-2">
                                                        <div class="col-6 mb-3">
                                                            <label class="form-label small fw-semibold">Icon (Emoji):</label>
                                                            <input type="text" name="icon" class="form-control text-center font-monospace" value="{{ $ach->icon }}" required style="border-radius: 8px; font-size: 18px;">
                                                        </div>
                                                        <div class="col-6 mb-3">
                                                            <label class="form-label small fw-semibold">Nhóm Category:</label>
                                                            <select name="category" class="form-select" required style="border-radius: 8px;">
                                                                <option value="vocab" {{ $ach->category == 'vocab' ? 'selected' : '' }}>Từ Vựng</option>
                                                                <option value="grammar" {{ $ach->category == 'grammar' ? 'selected' : '' }}>Ngữ Pháp</option>
                                                                <option value="review" {{ $ach->category == 'review' ? 'selected' : '' }}>Ôn Tập</option>
                                                                <option value="streak" {{ $ach->category == 'streak' ? 'selected' : '' }}>Kỷ Luật</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Loại Điều Kiện (Condition Type):</label>
                                                        <select name="condition_type" class="form-select" required style="border-radius: 8px; font-size: 13px;">
                                                            <option value="vocab_studied" {{ $ach->condition_type == 'vocab_studied' ? 'selected' : '' }}>Số từ đã học (vocab_studied)</option>
                                                            <option value="vocab_mastered" {{ $ach->condition_type == 'vocab_mastered' ? 'selected' : '' }}>Số từ thành thạo (vocab_mastered)</option>
                                                            <option value="grammar_lessons_done" {{ $ach->condition_type == 'grammar_lessons_done' ? 'selected' : '' }}>Bài ngữ pháp hoàn thành 3/3 (grammar_lessons_done)</option>
                                                            <option value="reviews_done" {{ $ach->condition_type == 'reviews_done' ? 'selected' : '' }}>Tổng số lượt ôn tập (reviews_done)</option>
                                                            <option value="retention_rate" {{ $ach->condition_type == 'retention_rate' ? 'selected' : '' }}>Tỷ lệ nhớ tối thiểu % (retention_rate)</option>
                                                            <option value="streak_days" {{ $ach->condition_type == 'streak_days' ? 'selected' : '' }}>Số ngày học liên tục (streak_days)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Con Số Mục Tiêu (Target Value):</label>
                                                        <input type="number" name="target_value" class="form-control font-monospace" min="1" value="{{ $ach->target_value }}" required style="border-radius: 8px;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
                                                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #2563eb; border: none;">Lưu Thay Đổi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
