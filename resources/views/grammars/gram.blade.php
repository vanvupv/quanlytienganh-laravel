@extends('grammars.grammar')

@section('title', 'Ngữ pháp tiếng Anh')

@section('content_grammar')
  <!-- Main Content -->
  <div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">
    <!-- Premium Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">📖 Lộ Trình Học Ngữ Pháp</h1>
        <p class="text-muted mb-0">Hệ thống hóa toàn bộ kiến thức ngữ pháp từ cơ bản đến nâng cao</p>
      </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
        <strong>🎉 Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- [MỚI] Hộp Ôn Tập Giãn Cách (Spaced Repetition System) -->
    @if($dueLessons && $dueLessons->isNotEmpty())
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #fffbeb 0%, #fff7ed 100%); border-left: 5px solid #f97316 !important;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <div>
            <h3 class="fw-bold text-warning mb-1 d-flex align-items-center gap-2" style="font-size: 20px; color: #ea580c !important; text-align: left;">
              🧠 Hộp Ôn Tập Giãn Cách (Spaced Repetition)
            </h3>
            <p class="text-muted small mb-0" style="text-align: left;">Hệ thống phát hiện các bài học bên dưới đã quá hạn ôn tập hoặc đang phai nhạt dần trong trí nhớ của bạn.</p>
          </div>
          <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #ffedd5; color: #ea580c; font-size: 13px;">
            {{ $dueLessons->count() }} Bài học cần ôn
          </span>
        </div>

        <div class="row g-3">
          @foreach($dueLessons as $due)
            <div class="col-md-6 col-lg-4">
              <div class="p-3 bg-white border rounded-4 d-flex flex-column justify-content-between h-100 shadow-sm" style="border-color: #fed7aa !important; text-align: left;">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge px-2 py-1" style="font-size: 10px; font-weight: bold; background-color: {{ $due->srs_status === 'Cần ôn tập' ? '#fef2f2' : '#fffbeb' }}; color: {{ $due->srs_status === 'Cần ôn tập' ? '#dc2626' : '#d97706' }} !important; border: 1px solid {{ $due->srs_status === 'Cần ôn tập' ? '#fca5a5' : '#fde047' }} !important; border-radius: 12px;">
                      {{ $due->srs_status }}
                    </span>
                    <span class="text-muted font-monospace" style="font-size: 11px;">Trí nhớ: <strong>{{ $due->memory_strength }}%</strong></span>
                  </div>
                  <h5 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">{{ $due->title }}</h5>
                  <div class="progress mb-3" style="height: 6px; border-radius: 3px; background-color: #f1f5f9;">
                    <div class="progress-bar {{ $due->srs_status === 'Cần ôn tập' ? 'bg-danger' : 'bg-warning' }}" role="progressbar" style="width: {{ $due->memory_strength }}%;" aria-valuenow="{{ $due->memory_strength }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
                <a href="{{ route('grammar.lessons.review', $due->id) }}" class="btn btn-sm btn-orange w-100 fw-bold py-2 mt-2 text-white d-flex align-items-center justify-content-center gap-1.5 shadow-sm" style="border-radius: 8px; font-size: 12.5px; transition: all 0.2s; text-decoration: none;">
                  🔄 Ôn Tập Ngay
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Dynamic Timeline General Section -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white;">
      <h3 class="fw-bold mb-4 text-secondary d-flex align-items-center gap-2" style="font-size: 20px;">
        <span class="p-2 rounded-3 text-white d-inline-flex" style="background: #2563eb; width: 34px; height: 34px; align-items: center; justify-content: center;">⏱️</span>
        Timeline Lộ Trình Tổng Quan
      </h3>
      
      <div class="grammar-timeline-horizontal py-2">
        <div class="row g-4 flex-nowrap overflow-auto pb-3" style="scrollbar-width: thin;">
          @forelse($categories as $category)
            <div class="col-md-4" style="min-width: 320px;">
              <div class="p-3 h-100 rounded-4 border" style="background: #fafafa; border-color: #f1f5f9 !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold" style="background-color: #2563eb !important;">Giai đoạn {{ $loop->iteration }}</span>
                </div>
                <h4 class="fw-bold text-dark mt-2 mb-1" style="font-size: 16px;">{{ $category->name }}</h4>
                <p class="text-muted small mb-3">{{ $category->description }}</p>
                
                <div class="topics-list d-flex flex-column gap-2">
                  @forelse($category->topics as $topic)
                    <div class="p-2 bg-white rounded-3 border-start border-primary border-4 shadow-sm">
                      <div class="fw-bold text-secondary small">{{ $topic->title }}</div>
                      <span class="badge bg-light text-muted mt-1" style="font-size: 10px;">{{ $topic->lessons->count() }} Bài học</span>
                    </div>
                  @empty
                    <div class="text-muted small py-2">Chưa có lộ trình cụ thể.</div>
                  @endforelse
                </div>
              </div>
            </div>
          @empty
            <div class="col-12 py-4 text-center">
              <p class="text-muted mb-0">Chưa có dữ liệu giai đoạn học tập nào.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Detailed Roadmaps (Lộ trình chi tiết & Cây Ngữ pháp) -->
    <div class="row g-4">
      @forelse($categories as $category)
        <div class="col-12">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white; border-top: 4px solid #3b82f6 !important;">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3 flex-wrap gap-2">
              <div>
                <h3 class="fw-bold text-primary mb-1 d-flex align-items-center gap-2" style="font-size: 20px;">
                  ⚡ Giai Đoạn: {{ $category->name }}
                </h3>
                <p class="text-muted small mb-0">{{ $category->description }}</p>
              </div>
              <span class="badge bg-blue-100 text-primary px-3 py-2 rounded-pill fw-bold" style="background-color: #eff6ff; color: #1d4ed8;">
                {{ $category->topics->count() }} Lộ Trình Con
              </span>
            </div>

            <div class="row g-4 mt-1">
              @forelse($category->topics as $topic)
                <div class="col-lg-6">
                  <div class="card h-100 border-0 p-3 shadow-sm hover-card" style="background: #f8fafc; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <h4 class="fw-bold text-dark mb-1" style="font-size: 16px;">📌 Lộ trình: {{ $topic->title }}</h4>
                      <span class="badge bg-secondary text-white rounded-pill px-2 py-1" style="font-size: 11px;">{{ $topic->lessons->count() }} phần</span>
                    </div>
                    <p class="text-muted small mb-3">{{ $topic->description }}</p>
                    
                    <div class="lesson-steps">
                      <h5 class="fw-semibold text-secondary small mb-2">📚 Bài học chi tiết:</h5>
                      <div class="row g-2">
                        @forelse($topic->lessons as $lesson)
                          <div class="col-sm-6">
                            <a href="{{ route('grammar.lesson.show', $lesson->id) }}" class="text-decoration-none d-block">
                              <div class="p-2 bg-white border border-light rounded-3 d-flex align-items-center justify-content-between hover-lesson shadow-sm" style="transition: all 0.2s; min-height: 42px;">
                                <div class="d-flex align-items-center gap-2 min-w-0" style="max-width: calc(100% - 16px);">
                                  <div class="step-num text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="background: #eff6ff; width: 24px; height: 24px; border-radius: 50%; font-size: 11px;">
                                    {{ $loop->iteration }}
                                  </div>
                                  <div class="text-truncate text-secondary small fw-semibold" style="text-align: left;">
                                    {{ $lesson->title }}
                                  </div>
                                </div>
                                @if($lesson->srs_status)
                                  <span class="rounded-circle d-inline-block flex-shrink-0 @if($lesson->srs_status === 'Cần ôn tập') bg-danger animate-pulse-glow-red @elseif($lesson->srs_status === 'Đang phai nhạt') bg-warning @else bg-success @endif" 
                                        style="width: 8px; height: 8px;" 
                                        title="Trạng thái trí nhớ: {{ $lesson->srs_status }} ({{ $lesson->memory_strength }}%)">
                                  </span>
                                @endif
                              </div>
                            </a>
                          </div>
                        @empty
                          <div class="col-12 text-muted small py-2">Chưa có bài học nào được xây dựng trong lộ trình này.</div>
                        @endforelse
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-12 py-3 text-center text-muted">
                  Giai đoạn này chưa có lộ trình nào được khởi tạo.
                </div>
              @endforelse
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 py-5 text-center text-muted">
          Chưa có dữ liệu lộ trình học tập nào được cập nhật.
        </div>
      @endforelse
    </div>
  </div>

  <style>
    .hover-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.06) !important;
    }
    .hover-lesson:hover {
      background: #eff6ff !important;
      border-color: #3b82f6 !important;
      transform: scale(1.02);
    }
    .btn-orange {
      background-color: #f97316 !important;
      border-color: #f97316 !important;
    }
    .btn-orange:hover {
      background-color: #ea580c !important;
      border-color: #ea580c !important;
      box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.2);
    }
    
    /* Hiệu ứng nhấp nháy phát sáng cho bài học cần ôn tập gấp */
    @keyframes pulse-glow-red {
      0%, 100% {
        box-shadow: 0 0 2px rgba(239, 68, 68, 0.5);
        transform: scale(1);
      }
      50% {
        box-shadow: 0 0 8px rgba(239, 68, 68, 1);
        transform: scale(1.2);
      }
    }
    .animate-pulse-glow-red {
      animation: pulse-glow-red 1.5s infinite ease-in-out;
    }
  </style>
@endsection