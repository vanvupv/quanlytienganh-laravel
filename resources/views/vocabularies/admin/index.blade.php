@extends('vocabularies.vocabulary')

@section('title', 'Quản Lý Lộ Trình Từ Vựng (Admin)')

@section('content_voca')
<div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">
    
    <!-- Premium Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">⚙️ Quản Lý Lộ Trình Từ Vựng</h1>
            <p class="text-muted mb-0">Hệ thống hóa lộ trình, cấu trúc cây phát triển từ vựng và đa tầng nghĩa</p>
        </div>
        <div>
            <a href="{{ route('vocabulary.index') }}" class="btn btn-outline-primary px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px; font-size: 14.5px;">
                🌳 Xem Lộ Trình Học Viên
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

    <!-- Admin Operations Hub Tab navigation -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: white;">
        <div class="card-header bg-white border-bottom border-light p-3">
            <ul class="nav nav-pills card-header-pills gap-2" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold px-4 py-2" id="stages-tab" data-bs-toggle="pill" data-bs-target="#stages-pane" type="button" role="tab" style="border-radius: 10px;">
                        📌 1. Quản Lý Giai Đoạn (Stages)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold px-4 py-2" id="words-tab" data-bs-toggle="pill" data-bs-target="#words-pane" type="button" role="tab" style="border-radius: 10px;">
                        📝 2. Quản Lý Từ Gốc (Base Words)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold px-4 py-2" id="properties-tab" data-bs-toggle="pill" data-bs-target="#properties-pane" type="button" role="tab" style="border-radius: 10px;">
                        🌳 3. Quản Lý Cành Lá (Nét Nghĩa & POS)
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="adminTabsContent">
                
                <!-- PANELS 1: STAGES -->
                <div class="tab-pane fade show active" id="stages-pane" role="tabpanel" aria-labelledby="stages-tab">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="p-4 rounded-4 border bg-light" style="border-radius: 16px; text-align: left;">
                                <h5 class="fw-bold text-dark mb-3">➕ Thêm Giai Đoạn Mới</h5>
                                <form action="{{ route('vocabulary.admin.stages.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Tên Giai Đoạn:</label>
                                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Giai đoạn 1" required style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Mô Tả Giai Đoạn:</label>
                                        <input type="text" name="description" class="form-control" placeholder="Ví dụ: Nền tảng cơ bản" style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Thứ Tự Lộ Trình (Order):</label>
                                        <input type="number" name="order" class="form-control" value="1" required style="border-radius: 8px;">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 8px; background-color: #2563eb;">
                                        💾 Lưu Giai Đoạn
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <h5 class="fw-bold text-dark mb-3" style="text-align: left;">📋 Danh Sách Giai Đoạn Hiện Tại</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border text-start" style="border-radius: 12px; overflow: hidden; font-size: 13.5px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 10%;">ID</th>
                                            <th style="width: 25%;">Tên Giai Đoạn</th>
                                            <th style="width: 35%;">Mô Tả</th>
                                            <th style="width: 15%;">Thứ Tự</th>
                                            <th style="width: 15%; text-align: center;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stages as $stage)
                                            <tr>
                                                <td class="fw-bold">{{ $stage->id }}</td>
                                                <td class="fw-bold text-primary">{{ $stage->name }}</td>
                                                <td>{{ $stage->description }}</td>
                                                <td class="font-monospace fw-bold">{{ $stage->order }}</td>
                                                <td style="text-align: center;">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editStageModal-{{ $stage->id }}">✏️</button>
                                                        <form action="{{ route('vocabulary.admin.stages.destroy', $stage->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa giai đoạn này? Tất cả cành lá gắn với giai đoạn cũng sẽ bị xóa!')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Stage -->
                                            <div class="modal fade" id="editStageModal-{{ $stage->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content" style="border-radius: 16px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">✏️ Sửa Giai Đoạn #{{ $stage->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('vocabulary.admin.stages.update', $stage->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Tên Giai Đoạn:</label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $stage->name }}" required style="border-radius: 8px;">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Mô Tả:</label>
                                                                    <input type="text" name="description" class="form-control" value="{{ $stage->description }}" style="border-radius: 8px;">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Thứ Tự:</label>
                                                                    <input type="number" name="order" class="form-control" value="{{ $stage->order }}" required style="border-radius: 8px;">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary" style="background-color: #2563eb;">Lưu Thay Đổi</button>
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

                <!-- PANELS 2: BASE WORDS -->
                <div class="tab-pane fade" id="words-pane" role="tabpanel" aria-labelledby="words-tab">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="p-4 rounded-4 border bg-light" style="border-radius: 16px; text-align: left;">
                                <h5 class="fw-bold text-dark mb-3">➕ Thêm Từ Gốc Mới</h5>
                                <form action="{{ route('vocabulary.admin.words.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Từ Gốc (English Word):</label>
                                        <input type="text" name="word" class="form-control font-monospace fw-bold" placeholder="Ví dụ: Run" required style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Phiên âm Anh (UK Phonetic):</label>
                                        <input type="text" name="uk_phonetic" class="form-control font-monospace" placeholder="Ví dụ: /rʌn/" style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Phiên âm Mỹ (US Phonetic):</label>
                                        <input type="text" name="us_phonetic" class="form-control font-monospace" placeholder="Ví dụ: /rʌn/" style="border-radius: 8px;">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 8px; background-color: #2563eb;">
                                        💾 Lưu Từ Gốc
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <h5 class="fw-bold text-dark mb-3" style="text-align: left;">📋 Danh Sách Từ Gốc</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border text-start" style="border-radius: 12px; overflow: hidden; font-size: 13.5px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 10%;">ID</th>
                                            <th style="width: 25%;">Từ Gốc</th>
                                            <th style="width: 25%;">Phiên Âm UK</th>
                                            <th style="width: 25%;">Phiên Âm US</th>
                                            <th style="width: 15%; text-align: center;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($words as $word)
                                            <tr>
                                                <td class="fw-bold">{{ $word->id }}</td>
                                                <td class="fw-bold text-primary font-monospace" style="font-size: 15px;">{{ $word->word }}</td>
                                                <td class="font-monospace text-muted">{{ $word->uk_phonetic }}</td>
                                                <td class="font-monospace text-muted">{{ $word->us_phonetic }}</td>
                                                <td style="text-align: center;">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editWordModal-{{ $word->id }}">✏️</button>
                                                        <form action="{{ route('vocabulary.admin.words.destroy', $word->id) }}" method="POST" onsubmit="return confirm('Bạn có thực sự chắc muốn xóa từ gốc này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Word -->
                                            <div class="modal fade" id="editWordModal-{{ $word->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content" style="border-radius: 16px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">✏️ Sửa Từ Gốc: {{ $word->word }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('vocabulary.admin.words.update', $word->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Từ Gốc:</label>
                                                                    <input type="text" name="word" class="form-control font-monospace fw-bold" value="{{ $word->word }}" required style="border-radius: 8px;">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Phiên âm UK:</label>
                                                                    <input type="text" name="uk_phonetic" class="form-control font-monospace" value="{{ $word->uk_phonetic }}" style="border-radius: 8px;">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold">Phiên âm US:</label>
                                                                    <input type="text" name="us_phonetic" class="form-control font-monospace" value="{{ $word->us_phonetic }}" style="border-radius: 8px;">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary" style="background-color: #2563eb;">Lưu Thay Đổi</button>
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

                <!-- PANELS 3: PROPERTIES (CÀNH LÁ) -->
                <div class="tab-pane fade" id="properties-pane" role="tabpanel" aria-labelledby="properties-tab">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="p-4 rounded-4 border bg-light" style="border-radius: 16px; text-align: left;">
                                <h5 class="fw-bold text-dark mb-3">➕ Thêm Cành Lá Phát Triển</h5>
                                <form action="{{ route('vocabulary.admin.properties.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Chọn Từ Gốc:</label>
                                        <select name="word_id" class="form-select" required style="border-radius: 8px;">
                                            @foreach($words as $w)
                                                <option value="{{ $w->id }}">{{ $w->word }} (UK: {{ $w->uk_phonetic }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Giai Đoạn Xuất Hiện:</label>
                                        <select name="stage_id" class="form-select" required style="border-radius: 8px;">
                                            @foreach($stages as $st)
                                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->description }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Từ Loại (POS):</label>
                                        <select name="part_of_speech" class="form-select" required style="border-radius: 8px;">
                                            <option value="Noun">Noun (Danh từ)</option>
                                            <option value="Verb">Verb (Động từ)</option>
                                            <option value="Adjective">Adjective (Tính từ)</option>
                                            <option value="Adverb">Adverb (Trạng từ)</option>
                                            <option value="Preposition">Preposition (Giới từ)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Ý Nghĩa ở Giai Đoạn này:</label>
                                        <input type="text" name="meaning" class="form-control" placeholder="Ví dụ: Chạy, Điều hành" required style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Câu Ví Dụ Tiếng Anh:</label>
                                        <input type="text" name="example_sentence" class="form-control font-monospace" placeholder="Ví dụ: She runs a business." style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bản Dịch Ví Dụ Tiếng Việt:</label>
                                        <input type="text" name="example_translation" class="form-control" placeholder="Ví dụ: Cô ấy điều hành một doanh nghiệp." style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Chủ Đề (Topic):</label>
                                        <input type="text" name="topic" class="form-control" placeholder="Ví dụ: Business, Family" style="border-radius: 8px;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Tài Liệu/Reference:</label>
                                        <input type="text" name="reference" class="form-control" placeholder="Ví dụ: Oxford 3000" style="border-radius: 8px;">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 8px; background-color: #2563eb;">
                                        💾 Lưu Thuộc Tính
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <h5 class="fw-bold text-dark mb-3" style="text-align: left;">📋 Danh Sách Cành Lá (Nét Nghĩa/Từ Loại)</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border text-start" style="border-radius: 12px; overflow: hidden; font-size: 12.5px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Từ gốc</th>
                                            <th>Giai đoạn</th>
                                            <th>Loại</th>
                                            <th>Ý Nghĩa</th>
                                            <th>Ví dụ Ngữ Cảnh</th>
                                            <th>Chủ đề / Ref</th>
                                            <th style="text-align: center;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($properties as $prop)
                                            <tr>
                                                <td class="fw-bold">{{ $prop->id }}</td>
                                                <td class="fw-bold text-primary font-monospace">{{ $prop->word->word }}</td>
                                                <td><span class="badge bg-secondary" style="font-size: 10px;">{{ $prop->stage->name }}</span></td>
                                                <td class="fw-bold text-dark">{{ $prop->part_of_speech }}</td>
                                                <td><strong>{{ $prop->meaning }}</strong></td>
                                                <td class="small">
                                                    @if($prop->example_sentence)
                                                        <em class="font-monospace text-muted">"{{ $prop->example_sentence }}"</em><br>
                                                        <span>👉 {{ $prop->example_translation }}</span>
                                                    @else
                                                        <span class="text-muted italic">[Không có]</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($prop->topic) <span class="badge bg-info text-dark d-block mb-1" style="font-size: 9px;">{{ $prop->topic }}</span> @endif
                                                    @if($prop->reference) <span class="badge bg-success d-block" style="font-size: 9px;">{{ $prop->reference }}</span> @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPropModal-{{ $prop->id }}">✏️</button>
                                                        <form action="{{ route('vocabulary.admin.properties.destroy', $prop->id) }}" method="POST" onsubmit="return confirm('Bạn có thực sự chắc muốn xóa thuộc tính này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Property -->
                                            <div class="modal fade" id="editPropModal-{{ $prop->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content" style="border-radius: 16px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">✏️ Sửa Nét Nghĩa #{{ $prop->id }} ({{ $prop->word->word }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('vocabulary.admin.properties.update', $prop->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Từ Gốc:</label>
                                                                        <select name="word_id" class="form-select" required style="border-radius: 8px;">
                                                                            @foreach($words as $w)
                                                                                <option value="{{ $w->id }}" {{ $w->id == $prop->word_id ? 'selected' : '' }}>{{ $w->word }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Giai Đoạn:</label>
                                                                        <select name="stage_id" class="form-select" required style="border-radius: 8px;">
                                                                            @foreach($stages as $st)
                                                                                <option value="{{ $st->id }}" {{ $st->id == $prop->stage_id ? 'selected' : '' }}>{{ $st->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Từ Loại (POS):</label>
                                                                        <input type="text" name="part_of_speech" class="form-control" value="{{ $prop->part_of_speech }}" required style="border-radius: 8px;">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Ý Nghĩa:</label>
                                                                        <input type="text" name="meaning" class="form-control" value="{{ $prop->meaning }}" required style="border-radius: 8px;">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label small fw-semibold">Ví Dụ Tiếng Anh:</label>
                                                                        <input type="text" name="example_sentence" class="form-control font-monospace" value="{{ $prop->example_sentence }}" style="border-radius: 8px;">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label small fw-semibold">Dịch Ví Dụ Tiếng Việt:</label>
                                                                        <input type="text" name="example_translation" class="form-control" value="{{ $prop->example_translation }}" style="border-radius: 8px;">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Chủ Đề (Topic):</label>
                                                                        <input type="text" name="topic" class="form-control" value="{{ $prop->topic }}" style="border-radius: 8px;">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Tài Liệu Tham Khảo (Reference):</label>
                                                                        <input type="text" name="reference" class="form-control" value="{{ $prop->reference }}" style="border-radius: 8px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary" style="background-color: #2563eb;">Lưu Thay Đổi</button>
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
        </div>
    </div>

</div>

<!-- Auto activation of tab based on URL hashes if needed -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var triggerTabList = [].slice.call(document.querySelectorAll('#adminTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    });
</script>
@endsection
