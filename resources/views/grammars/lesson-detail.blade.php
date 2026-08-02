@extends('grammars.grammar')

@section('title', 'Grammar')

@section('content_grammar')
  <!-- Main Content -->
  <div class="container-fluid">
    <!-- Sleek Navigation Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 p-3 bg-white shadow-sm border border-light rounded-4">
      <div>
        <a href="{{ route('grammar.index') }}" class="btn btn-outline-primary fw-bold px-3 py-2 d-inline-flex align-items-center gap-2 transition-all hover-back-btn" style="border-radius: 10px; font-size: 13.5px; text-decoration: none;">
          ← Quay lại Lộ trình
        </a>
      </div>
      
      <div class="d-flex align-items-center gap-2">
        @if($prevLesson)
          <a href="{{ route('grammar.lesson.show', $prevLesson->id) }}" class="btn btn-light fw-bold px-3.5 py-2 text-secondary d-inline-flex align-items-center gap-1.5 border" style="border-radius: 10px; font-size: 13.5px; text-decoration: none;" title="{{ $prevLesson->title }}">
            ◀ Bài trước
          </a>
        @else
          <button class="btn btn-light fw-bold px-3.5 py-2 text-muted border" style="border-radius: 10px; font-size: 13.5px; cursor: not-allowed; opacity: 0.5;" disabled>
            ◀ Bài trước
          </button>
        @endif

        <span class="badge bg-secondary-subtle text-secondary px-3 py-2.5 rounded-pill fw-bold border" style="font-size: 12.5px; background-color: #f1f5f9; color: #475569;">
          {{ $lesson->title }}
        </span>

        @if($nextLesson)
          <a href="{{ route('grammar.lesson.show', $nextLesson->id) }}" class="btn btn-primary fw-bold px-3.5 py-2 text-white d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px; font-size: 13.5px; text-decoration: none; background: #2563eb; border-color: #2563eb;" title="{{ $nextLesson->title }}">
            Bài sau ▶
          </a>
        @else
          <button class="btn btn-primary fw-bold px-3.5 py-2 text-white shadow-sm" style="border-radius: 10px; font-size: 13.5px; cursor: not-allowed; opacity: 0.5; background: #94a3b8; border-color: #94a3b8;" disabled>
            Bài sau ▶
          </button>
        @endif
      </div>
    </div>

    <!--  -->
    <div class="card- ">
      <div class="row">
        <div class="col-lg-12">
          <div class="section__nguphap">
            <!--  -->
            <!-- Hàng 1: Thông tin bài học & Tiến độ học tập (Ngang hàng nhau) -->
            <div class="row g-4 mb-4">
              <!-- Cột trái: Thông tin bài học -->
              <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
                  <h5 class="card-title mb-3 text-primary fw-bold" style="text-align: left;">
                    🧩 Thông tin bài học
                  </h5>

                  <div class="lesson-info text-start">
                    <div class="mb-2">
                      <span class="fw-semibold text-muted" style="font-size: 13px;">Danh mục:</span>
                      <div class="badge bg-primary px-3 py-2 mt-1 d-inline-block" style="font-size: 12px; border-radius: 8px;">
                        {{ $lesson->category->name ?? 'Chưa có danh mục' }}
                      </div>
                    </div>

                    <div class="mb-2">
                      <span class="fw-semibold text-muted" style="font-size: 13px;">Chủ đề:</span>
                      <div class="badge bg-info text-dark px-3 py-2 mt-1 d-inline-block" style="font-size: 12px; border-radius: 8px;">
                        {{ $lesson->topic->title ?? 'Chưa có chủ đề' }}
                      </div>
                    </div>

                    <div class="mb-2">
                      <span class="fw-semibold text-muted" style="font-size: 13px;">Bài học:</span>
                      <div class="badge bg-success px-3 py-2 mt-1 d-inline-block" style="font-size: 12px; border-radius: 8px;">
                        {{ $lesson->title }}
                      </div>
                    </div>
                  </div>

                  <hr class="my-3">

                  <!-- Chức năng Ôn tập & Xem lịch sử bài ôn -->
                  <div class="d-flex flex-column gap-2 mb-3">
                    <a href="{{ route('grammar.lessons.review', $lesson->id) }}" class="btn btn-success fw-bold py-2.5 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px; background-color: #10b981; border-color: #10b981; color: white; text-decoration: none; font-size: 14px;">
                      🔄 Vào Ôn Tập & Củng Cố
                    </a>
                    
                    @if($lesson->reviews->count() > 0)
                      @php
                        $latestReview = $lesson->reviews()->latest()->first();
                      @endphp
                      <a href="{{ route('grammar.reviews.show', $latestReview->id) }}" class="btn btn-outline-secondary fw-bold py-2 d-flex align-items-center justify-content-center gap-2 text-dark" style="border-radius: 10px; border-color: #cbd5e1; text-decoration: none; font-size: 13.5px;">
                        📊 Xem Bài Ôn Gần Nhất ({{ $latestReview->score }}đ)
                      </a>

                      <button type="button" class="btn btn-link text-primary fw-semibold small py-1 text-decoration-none d-flex align-items-center justify-content-center gap-1 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#reviewHistoryModal" style="font-size: 12.5px;">
                        📜 Lịch sử ôn tập ({{ $lesson->reviews->count() }} lần)
                      </button>
                    @endif
                  </div>


                  <!-- Quá trình học & Hoàn thành bài học -->
                  <div class="d-flex flex-column gap-2 mb-3 mt-3">
                    <button type="button" class="btn btn-outline-primary fw-bold py-2 d-flex align-items-center justify-content-center gap-2" id="btnLearningProcess" data-bs-toggle="modal" data-bs-target="#learningProcessModal" style="border-radius: 10px; font-size: 13.5px;">
                      📅 Quá trình học
                    </button>
                    
                    <button type="button" class="btn {{ $isCompleted ? 'btn-success' : 'btn-secondary' }} fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2 shadow-sm" id="btnCompleteLesson" style="border-radius: 10px; font-size: 14px; {{ $isCompleted ? 'cursor: not-allowed;' : '' }}" {{ $isCompleted ? 'disabled' : '' }}>
                      🎓 <span id="btnCompleteText">{{ $isCompleted ? '✓ Đã Hoàn Thành Bài Học' : 'Hoàn Thành Bài Học' }}</span>
                    </button>

                    <button type="button" class="btn btn-outline-danger fw-bold py-2 d-flex align-items-center justify-content-center gap-2" id="btnResetProgress" style="border-radius: 10px; font-size: 13.5px;">
                      🔄 Reset Tiến Trình
                    </button>
                  </div>

                  <div class="text-muted small text-start mt-auto">
                    <strong>Ghi chú:</strong> Bài học này thuộc chủ đề
                    <em>{{ $lesson->topic->title ?? 'Chưa có chủ đề' }}</em>
                    trong danh mục
                    <em>{{ $lesson->category->name ?? 'Chưa có danh mục' }}</em>.
                  </div>
                </div>
              </div>

              <!-- Cột phải: Tiến độ học tập -->
              <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
                  <h5 class="card-title mb-3 text-dark fw-bold d-flex align-items-center gap-2" style="font-size: 15px; margin: 0; text-align: left;">
                    📊 Tiến Độ Học & Ôn Tập Của Bạn
                  </h5>
                  
                  <div class="row g-2 mb-3 mt-1">
                    <div class="col-4 text-center p-2 rounded" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                      <div class="small text-muted fw-semibold" style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px;">Số buổi</div>
                      <div class="fw-extrabold text-dark" style="font-size: 16px;">{{ $lesson->studyLogs->sum('sessions_count') }}</div>
                    </div>
                    <div class="col-4 text-center p-2 rounded" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                      <div class="small text-muted fw-semibold" style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px;">Học bài</div>
                      <div class="fw-extrabold text-primary" style="font-size: 16px;">{{ $lesson->studyLogs->sum('study_time') }} phút</div>
                    </div>
                    <div class="col-4 text-center p-2 rounded" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                      <div class="small text-muted fw-semibold" style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px;">Ôn tập</div>
                      <div class="fw-extrabold text-success" style="font-size: 16px;">{{ $lesson->studyLogs->sum('review_time') }} phút</div>
                    </div>
                  </div>

                  <hr class="my-2" style="border-color: #e2e8f0;">

                  <div class="d-flex flex-column gap-3 mt-2" style="text-align: left; max-height: 180px; overflow-y: auto; scrollbar-width: thin; padding-right: 5px;">
                    @forelse($lesson->sectionReviews as $sec)
                      <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="fw-bold text-secondary" style="font-size: 12px;">⚡ {{ $sec->section_name }}</span>
                          <span class="badge rounded-pill px-2 py-0.5" 
                                style="font-size: 9.5px; font-weight: bold; background-color: {{ $sec->status == 'Đạt' ? '#e6fdf0' : '#fffbeb' }}; color: {{ $sec->status == 'Đạt' ? '#15803d' : '#a16207' }} !important; border: 1px solid {{ $sec->status == 'Đạt' ? '#86efac' : '#fca5a5' }} !important;">
                            {{ $sec->status }}
                          </span>
                        </div>
                        <div class="small text-muted" style="font-size: 11px; line-height: 1.4;">
                          • Đã ôn: <strong class="text-dark">{{ $sec->review_count }} lần</strong> ({{ $sec->review_time }}p / {{ $sec->min_review_time }}p tối thiểu)<br>
                          • Điểm ôn gần nhất: 
                          <strong class="{{ $sec->review_score >= 80 ? 'text-success' : 'text-danger' }}">
                            {{ $sec->review_score ?: '0' }} / 100 điểm
                          </strong>
                        </div>
                      </div>
                    @empty
                      <p class="text-muted small italic text-center mb-0" style="font-size: 11.5px;">Chưa có dữ liệu thống kê học tập cho bài này.</p>
                    @endforelse
                  </div>
                </div>
              </div>
            </div>

            <!-- Hàng 2: Mục lục & Nội dung bài học (Ngang hàng nhau) -->
            <div class="row g-4">
              <!-- Cột trái: Danh Mục Kiến Thức Đánh Dấu (col-lg-4) -->
              <div class="col-lg-4">
                @if($isHtmlFormat)
                  <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white; min-height: 450px;">
                    <h5 class="card-title mb-3 text-dark fw-bold d-flex align-items-center gap-2 border-bottom pb-3" style="font-size: 15px; margin: 0; text-align: left;">
                      📌 Danh Mục Kiến Thức Đánh Dấu
                    </h5>
                    <div id="toc-list" class="d-flex flex-column" style="max-height: 550px; overflow-y: auto; scrollbar-width: thin; padding-right: 5px;">
                      <!-- Dynamic Table of Contents items go here -->
                    </div>
                  </div>
                @else
                  <div class="card border-0 shadow-sm p-4 h-100 text-center text-muted" style="border-radius: 16px; background: white; display: flex; justify-content: center; align-items: center;">
                    <p class="small italic mb-0">Danh mục chỉ khả dụng cho giáo trình định dạng tương tác.</p>
                  </div>
                @endif
              </div>

              <!-- Cột phải: Nội dung chính (col-lg-8) -->
              <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; position: relative; min-height: 450px;">
                  <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="fw-bold text-primary mb-0" style="font-size: 22px; text-align: left;">
                      📖 {{ $lesson->title }}
                    </h2>
                    @if($isHtmlFormat)
                      <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary fw-bold px-3 py-1.5" id="btnToggleEdit" style="border-radius: 8px;">
                          ✍️ Chế độ Biên tập
                        </button>
                        <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1.5 text-white d-none" id="btnSaveHtml" style="border-radius: 8px; background: #2563eb; border-color: #2563eb;">
                          💾 Lưu giáo trình
                        </button>
                      </div>
                    @endif
                  </div>

                  @if($isHtmlFormat)
                    <div class="row g-4">
                      <!-- Cột trái: Danh sách các khối kiến thức đã học dưới dạng badge -->
                      <div class="col-md-3 border-end">
                        <h6 class="fw-bold text-secondary mb-3 text-start d-flex align-items-center gap-1.5" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                          🎓 Đã Nắm Vững
                        </h6>
                        <div id="mastered-badges-list" class="d-flex flex-column gap-2.5 text-start" style="max-height: 500px; overflow-y: auto; scrollbar-width: thin; padding-right: 5px;">
                          <!-- Badges will be dynamically rendered here -->
                        </div>
                      </div>

                      <!-- Cột phải: Giáo trình tương tác -->
                      <div class="col-md-9">
                        <!-- HTML Interactive Textbook Canvas -->
                        <div id="lesson-html-content" class="textbook-canvas p-2 text-start text-dark" style="font-size: 15.5px; line-height: 1.7; min-height: 200px;">
                          @if($isHtmlFormat && isset($sections))
                            @foreach($sections as $sec)
                              <div class="grammar-section mb-4" data-section-id="{{ $sec['id'] }}">
                                <h4 class="fw-bold text-dark mb-3 border-bottom pb-2" style="font-size: 17px;">{{ $sec['title'] }}</h4>
                                <div class="section-html-content">
                                  {!! $sec['html'] !!}
                                </div>
                              </div>
                            @endforeach
                          @else
                            {!! $lesson->content !!}
                          @endif
                        </div>
                      </div>
                    </div>

                    <!-- Floating Tooltip Popover for Highlighter Tool -->
                    <div id="highlighter-popover" class="shadow-lg border p-3 bg-white rounded-3 position-absolute d-none" style="z-index: 9999; min-width: 260px; border-color: #e2e8f0 !important;">
                      <div class="d-flex flex-column gap-2 text-start">
                        <div class="fw-bold text-secondary small" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">1. Chọn màu tô</div>
                        <div class="d-flex gap-2 mb-1" id="color-selectors">
                          <button type="button" class="color-dot btn-color-red bg-danger border-0 rounded-circle position-relative" data-color="red" style="width: 24px; height: 24px;" title="Đỏ - Công thức"></button>
                          <button type="button" class="color-dot btn-color-blue bg-primary border-0 rounded-circle position-relative" data-color="blue" style="width: 24px; height: 24px;" title="Xanh - Cách dùng"></button>
                          <button type="button" class="color-dot btn-color-green bg-success border-0 rounded-circle position-relative" data-color="green" style="width: 24px; height: 24px;" title="Lục - Ví dụ"></button>
                          <button type="button" class="color-dot btn-color-yellow bg-warning border-0 rounded-circle position-relative" data-color="yellow" style="width: 24px; height: 24px;" title="Vàng - Lưu ý"></button>
                        </div>
                        <div class="fw-bold text-secondary small" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">2. Phân loại kiến thức</div>
                        <input type="text" id="highlight-tag-name" class="form-control form-control-sm border shadow-sm" placeholder="Nhập tên tag hoặc dịch nghĩa" style="font-size: 12px; border-radius: 6px; padding: 6px 10px;">
                        <div class="d-flex justify-content-between align-items-center mt-1.5">
                          <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-2.5 d-none" id="btnDeleteHighlight" style="font-size: 11.5px; border-radius: 6px;">Xóa màu</button>
                          <div class="d-flex gap-2 ms-auto">
                            <button type="button" class="btn btn-sm btn-light fw-bold px-3" id="btnCancelHighlight" style="font-size: 11.5px; border-radius: 6px; border: 1px solid #cbd5e1;">Hủy</button>
                            <button type="button" class="btn btn-sm btn-primary fw-bold text-white px-3" id="btnApplyHighlight" style="font-size: 11.5px; border-radius: 6px; background: #2563eb; border-color: #2563eb;">Áp dụng</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    @php
                      $progressMap = $lesson->progress->keyBy('knowledge_key');
                    @endphp

                  <!-- Công thức -->
                  <div class="card border-0 bg-light p-3 mb-4 formula-card" style="border-radius: 12px;">
                    <h4 class="fw-bold text-dark mb-3" style="font-size: 16px; text-align: left;">🔹 Công thức</h4>
                    <div class="d-flex flex-column gap-3">
                      
                      <!-- Khẳng định -->
                      @php $key = 'formula:affirmative'; $prog = $progressMap->get($key); @endphp
                      <div class="knowledge-item p-3 border rounded-3 bg-white d-flex flex-column gap-2 text-start transition-all {{ $prog ? 'learned border-success bg-learned shadow-sm' : '' }}" data-knowledge-key="{{ $key }}" data-title="Công thức: Khẳng định" style="transition: all 0.3s ease;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                          <span class="fw-bold text-secondary" style="font-size: 13px;">Khẳng định (+)</span>
                          <div class="action-buttons d-flex align-items-center gap-2">
                            @if($prog)
                              <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill learned-badge" style="font-size: 11px; font-weight: bold; background-color: #e6fdf0; border: 1px solid #86efac !important; color: #15803d !important;">
                                ✔️ Đã học ({{ $prog->learned_date->format('d/m/Y') }})
                              </span>
                              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-progress border-0 p-1 rounded-circle transition-all" title="Hủy đánh dấu" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: transparent;">
                                🗑️
                              </button>
                            @else
                              <button type="button" class="btn btn-sm btn-outline-primary btn-mark-progress px-3 py-1 fw-bold" style="border-radius: 20px; font-size: 11.5px;">
                                ✍️ Đánh dấu học
                              </button>
                            @endif
                          </div>
                        </div>
                        <div class="formula-content font-monospace fw-semibold p-2.5 bg-light rounded text-dark" style="font-size: 13.5px; border-left: 3px solid #64748b;">
                          {{ $content['formula']['affirmative'] ?? '' }}
                        </div>
                        <div class="note-section {{ $prog && $prog->note ? '' : 'd-none' }}">
                          <div class="p-2.5 rounded-3 border-start border-success border-3 bg-white mt-1 text-muted small" style="background-color: #f0fdf4 !important; font-size: 12px; border-left: 3px solid #15803d !important;">
                            <strong>📝 Ghi chú:</strong> <span class="note-text">{{ $prog->note ?? '' }}</span>
                          </div>
                        </div>
                      </div>

                      <!-- Phủ định -->
                      @php $key = 'formula:negative'; $prog = $progressMap->get($key); @endphp
                      <div class="knowledge-item p-3 border rounded-3 bg-white d-flex flex-column gap-2 text-start transition-all {{ $prog ? 'learned border-success bg-learned shadow-sm' : '' }}" data-knowledge-key="{{ $key }}" data-title="Công thức: Phủ định" style="transition: all 0.3s ease;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                          <span class="fw-bold text-secondary" style="font-size: 13px;">Phủ định (-)</span>
                          <div class="action-buttons d-flex align-items-center gap-2">
                            @if($prog)
                              <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill learned-badge" style="font-size: 11px; font-weight: bold; background-color: #e6fdf0; border: 1px solid #86efac !important; color: #15803d !important;">
                                ✔️ Đã học ({{ $prog->learned_date->format('d/m/Y') }})
                              </span>
                              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-progress border-0 p-1 rounded-circle transition-all" title="Hủy đánh dấu" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: transparent;">
                                🗑️
                              </button>
                            @else
                              <button type="button" class="btn btn-sm btn-outline-primary btn-mark-progress px-3 py-1 fw-bold" style="border-radius: 20px; font-size: 11.5px;">
                                ✍️ Đánh dấu học
                              </button>
                            @endif
                          </div>
                        </div>
                        <div class="formula-content font-monospace fw-semibold p-2.5 bg-light rounded text-dark" style="font-size: 13.5px; border-left: 3px solid #64748b;">
                          {{ $content['formula']['negative'] ?? '' }}
                        </div>
                        <div class="note-section {{ $prog && $prog->note ? '' : 'd-none' }}">
                          <div class="p-2.5 rounded-3 border-start border-success border-3 bg-white mt-1 text-muted small" style="background-color: #f0fdf4 !important; font-size: 12px; border-left: 3px solid #15803d !important;">
                            <strong>📝 Ghi chú:</strong> <span class="note-text">{{ $prog->note ?? '' }}</span>
                          </div>
                        </div>
                      </div>

                      <!-- Nghi vấn -->
                      @php $key = 'formula:question'; $prog = $progressMap->get($key); @endphp
                      <div class="knowledge-item p-3 border rounded-3 bg-white d-flex flex-column gap-2 text-start transition-all {{ $prog ? 'learned border-success bg-learned shadow-sm' : '' }}" data-knowledge-key="{{ $key }}" data-title="Công thức: Nghi vấn" style="transition: all 0.3s ease;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                          <span class="fw-bold text-secondary" style="font-size: 13px;">Nghi vấn (?)</span>
                          <div class="action-buttons d-flex align-items-center gap-2">
                            @if($prog)
                              <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill learned-badge" style="font-size: 11px; font-weight: bold; background-color: #e6fdf0; border: 1px solid #86efac !important; color: #15803d !important;">
                                ✔️ Đã học ({{ $prog->learned_date->format('d/m/Y') }})
                              </span>
                              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-progress border-0 p-1 rounded-circle transition-all" title="Hủy đánh dấu" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: transparent;">
                                🗑️
                              </button>
                            @else
                              <button type="button" class="btn btn-sm btn-outline-primary btn-mark-progress px-3 py-1 fw-bold" style="border-radius: 20px; font-size: 11.5px;">
                                ✍️ Đánh dấu học
                              </button>
                            @endif
                          </div>
                        </div>
                        <div class="formula-content font-monospace fw-semibold p-2.5 bg-light rounded text-dark" style="font-size: 13.5px; border-left: 3px solid #64748b;">
                          {{ $content['formula']['question'] ?? '' }}
                        </div>
                        <div class="note-section {{ $prog && $prog->note ? '' : 'd-none' }}">
                          <div class="p-2.5 rounded-3 border-start border-success border-3 bg-white mt-1 text-muted small" style="background-color: #f0fdf4 !important; font-size: 12px; border-left: 3px solid #15803d !important;">
                            <strong>📝 Ghi chú:</strong> <span class="note-text">{{ $prog->note ?? '' }}</span>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <!-- Cách dùng -->
                  <div class="card border-0 bg-light p-3 mb-4 usage-card" style="border-radius: 12px;">
                    <h4 class="fw-bold text-dark mb-3" style="font-size: 16px; text-align: left;">📘 Cách dùng</h4>
                    <div class="d-flex flex-column gap-3">
                      @foreach($content['usage'] ?? [] as $index => $rule)
                        @php $key = 'usage:' . $index; $prog = $progressMap->get($key); @endphp
                        <div class="knowledge-item p-3 border rounded-3 bg-white d-flex flex-column gap-2 text-start transition-all {{ $prog ? 'learned border-success bg-learned shadow-sm' : '' }}" data-knowledge-key="{{ $key }}" data-title="Cách dùng {{ $loop->iteration }}" style="transition: all 0.3s ease;">
                          <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex gap-2.5 align-items-start">
                              <span class="badge bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px; font-size: 12px; margin-top: 1px; flex-shrink: 0;">{{ $loop->iteration }}</span>
                              <div class="fw-semibold text-dark text-wrap" style="font-size: 13.5px; line-height: 1.5;">
                                {{ $rule }}
                              </div>
                            </div>
                            <div class="action-buttons d-flex align-items-center gap-2 flex-shrink-0">
                              @if($prog)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill learned-badge" style="font-size: 11px; font-weight: bold; background-color: #e6fdf0; border: 1px solid #86efac !important; color: #15803d !important;">
                                  ✔️ Đã học ({{ $prog->learned_date->format('d/m/Y') }})
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-progress border-0 p-1 rounded-circle transition-all" title="Hủy đánh dấu" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: transparent;">
                                  🗑️
                                </button>
                              @else
                                <button type="button" class="btn btn-sm btn-outline-primary btn-mark-progress px-3 py-1 fw-bold" style="border-radius: 20px; font-size: 11.5px;">
                                  ✍️ Đánh dấu học
                                </button>
                              @endif
                            </div>
                          </div>
                          <div class="note-section {{ $prog && $prog->note ? '' : 'd-none' }}">
                            <div class="p-2.5 rounded-3 border-start border-success border-3 bg-white mt-1 text-muted small" style="background-color: #f0fdf4 !important; font-size: 12px; border-left: 3px solid #15803d !important;">
                              <strong>📝 Ghi chú:</strong> <span class="note-text">{{ $prog->note ?? '' }}</span>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>

                  <!-- Ví dụ -->
                  <div class="card border-0 bg-light p-3 mb-4 example-card" style="border-radius: 12px;">
                    <h4 class="fw-bold text-dark mb-3" style="font-size: 16px; text-align: left;">✏️ Ví dụ</h4>
                    <div class="d-flex flex-column gap-3">
                      @foreach($examples as $index => $ex)
                        @php $key = 'example:' . $index; $prog = $progressMap->get($key); @endphp
                        <div class="knowledge-item p-3 border rounded-3 bg-white d-flex flex-column gap-2 text-start transition-all {{ $prog ? 'learned border-success bg-learned shadow-sm' : '' }}" data-knowledge-key="{{ $key }}" data-title="Ví dụ {{ $loop->iteration }}" style="transition: all 0.3s ease;">
                          <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex flex-column gap-1 text-start">
                              <span class="font-monospace fw-bold text-primary" style="font-size: 14.5px;">{{ $ex['sentence'] }}</span>
                              <span class="text-secondary small">→ {{ $ex['note'] }}</span>
                            </div>
                            <div class="action-buttons d-flex align-items-center gap-2 flex-shrink-0">
                              @if($prog)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill learned-badge" style="font-size: 11px; font-weight: bold; background-color: #e6fdf0; border: 1px solid #86efac !important; color: #15803d !important;">
                                  ✔️ Đã học ({{ $prog->learned_date->format('d/m/Y') }})
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-progress border-0 p-1 rounded-circle transition-all" title="Hủy đánh dấu" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: transparent;">
                                  🗑️
                                </button>
                              @else
                                <button type="button" class="btn btn-sm btn-outline-primary btn-mark-progress px-3 py-1 fw-bold" style="border-radius: 20px; font-size: 11.5px;">
                                  ✍️ Đánh dấu học
                                </button>
                              @endif
                            </div>
                          </div>
                          <div class="note-section {{ $prog && $prog->note ? '' : 'd-none' }}">
                            <div class="p-2.5 rounded-3 border-start border-success border-3 bg-white mt-1 text-muted small" style="background-color: #f0fdf4 !important; font-size: 12px; border-left: 3px solid #15803d !important;">
                              <strong>📝 Ghi chú:</strong> <span class="note-text">{{ $prog->note ?? '' }}</span>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <section class="grammar__assessment__section">
      @include('grammars.grammar_assessment.grammar_assessment')
    </section>
  </div>

  <!-- Modal Đánh Dấu Tiến Trình Học Tập -->
  <div class="modal fade" id="markProgressModal" tabindex="-1" aria-labelledby="markProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header bg-primary text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
          <h5 class="modal-title fw-bold text-white" id="markProgressModalLabel" style="margin: 0;">✍️ Đánh Dấu Đã Học</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1); opacity: 0.8;"></button>
        </div>
        <form id="markProgressForm">
          @csrf
          <input type="hidden" name="knowledge_key" id="progress_knowledge_key">
          <div class="modal-body p-4" style="text-align: left; background-color: #f8fafc;">
            <div class="mb-3">
              <label for="progress_learned_date" class="form-label fw-bold text-secondary" style="font-size: 13px;">📅 Ngày học</label>
              <input type="date" class="form-control border-0 shadow-sm" id="progress_learned_date" name="learned_date" required style="border-radius: 8px; padding: 10px;">
            </div>
            <div class="mb-2">
              <label for="progress_note" class="form-label fw-bold text-secondary" style="font-size: 13px;">📝 Ghi chú buổi học</label>
              <textarea class="form-control border-0 shadow-sm" id="progress_note" name="note" rows="3" placeholder="Ví dụ: Đã học cách dùng này, nhớ phân biệt với..." style="border-radius: 8px; padding: 10px;"></textarea>
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            <button type="submit" class="btn btn-primary px-4 fw-bold text-white" style="border-radius: 8px;">Lưu tiến trình</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    .bg-learned {
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%) !important;
    }
    .knowledge-item {
      position: relative;
      border: 1px solid #e2e8f0;
      box-shadow: 0 1px 2px 0 rgba(0,0,0,0.02);
    }
    .knowledge-item:hover {
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
      border-color: #cbd5e1;
    }
    .knowledge-item.learned:hover {
      border-color: #86efac;
    }
    .transition-all {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-remove-progress:hover {
      background-color: #fee2e2 !important;
      transform: scale(1.1);
    }
    .btn-mark-progress {
      transition: all 0.2s ease-in-out;
    }
    .btn-mark-progress:hover {
      background-color: #2563eb !important;
      color: white !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Bản đồ tiến trình học tập lý thuyết toàn cục
      window.grammarProgressMap = @json($lesson->progress->keyBy('knowledge_key')) || {};
      window.grammarReviews = @json($lesson->reviews) || [];

      const markProgressModal = new bootstrap.Modal(document.getElementById('markProgressModal'));
      const markForm = document.getElementById('markProgressForm');
      let currentItemElement = null;

      // Event delegation for marking progress
      document.body.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-mark-progress')) {
          const item = e.target.closest('.knowledge-item');
          if (!item) return;

          currentItemElement = item;
          const key = item.getAttribute('data-knowledge-key');
          const title = item.getAttribute('data-title') || 'Phần kiến thức';

          document.getElementById('progress_knowledge_key').value = key;
          document.getElementById('markProgressModalLabel').innerText = '✍️ Đánh dấu đã học: ' + title;
          
          // Set default date to today
          const today = new Date().toISOString().split('T')[0];
          document.getElementById('progress_learned_date').value = today;
          document.getElementById('progress_note').value = '';

          markProgressModal.show();
        }
      });

      // Submit Form Progress
      markForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!currentItemElement) return;
        const key = document.getElementById('progress_knowledge_key').value;
        const dateVal = document.getElementById('progress_learned_date').value;
        const noteVal = document.getElementById('progress_note').value;

        const url = "{{ route('grammar.lesson.progress.save', $lesson->id) }}";

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({
            knowledge_key: key,
            learned_date: dateVal,
            note: noteVal
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            markProgressModal.hide();
            
            // Cập nhật map tiến trình toàn cục và render lại mục lục
            window.grammarProgressMap[key] = data.progress;
            updateTableOfContents();
          } else {
            alert('Có lỗi xảy ra: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi kết nối máy chủ.');
        });
      });

      // Remove Progress Clicked
      document.body.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('btn-remove-progress') || e.target.closest('.btn-remove-progress'))) {
          const button = e.target.classList.contains('btn-remove-progress') ? e.target : e.target.closest('.btn-remove-progress');
          const item = button.closest('.knowledge-item');
          if (!item) return;

          if (!confirm('Bạn có muốn hủy đánh dấu đã học cho phần kiến thức này không?')) {
            return;
          }

          const key = item.getAttribute('data-knowledge-key');
          deleteProgressByKey(key);
        }
      });

      // --- Phân hệ Giáo trình Tương tác & Đánh dấu ---
      const btnToggleEdit = document.getElementById('btnToggleEdit');
      const btnSaveHtml = document.getElementById('btnSaveHtml');
      const canvas = document.getElementById('lesson-html-content');
      const popover = document.getElementById('highlighter-popover');
      const btnCancelHighlight = document.getElementById('btnCancelHighlight');
      const btnApplyHighlight = document.getElementById('btnApplyHighlight');
      const btnDeleteHighlight = document.getElementById('btnDeleteHighlight');
      const tagNameInput = document.getElementById('highlight-tag-name');
      const colorDots = document.querySelectorAll('.color-dot');
      
      let isEditing = false;
      let selectedColor = 'red'; // Mặc định là đỏ (công thức)
      let currentRange = null;
      let currentEditingHighlight = null; // Thẻ highlight đang được chỉnh sửa

      // Hàm tạo ID độc nhất bền vững dựa trên thuộc tính của khối để tránh đổi vị trí bị lệch tiến trình
      function generateHighlightId(el) {
        const category = el.getAttribute('data-category') || '';
        const name = el.getAttribute('data-name') || '';
        const text = el.textContent.trim();
        const str = category + '|' + name + '|' + text;
        
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
          hash = (hash << 5) - hash + str.charCodeAt(i);
          hash |= 0;
        }
        return 'hl-' + Math.abs(hash).toString(36);
      }

      // Hàm gỡ bỏ trạng thái đã học của một khối kiến thức
      function deleteProgressByKey(key) {
        const url = "{{ route('grammar.lesson.progress.delete', $lesson->id) }}";

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({
            knowledge_key: key
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            delete window.grammarProgressMap[key];
            updateTableOfContents();
          } else {
            alert('Có lỗi xảy ra: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi kết nối máy chủ.');
        });
      }

      // Hàm cập nhật danh mục kiến thức đánh dấu (Table of Contents) bên trái hỗ trợ phân cấp cây (Tree hierarchy)
      function updateTableOfContents() {
        const tocList = document.getElementById('toc-list');
        const masteredList = document.getElementById('mastered-badges-list');
        if (!tocList || !canvas) return;

        tocList.innerHTML = '';
        if (masteredList) masteredList.innerHTML = '';

        // Xóa tất cả tích xanh cũ trên canvas để tránh bị nhân bản nhiều lần
        canvas.querySelectorAll('.mastered-checkmark-badge').forEach(badge => badge.remove());

        const highlights = Array.from(canvas.querySelectorAll('.grammar-highlight'));

        if (highlights.length === 0) {
          tocList.innerHTML = '<p class="text-muted small italic text-center mb-0 mt-2">Chưa có kiến thức nào được đánh dấu.</p>';
          if (masteredList) {
            masteredList.innerHTML = '<p class="text-muted small italic text-center mb-0 mt-2">Chưa học phần nào.</p>';
          }
          return;
        }

        // Đảm bảo tất cả phần tử có ID duy nhất và không bị trùng lặp gây vòng lặp vô hạn
        let idCounter = 0;
        highlights.forEach((el) => {
          el.id = 'hl-node-' + (idCounter++);
        });

        const sections = Array.from(canvas.querySelectorAll('.grammar-section'));
        let masteredCount = 0;

        // Hàm đệ quy vẽ cây phân cấp lên giao diện
        function renderNode(node, container, depth = 0) {
          const el = node.element;
          const category = el.getAttribute('data-category') || 'red';
          const name = el.getAttribute('data-name') || 'Đánh dấu';
          const text = el.textContent.trim();

          const item = document.createElement('div');
          // Gắn class knowledge-item và các thuộc tính tương thích với hệ thống Đánh dấu đã học cũ
          item.className = 'toc-item knowledge-item p-2 rounded-3 text-start small mb-1.5 d-flex align-items-center justify-content-between border';
          
          // Thụt lề (indentation) theo cấp độ sâu của node
          const indent = depth * 12;
          item.style.cssText = `font-size: 12px; cursor: pointer; transition: all 0.2s; background: white; border-color: #f1f5f9; margin-left: ${indent}px;`;

          const dataId = generateHighlightId(el);
          item.setAttribute('data-knowledge-key', dataId);
          item.setAttribute('data-title', name);

          let badgeColor = '#ef4444'; 
          let badgeBg = '#fee2e2';
          if (category === 'blue') { badgeColor = '#3b82f6'; badgeBg = '#dbeafe'; }
          else if (category === 'green') { badgeColor = '#10b981'; badgeBg = '#dcfce7'; }
          else if (category === 'yellow') { badgeColor = '#f59e0b'; badgeBg = '#fef9c3'; }

          // Biểu tượng nhánh cho con
          const connector = depth > 0 ? '<span class="text-muted small fw-bold" style="font-size: 10px; margin-right: 2px;">↳</span>' : '';

          // Trạng thái đã học hay chưa
          const isLearned = window.grammarProgressMap && window.grammarProgressMap[dataId];
          let statusHtml = '';
          if (isLearned) {
            masteredCount++;
            const progressInfo = window.grammarProgressMap[dataId];
            const noteText = progressInfo.note ? `\nGhi chú: ${progressInfo.note}` : '';
            statusHtml = `
              <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5 rounded-pill" style="font-size: 9px; font-weight: bold; background-color: #e6fdf0; border-color: #86efac !important; color: #15803d !important;" title="Đã học ngày ${progressInfo.learned_date || ''}${noteText}">
                  ✔️ Đã học
                </span>
                <button type="button" class="btn btn-link btn-remove-progress p-0 border-0 text-danger lh-1" title="Hủy đánh dấu học" style="font-size: 11px; text-decoration: none;">
                  🗑️
                </button>
              </div>
            `;

            // Thêm badge vào cột trái của col-lg-8
            if (masteredList) {
              const badgeItem = document.createElement('div');
              badgeItem.className = 'badge-item p-2 rounded-3 text-start small border d-flex flex-column gap-1 transition-all';
              badgeItem.style.cssText = `font-size: 11px; cursor: pointer; background-color: ${badgeBg}; border-color: ${badgeColor}; color: #1e293b; margin-bottom: 8px;`;
              
              badgeItem.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                  <span class="fw-bold text-truncate" style="max-width: 85%; font-size: 11.5px;">${name}</span>
                  <span class="badge rounded-circle p-0 flex-shrink-0" style="width: 6px; height: 6px; background-color: ${badgeColor};"></span>
                </div>
                <div class="text-muted text-truncate font-monospace" style="font-size: 9px;">${text}</div>
                <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 9px; color: #64748b;">
                  <span>📅 ${progressInfo.learned_date || ''}</span>
                  <span class="text-danger btn-remove-progress-badge text-decoration-underline" data-key="${dataId}" title="Hủy đánh dấu" style="cursor: pointer;">Hủy học</span>
                </div>
              `;

              // Click để cuộn tới khối
              badgeItem.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-progress-badge')) {
                  e.stopPropagation();
                  if (confirm('Bạn có muốn hủy đánh dấu đã học cho phần kiến thức này không?')) {
                    deleteProgressByKey(dataId);
                  }
                  return;
                }
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.style.transform = 'scale(1.03)';
                el.style.boxShadow = `0 0 10px ${badgeColor}`;
                setTimeout(() => {
                  el.style.transform = '';
                  el.style.boxShadow = '';
                }, 1000);
              });

              masteredList.appendChild(badgeItem);
            }

            // [MỚI] Vẽ dấu tích xanh trực tiếp trên canvas bài học (chỉ khi không trong chế độ biên tập)
            if (!isEditing) {
              const checkBadge = document.createElement('span');
              checkBadge.className = 'mastered-checkmark-badge d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm';
              checkBadge.style.cssText = 'width: 15px; height: 15px; font-size: 8px; vertical-align: middle; line-height: 1; display: inline-flex; margin-left: 6px;';
              checkBadge.title = 'Đã nắm vững';
              checkBadge.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" style="width: 9px; height: 9px;">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              `;
              el.appendChild(checkBadge);
            }
          } else {
            statusHtml = `
              <button type="button" class="btn btn-xs btn-outline-primary btn-mark-progress px-2 py-0.5 fw-bold flex-shrink-0" style="border-radius: 12px; font-size: 10px;">
                + Đã học
              </button>
            `;
          }

          item.innerHTML = `
            <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1" style="cursor: pointer;">
              ${connector}
              <span class="rounded-circle d-inline-block flex-shrink-0" style="width: 7px; height: 7px; background-color: ${badgeColor};"></span>
              <div class="min-w-0 text-truncate" style="text-align: left;">
                <div class="fw-bold text-dark text-truncate" style="font-size: 11px;">${name}</div>
                <div class="text-muted text-truncate font-monospace" style="font-size: 9.5px;">${text}</div>
              </div>
            </div>
            ${statusHtml}
          `;

          // Hover item
          item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#f8fafc';
            item.style.borderColor = badgeColor;
          });
          item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
            item.style.borderColor = '#f1f5f9';
          });

          // Click vào phần text/icon bên trái để cuộn tới khối
          const leftClickArea = item.querySelector('.d-flex');
          leftClickArea.addEventListener('click', (e) => {
            e.stopPropagation();
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            el.style.transform = 'scale(1.03)';
            el.style.boxShadow = `0 0 10px ${badgeColor}`;
            setTimeout(() => {
              el.style.transform = '';
              el.style.boxShadow = '';
            }, 1000);
          });

          container.appendChild(item);

          // Vẽ đệ quy các con
          node.children.forEach(child => {
            renderNode(child, container, depth + 1);
          });
        }

        if (sections.length > 0) {
          sections.forEach(sec => {
            // Vẽ tiêu đề Section
            const secTitle = sec.querySelector('h4').innerText;
            const secHeader = document.createElement('div');
            secHeader.className = 'fw-bold text-secondary mt-3 mb-2 pb-1 border-bottom text-start';
            secHeader.style.fontSize = '12.5px';
            secHeader.innerText = secTitle;
            tocList.appendChild(secHeader);

            const secHighlights = Array.from(sec.querySelectorAll('.grammar-highlight'));
            if (secHighlights.length === 0) {
              const noItems = document.createElement('div');
              noItems.className = 'text-muted small italic text-start ps-2';
              noItems.style.fontSize = '11px';
              noItems.innerText = 'Chưa có kiến thức đánh dấu.';
              tocList.appendChild(noItems);
              return;
            }

            // Xây dựng cây phân cấp trong Section này
            const nodesMap = {};
            const roots = [];

            secHighlights.forEach(el => {
              nodesMap[el.id] = {
                id: el.id,
                element: el,
                children: []
              };
            });

            secHighlights.forEach(el => {
              const parentEl = el.parentElement.closest('.grammar-highlight');
              if (parentEl && secHighlights.includes(parentEl) && nodesMap[parentEl.id]) {
                nodesMap[parentEl.id].children.push(nodesMap[el.id]);
              } else {
                roots.push(nodesMap[el.id]);
              }
            });

            roots.forEach(root => {
              renderNode(root, tocList, 0);
            });
          });
        } else {
          // Dự phòng cho dữ liệu chưa phân phần
          const nodesMap = {};
          const roots = [];

          highlights.forEach(el => {
            nodesMap[el.id] = {
              id: el.id,
              element: el,
              children: []
            };
          });

          highlights.forEach(el => {
            const parentEl = el.parentElement.closest('.grammar-highlight');
            if (parentEl && nodesMap[parentEl.id]) {
              nodesMap[parentEl.id].children.push(nodesMap[el.id]);
            } else {
              roots.push(nodesMap[el.id]);
            }
          });

          roots.forEach(root => {
            renderNode(root, tocList, 0);
          });
        }

        if (masteredList && masteredCount === 0) {
          masteredList.innerHTML = '<p class="text-muted small italic text-center mb-0 mt-2" style="font-size: 11.5px;">Chưa học phần nào.</p>';
        }

        // [MỚI] Cập nhật trạng thái nút hoàn thành bài học động
        const isLessonCompleted = window.grammarProgressMap && window.grammarProgressMap['lesson_completed'];
        const btnCompleteLesson = document.getElementById('btnCompleteLesson');
        const btnCompleteText = document.getElementById('btnCompleteText');
        if (btnCompleteLesson) {
          btnCompleteLesson.setAttribute('data-total-highlights', highlights.length);
          btnCompleteLesson.setAttribute('data-mastered-count', masteredCount);

          if (isLessonCompleted) {
            btnCompleteLesson.className = 'btn btn-success fw-bold py-2.5 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm';
            if (btnCompleteText) btnCompleteText.innerText = '✓ Đã Hoàn Thành Bài Học';
            btnCompleteLesson.disabled = true;
            btnCompleteLesson.style.cursor = 'not-allowed';
          } else {
            if (masteredCount === highlights.length && highlights.length > 0) {
              btnCompleteLesson.className = 'btn btn-primary fw-bold py-2.5 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm animate-pulse-glow';
              if (btnCompleteText) btnCompleteText.innerText = '🎓 Hoàn Thành Bài Học';
              btnCompleteLesson.disabled = false;
              btnCompleteLesson.style.cursor = 'pointer';
            } else {
              btnCompleteLesson.className = 'btn btn-secondary fw-bold py-2.5 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm opacity-60';
              if (btnCompleteText) btnCompleteText.innerText = 'Hoàn Thành Bài Học';
              btnCompleteLesson.disabled = true;
              btnCompleteLesson.style.cursor = 'not-allowed';
              btnCompleteLesson.title = 'Bạn cần học hết tất cả các phần kiến thức mới có thể hoàn thành bài học.';
            }
          }
        }

        // [MỚI] Tải lại timeline quá trình học
        renderTimeline();
      }

      // [MỚI] Hàm vẽ timeline quá trình học động trong JS để lọc các key cũ/lỗi thời
      function renderTimeline() {
        const container = document.getElementById('timeline-container');
        if (!container) return;

        container.innerHTML = '';
        const timelineItems = [];

        // 1. Khởi động bài học
        timelineItems.push({
          date: new Date(0),
          html: `
            <span class="position-absolute translate-middle bg-primary rounded-circle" style="width: 12px; height: 12px; left: -26px; top: 8px;"></span>
            <div class="fw-bold text-dark">Khởi động bài học</div>
            <div class="text-muted small">Hệ thống ghi nhận bạn đã bắt đầu tiếp cận bài học này.</div>
          `
        });

        // 2. Các khối lý thuyết đã học (chỉ lấy các key đang thực sự tồn tại trên canvas)
        const highlights = Array.from(canvas.querySelectorAll('.grammar-highlight'));
        const activeKeys = new Set(highlights.map(el => generateHighlightId(el)));

        for (const key in window.grammarProgressMap) {
          if (key !== 'lesson_completed' && activeKeys.has(key)) {
            const prog = window.grammarProgressMap[key];
            const date = new Date(prog.learned_date || prog.created_at || Date.now());
            
            let dateStr = '';
            if (prog.learned_date) {
              const d = new Date(prog.learned_date);
              const day = String(d.getDate()).padStart(2, '0');
              const month = String(d.getMonth() + 1).padStart(2, '0');
              const year = d.getFullYear();
              dateStr = `${day}/${month}/${year}`;
            }

            timelineItems.push({
              date: date,
              html: `
                <span class="position-absolute translate-middle bg-success rounded-circle" style="width: 12px; height: 12px; left: -26px; top: 8px;"></span>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-success">✓ Đã học: ${prog.note || 'Khối lý thuyết'}</div>
                  <span class="text-muted small font-monospace">${dateStr}</span>
                </div>
                <div class="text-muted small">Học viên đã học và ghi nhớ phần kiến thức được tô màu này.</div>
              `
            });
          }
        }

        // 3. Các lượt ôn tập
        if (window.grammarReviews && window.grammarReviews.length > 0) {
          window.grammarReviews.forEach((rev, index) => {
            const date = new Date(rev.created_at);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const dateStr = `${hours}:${minutes} - ${day}/${month}/${year}`;

            const durationMins = Math.round(rev.duration_seconds / 60);

            timelineItems.push({
              date: date,
              html: `
                <span class="position-absolute translate-middle bg-warning rounded-circle" style="width: 12px; height: 12px; left: -26px; top: 8px;"></span>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-warning">🔄 Ôn tập lần ${index + 1}</div>
                  <span class="text-muted small font-monospace">${dateStr}</span>
                </div>
                <div class="text-muted small">
                  Đạt điểm số: <strong class="${rev.passed ? 'text-success' : 'text-danger'}">${rev.score}%</strong> 
                  (${rev.passed ? 'Đạt' : 'Chưa đạt'}). Thời gian làm bài: ${durationMins} phút.
                </div>
              `
            });
          });
        }

        // 4. Xác nhận hoàn thành bài học
        const completionInfo = window.grammarProgressMap && window.grammarProgressMap['lesson_completed'];
        if (completionInfo) {
          const date = new Date(completionInfo.learned_date || completionInfo.created_at || Date.now());
          let dateStr = '';
          if (completionInfo.learned_date) {
            const d = new Date(completionInfo.learned_date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            dateStr = `${day}/${month}/${year}`;
          }

          timelineItems.push({
            date: date,
            html: `
              <span class="position-absolute translate-middle bg-danger rounded-circle animate-pulse-glow" style="width: 16px; height: 16px; left: -28px; top: 8px; border: 3px solid white;"></span>
              <div class="d-flex justify-content-between align-items-center">
                <div class="fw-extrabold text-danger" style="font-size: 15px;">🎓 HOÀN THÀNH BÀI HỌC!</div>
                <span class="text-muted small font-monospace fw-bold">${dateStr}</span>
              </div>
              <div class="text-muted small">Chúc mừng bạn đã hoàn thành xuất sắc toàn bộ lý thuyết và ôn luyện bài học này!</div>
            `
          });
        }

        // Sắp xếp toàn bộ timeline theo trình tự thời gian tăng dần (cũ nhất lên trước)
        timelineItems.sort((a, b) => a.date - b.date);

        // Render ra giao diện
        timelineItems.forEach(item => {
          const div = document.createElement('div');
          div.className = 'timeline-item mb-4 position-relative';
          div.innerHTML = item.html;
          container.appendChild(div);
        });
      }

      // Khởi tạo mục lục lúc vừa load trang
      if (canvas) {
        updateTableOfContents();
      }

      if (btnToggleEdit) {
        btnToggleEdit.addEventListener('click', function() {
          isEditing = !isEditing;
          if (isEditing) {
            canvas.setAttribute('contenteditable', 'true');
            btnToggleEdit.innerHTML = '🚫 Thoát biên tập';
            btnToggleEdit.classList.replace('btn-outline-secondary', 'btn-danger');
            btnSaveHtml.classList.remove('d-none');
            canvas.focus();
          } else {
            canvas.removeAttribute('contenteditable');
            btnToggleEdit.innerHTML = '✍️ Chế độ Biên tập';
            btnToggleEdit.classList.replace('btn-danger', 'btn-outline-secondary');
            btnSaveHtml.classList.add('d-none');
            popover.classList.add('d-none');
            currentEditingHighlight = null;
          }
          updateTableOfContents(); // [MỚI] Tải lại tích xanh và mục lục khi thay đổi chế độ biên tập
        });
      }

      // Bảng chọn màu
      colorDots.forEach(dot => {
        dot.addEventListener('click', function() {
          colorDots.forEach(d => d.classList.remove('active'));
          this.classList.add('active');
          selectedColor = this.getAttribute('data-color');
        });
      });
      const defaultDot = document.querySelector('.btn-color-red');
      if (defaultDot) defaultDot.classList.add('active');

      // Hàm định vị Popover tương đối theo card cha (position: relative) để tránh bị tràn màn hình
      function positionPopover(rect) {
        const parentCard = canvas.closest('.card');
        const parentRect = parentCard.getBoundingClientRect();

        const popoverHeight = popover.offsetHeight || 160;
        const popoverWidth = popover.offsetWidth || 260;

        let top = rect.top - parentRect.top - popoverHeight - 10;
        let left = rect.left - parentRect.left + (rect.width / 2) - (popoverWidth / 2);

        // Giới hạn trái / phải để tránh popover tràn ra ngoài
        const padding = 15;
        if (left < padding) {
          left = padding;
        } else if (left + popoverWidth > parentRect.width - padding) {
          left = parentRect.width - popoverWidth - padding;
        }

        // Tránh trồi lên trên mép trên của card
        if (top < padding) {
          top = rect.bottom - parentRect.top + 10;
        }

        popover.style.top = top + 'px';
        popover.style.left = left + 'px';
      }

      // Click vào khối đã có sẵn trong chế độ biên tập để SỬA/XÓA
      if (canvas) {
        canvas.addEventListener('click', function(e) {
          if (!isEditing) return;

          const highlightEl = e.target.closest('.grammar-highlight');
          if (highlightEl) {
            e.preventDefault();
            e.stopPropagation();

            currentEditingHighlight = highlightEl;
            const category = highlightEl.getAttribute('data-category');
            const name = highlightEl.getAttribute('data-name');
            tagNameInput.value = name || '';

            colorDots.forEach(dot => {
              dot.classList.remove('active');
              if (dot.getAttribute('data-color') === category) {
                dot.classList.add('active');
                selectedColor = category;
              }
            });

            // Hiện nút Xóa màu, đổi chữ nút Áp dụng thành Cập nhật
            btnDeleteHighlight.classList.remove('d-none');
            btnApplyHighlight.innerHTML = 'Cập nhật';

            // Định vị Popover
            const rect = highlightEl.getBoundingClientRect();
            positionPopover(rect);
            popover.classList.remove('d-none');
          }
        });
      }

      // Lắng nghe sự kiện bôi đen văn bản
      if (canvas) {
        document.addEventListener('selectionchange', handleSelection);
      }

      function handleSelection() {
        if (!isEditing) return;
        
        const selection = window.getSelection();
        if (selection.rangeCount === 0 || selection.isCollapsed) {
          const activeEl = document.activeElement;
          if (activeEl === tagNameInput || activeEl === btnApplyHighlight || activeEl === btnCancelHighlight || (btnDeleteHighlight && activeEl === btnDeleteHighlight)) {
            return;
          }
          popover.classList.add('d-none');
          return;
        }

        const range = selection.getRangeAt(0);
        if (!canvas.contains(range.commonAncestorContainer)) {
          popover.classList.add('d-none');
          return;
        }

        currentRange = range.cloneRange();
        currentEditingHighlight = null; // Reset đối tượng đang chỉnh sửa

        // Reset popover về chế độ Thêm mới
        btnDeleteHighlight.classList.add('d-none');
        btnApplyHighlight.innerHTML = 'Áp dụng';
        
        // Định vị Tooltip Popover ở trên vùng bôi đen
        const rect = range.getBoundingClientRect();
        positionPopover(rect);
        popover.classList.remove('d-none');
      }

      // Nút hủy đánh dấu
      if (btnCancelHighlight) {
        btnCancelHighlight.addEventListener('click', function() {
          popover.classList.add('d-none');
          tagNameInput.value = '';
          currentEditingHighlight = null;
          window.getSelection().removeAllRanges();
        });
      }

      // Nút áp dụng/Cập nhật đánh dấu màu sắc + gắn nhãn phân loại
      if (btnApplyHighlight) {
        btnApplyHighlight.addEventListener('click', function() {
          const tagName = tagNameInput.value.trim() || 'Đánh dấu';

          if (currentEditingHighlight) {
            // Cập nhật khối hiện tại
            currentEditingHighlight.className = `grammar-highlight hl-${selectedColor}`;
            currentEditingHighlight.setAttribute('data-category', selectedColor);
            currentEditingHighlight.setAttribute('data-name', tagName);
          } else {
            // Tạo khối đánh dấu mới
            if (!currentRange) return;

            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(currentRange);

            const span = document.createElement('span');
            span.className = `grammar-highlight hl-${selectedColor}`;
            span.setAttribute('data-category', selectedColor);
            span.setAttribute('data-name', tagName);
            
            try {
              currentRange.surroundContents(span);
            } catch (e) {
              const fragment = currentRange.extractContents();
              span.appendChild(fragment);
              currentRange.insertNode(span);
            }
          }

          tagNameInput.value = '';
          popover.classList.add('d-none');
          window.getSelection().removeAllRanges();
          currentRange = null;
          currentEditingHighlight = null;
          updateTableOfContents(); // Cập nhật mục lục bên trái
        });
      }

      // Nút gỡ màu đánh dấu (Unwrap thẻ span)
      if (btnDeleteHighlight) {
        btnDeleteHighlight.addEventListener('click', function() {
          if (currentEditingHighlight) {
            const parent = currentEditingHighlight.parentNode;
            while (currentEditingHighlight.firstChild) {
              parent.insertBefore(currentEditingHighlight.firstChild, currentEditingHighlight);
            }
            parent.removeChild(currentEditingHighlight);

            tagNameInput.value = '';
            popover.classList.add('d-none');
            currentEditingHighlight = null;
            updateTableOfContents(); // Cập nhật mục lục bên trái
          }
        });
      }

      // Nút lưu giáo trình gửi AJAX
      if (btnSaveHtml) {
        btnSaveHtml.addEventListener('click', function() {
          const sectionsData = [];
          const sectionDivs = canvas.querySelectorAll('.grammar-section');
          
          if (sectionDivs.length > 0) {
            sectionDivs.forEach(div => {
              const secId = div.getAttribute('data-section-id') || 'sec_' + Date.now();
              const titleEl = div.querySelector('h4');
              const title = titleEl ? titleEl.innerText.trim() : 'Nội dung bài học';
              const htmlEl = div.querySelector('.section-html-content');
              
              // [MỚI] Nhân bản và loại bỏ các tích xanh trước khi lưu vào cơ sở dữ liệu
              let html = '';
              if (htmlEl) {
                const clone = htmlEl.cloneNode(true);
                clone.querySelectorAll('.mastered-checkmark-badge').forEach(b => b.remove());
                html = clone.innerHTML;
              } else {
                html = div.innerHTML;
              }
              
              sectionsData.push({
                id: secId,
                title: title,
                html: html,
                study_time_target: 15,
                min_review_time_target: 10
              });
            });
          } else {
            // Không có .grammar-section, tự động tạo 1 section duy nhất
            const clone = canvas.cloneNode(true);
            clone.querySelectorAll('.mastered-checkmark-badge').forEach(b => b.remove());
            sectionsData.push({
              id: 'sec_default',
              title: '1. Nội dung bài học',
              html: clone.innerHTML,
              study_time_target: 15,
              min_review_time_target: 10
            });
          }

          const htmlContent = JSON.stringify(sectionsData);
          const url = "{{ route('grammar.lesson.save-html', $lesson->id) }}";

          btnSaveHtml.innerHTML = '⏳ Đang lưu...';
          btnSaveHtml.disabled = true;

          fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
              html_content: htmlContent
            })
          })
          .then(response => response.json())
          .then(data => {
            btnSaveHtml.innerHTML = '💾 Lưu giáo trình';
            btnSaveHtml.disabled = false;
            
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Lỗi: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            btnSaveHtml.innerHTML = '💾 Lưu giáo trình';
            btnSaveHtml.disabled = false;
            alert('Có lỗi xảy ra khi lưu giáo trình.');
          });
        });
      }

      // [MỚI] Xử lý nút bấm hoàn thành bài học gửi AJAX
      const btnCompleteLesson = document.getElementById('btnCompleteLesson');
      if (btnCompleteLesson) {
        btnCompleteLesson.addEventListener('click', function() {
          const total = parseInt(btnCompleteLesson.getAttribute('data-total-highlights') || '0');
          const mastered = parseInt(btnCompleteLesson.getAttribute('data-mastered-count') || '0');

          if (mastered < total || total === 0) {
            alert('Bạn cần học hết tất cả các phần kiến thức (đánh dấu đã học) mới có thể xác nhận hoàn thành bài học!');
            return;
          }

          if (confirm('Chúc mừng bạn đã học xong toàn bộ lý thuyết của bài học này! Bạn có muốn xác nhận hoàn thành bài học không?')) {
            const url = "{{ route('grammar.lesson.progress.save', $lesson->id) }}";
            
            fetch(url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              body: JSON.stringify({
                knowledge_key: 'lesson_completed',
                note: 'Đã học xong toàn bộ lý thuyết bài học'
              })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                alert('Chúc mừng! Bạn đã hoàn thành xuất sắc bài học này! 🎉');
                location.reload();
              } else {
                alert('Có lỗi xảy ra: ' + data.message);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Có lỗi xảy ra khi kết nối máy chủ.');
            });
          }
        });
      }
      // [MỚI] Xử lý nút bấm reset tiến trình học gửi AJAX
      const btnResetProgress = document.getElementById('btnResetProgress');
      if (btnResetProgress) {
        btnResetProgress.addEventListener('click', function() {
          if (confirm('CẢNH BÁO: Hành động này sẽ xóa sạch tiến trình học lý thuyết (các phần đã đánh dấu) và toàn bộ lịch sử ôn tập của bài học này về 0. Bạn có chắc chắn muốn reset không?')) {
            const url = "{{ route('grammar.lesson.progress.reset', $lesson->id) }}";
            
            btnResetProgress.disabled = true;
            btnResetProgress.innerHTML = '⏳ Đang reset...';

            fetch(url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                alert(data.message);
                location.reload();
              } else {
                alert('Có lỗi xảy ra: ' + data.message);
                btnResetProgress.disabled = false;
                btnResetProgress.innerHTML = '🔄 Reset Tiến Trình';
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Có lỗi xảy ra khi kết nối máy chủ.');
              btnResetProgress.disabled = false;
              btnResetProgress.innerHTML = '🔄 Reset Tiến Trình';
            });
          }
        });
      }
    });
  </script>

  <style>
    /* Custom Highlighter Styles */
    .grammar-highlight {
      cursor: pointer;
      position: relative;
      border-radius: 4px;
      padding: 2px 4px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .grammar-highlight.hl-red {
      background-color: #fee2e2 !important;
      color: #991b1b !important;
      border-bottom: 2px solid #f87171 !important;
    }

    .grammar-highlight.hl-blue {
      background-color: #dbeafe !important;
      color: #1e40af !important;
      border-bottom: 2px solid #60a5fa !important;
    }

    .grammar-highlight.hl-green {
      background-color: #dcfce7 !important;
      color: #166534 !important;
      border-bottom: 2px solid #4ade80 !important;
    }

    .grammar-highlight.hl-yellow {
      background-color: #fef9c3 !important;
      color: #854d0e !important;
      border-bottom: 2px solid #facc15 !important;
    }

    /* Tooltip on Hover */
    .grammar-highlight::after {
      content: attr(data-name);
      position: absolute;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      background-color: #1e293b;
      color: #ffffff;
      padding: 4px 8px;
      font-size: 11px;
      border-radius: 6px;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.2s, visibility 0.2s;
      box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
      z-index: 1000;
      font-weight: bold;
    }

    .grammar-highlight:hover::after {
      opacity: 1;
      visibility: visible;
    }

    .color-dot {
      cursor: pointer;
      transition: transform 0.2s, border-color 0.2s;
    }

    .color-dot:hover {
      transform: scale(1.15);
    }

    .color-dot.active {
      transform: scale(1.2);
      outline: 2px solid #0f172a;
      outline-offset: 2px;
    }

    .textbook-canvas[contenteditable="true"] {
      outline: 2px dashed #3b82f6 !important;
      outline-offset: 4px;
      border-radius: 8px;
      background-color: #fafaf9 !important;
    }

    /* Hiệu ứng nhấp nháy phát sáng cho nút hoàn thành bài học */
    @keyframes pulse-glow {
      0%, 100% {
        box-shadow: 0 0 5px rgba(37, 99, 235, 0.4);
        transform: scale(1);
      }
      50% {
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.8);
        transform: scale(1.02);
      }
    }
    .animate-pulse-glow {
      animation: pulse-glow 2s infinite ease-in-out;
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      color: white !important;
    }
  </style>

  <!-- [MỚI] Modal Nhật ký quá trình học -->
  <div class="modal fade" id="learningProcessModal" tabindex="-1" aria-labelledby="learningProcessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
        <div class="modal-header border-0 bg-light p-4" style="border-top-left-radius: 20px; border-top-right-radius: 20px; display: flex; justify-content: space-between; align-items: center;">
          <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="learningProcessModalLabel" style="margin: 0;">
            📅 Nhật Ký Quá Trình Học: <span class="text-primary">{{ $lesson->title }}</span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none; background: transparent; font-size: 20px;">×</button>
        </div>
        <div class="modal-body p-4" style="max-height: 450px; overflow-y: auto;">
          <!-- Timeline (Được vẽ động qua Javascript) -->
          <div id="timeline-container" class="timeline-wrapper position-relative ps-4 border-start border-2 border-primary-subtle" style="margin-left: 20px; text-align: left;">
          </div>
        </div>
        <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; display: flex; justify-content: flex-end;">
          <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: bold;">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <!-- [MỚI] Modal Lịch sử Ôn tập (Được chuyển xuống cuối trang để tránh lỗi nhấp nháy) -->
  @if($lesson->reviews->count() > 0)
    <div class="modal fade" id="reviewHistoryModal" tabindex="-1" aria-labelledby="reviewHistoryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
          <div class="modal-header bg-dark text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <h5 class="modal-title fw-bold text-white" id="reviewHistoryModalLabel" style="margin: 0;">📜 Lịch Sử Ôn Tập: {{ $lesson->title }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1); opacity: 0.8;"></button>
          </div>
          <div class="modal-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="background: white; text-align: left;">
                <thead class="table-light">
                  <tr>
                    <th class="text-center" style="width: 80px;">Lần ôn</th>
                    <th>Thời gian nộp</th>
                    <th class="text-center">Thời gian làm</th>
                    <th class="text-center">Điểm số</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 120px;">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lesson->reviews->sortByDesc('created_at') as $index => $rev)
                    <tr>
                      <td class="text-center fw-bold text-secondary">
                        #{{ $lesson->reviews->count() - $index }}
                      </td>
                      <td>
                        <div class="fw-semibold text-dark">
                          {{ $rev->created_at->format('H:i - d/m/Y') }}
                        </div>
                        <div class="text-muted small">
                          {{ $rev->created_at->diffForHumans() }}
                        </div>
                      </td>
                      <td class="text-center font-monospace small">
                        @php
                          $mins = floor($rev->duration_seconds / 60);
                          $secs = $rev->duration_seconds % 60;
                        @endphp
                        {{ $mins > 0 ? $mins . 'p ' : '' }}{{ $secs }}s
                      </td>
                      <td class="text-center fw-extrabold text-primary" style="font-size: 15px;">
                        {{ $rev->score }} / 100
                      </td>
                      <td class="text-center">
                        <span class="badge rounded-pill px-2.5 py-1"
                              style="font-size: 11px; background-color: {{ $rev->passed ? '#e6fdf0' : '#fdf2f2' }}; color: {{ $rev->passed ? '#15803d' : '#b91c1c' }} !important; border: 1px solid {{ $rev->passed ? '#86efac' : '#fca5a5' }} !important;">
                          {{ $rev->passed ? 'Đạt' : 'Chưa Đạt' }}
                        </span>
                      </td>
                      <td class="text-center">
                        <a href="{{ route('grammar.reviews.show', $rev->id) }}" class="btn btn-sm btn-outline-primary fw-bold" style="border-radius: 6px; font-size: 11px; text-decoration: none;">
                          🔍 Xem chi tiết
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection