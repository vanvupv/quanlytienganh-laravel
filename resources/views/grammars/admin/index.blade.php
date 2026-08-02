@extends('grammars.grammar')

@section('title', 'Quản Lý Ngữ Pháp')

@section('content_grammar')
  <div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">
    <!-- Admin Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">⚙️ Bảng Điều Khiển Ngữ Pháp</h1>
        <p class="text-muted mb-0">Quản lý lộ trình học tập, chủ đề và từng phần trong bài học ngữ pháp</p>
      </div>
      <div>
        <a href="{{ route('grammar.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold d-flex align-items-center gap-2" style="border-radius: 10px;">
          ← Xem Lộ Trình Học
        </a>
      </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
        <strong>🎉 Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Count Summary Grid -->
    <div class="row g-4 mb-4">
      <!-- Stages Counter -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 text-white hover-card" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 16px;">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold mb-1" style="opacity: 0.85; font-size: 15px;">Tổng Giai Đoạn (Stages)</h5>
              <h2 class="fw-extrabold m-0" style="font-size: 36px; font-weight: 800;">{{ $categoriesCount }}</h2>
            </div>
            <span style="font-size: 40px;">⏱️</span>
          </div>
        </div>
      </div>
      <!-- Topics Counter -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 text-white hover-card" style="background: linear-gradient(135deg, #0ea5e9, #0288d1); border-radius: 16px;">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold mb-1" style="opacity: 0.85; font-size: 15px;">Tổng Lộ Trình (Topics)</h5>
              <h2 class="fw-extrabold m-0" style="font-size: 36px; font-weight: 800;">{{ $topicsCount }}</h2>
            </div>
            <span style="font-size: 40px;">📌</span>
          </div>
        </div>
      </div>
      <!-- Lessons Counter -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 text-white hover-card" style="background: linear-gradient(135deg, #10b981, #047857); border-radius: 16px;">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold mb-1" style="opacity: 0.85; font-size: 15px;">Tổng Bài Học (Lessons)</h5>
              <h2 class="fw-extrabold m-0" style="font-size: 36px; font-weight: 800;">{{ $lessonsCount }}</h2>
            </div>
            <span style="font-size: 40px;">📚</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Management Panels -->
    <div class="row g-4">
      
      <!-- Stage (Categories) Management Card -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0 text-dark" style="font-size: 18px;">📋 Quản Lý Giai Đoạn (Categories)</h3>
            <button class="btn btn-primary btn-sm px-3 py-1.5 fw-bold" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
              + Thêm Giai Đoạn
            </button>
          </div>
          
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th style="width: 80px;">Thứ tự</th>
                  <th>Tên Giai Đoạn</th>
                  <th>Mô tả</th>
                  <th style="width: 130px; text-align: center;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($categories as $category)
                  <tr>
                    <td><span class="badge bg-secondary rounded-pill">{{ $category->order }}</span></td>
                    <td class="fw-bold text-dark">{{ $category->name }}</td>
                    <td class="text-muted small text-truncate" style="max-width: 150px;">{{ $category->description }}</td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-warning edit-category-btn" style="border-radius: 6px;"
                          data-id="{{ $category->id }}"
                          data-name="{{ $category->name }}"
                          data-description="{{ $category->description }}"
                          data-order="{{ $category->order }}"
                          data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                          Sửa
                        </button>
                        <form action="{{ route('grammar.admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa giai đoạn này? Tất cả lộ trình và bài học con sẽ bị xóa hoàn toàn!');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">Xóa</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted small py-4">Chưa có giai đoạn nào được tạo.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Topic (Roadmaps) Management Card -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0 text-dark" style="font-size: 18px;">📌 Quản Lý Lộ Trình (Topics)</h3>
            <button class="btn btn-primary btn-sm px-3 py-1.5 fw-bold" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#addTopicModal">
              + Thêm Lộ Trình
            </button>
          </div>
          
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Tên Lộ Trình</th>
                  <th>Thuộc Giai Đoạn</th>
                  <th>Bài học con</th>
                  <th style="width: 130px; text-align: center;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topics as $topic)
                  <tr>
                    <td class="fw-bold text-dark">{{ $topic->title }}</td>
                    <td>
                      <span class="badge bg-info text-dark" style="font-size: 11px;">
                        {{ $topic->category->name ?? 'Không xác định' }}
                      </span>
                    </td>
                    <td><span class="badge bg-light text-muted">{{ $topic->lessons_count }} bài</span></td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-warning edit-topic-btn" style="border-radius: 6px;"
                          data-id="{{ $topic->id }}"
                          data-title="{{ $topic->title }}"
                          data-description="{{ $topic->description }}"
                          data-category="{{ $topic->category_id }}"
                          data-order="{{ $topic->order }}"
                          data-bs-toggle="modal" data-bs-target="#editTopicModal">
                          Sửa
                        </button>
                        <form action="{{ route('grammar.admin.topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa lộ trình này? Tất cả bài học con trong lộ trình sẽ bị xóa hoàn toàn!');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">Xóa</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted small py-4">Chưa có lộ trình nào được tạo.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Lesson (Parts in Roadmap) Management Card -->
      <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h3 class="fw-bold mb-0 text-dark" style="font-size: 18px;">📚 Quản Lý Các Bài Học Chi Tiết (Lessons)</h3>
            <a href="{{ route('grammar.admin.lessons.create') }}" class="btn btn-success btn-sm px-3 py-2 fw-bold d-flex align-items-center gap-1" style="border-radius: 8px;">
              + Thêm Bài Học Mới
            </a>
          </div>

          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th style="width: 80px;">Thứ tự</th>
                  <th>Tên Bài Học</th>
                  <th>Thuộc Lộ Trình (Topic)</th>
                  <th>Giai Đoạn</th>
                  <th style="width: 150px; text-align: center;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($lessons as $lesson)
                  <tr>
                    <td><span class="badge bg-light text-secondary rounded-pill border">{{ $lesson->order }}</span></td>
                    <td class="fw-bold text-dark">{{ $lesson->title }}</td>
                    <td><span class="fw-semibold text-secondary">{{ $lesson->topic->title ?? 'Không xác định' }}</span></td>
                    <td>
                      <span class="badge bg-primary px-2.5 py-1.5" style="background-color: #3b82f6 !important; font-size: 11px;">
                        {{ $lesson->topic->category->name ?? 'Không xác định' }}
                      </span>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('grammar.admin.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 6px;">Sửa bài</a>
                        <form action="{{ route('grammar.admin.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa bài học này?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">Xóa</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted small py-4">Chưa có bài học nào được xây dựng.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-3">
            {{ $lessons->links() }}
          </div>
        </div>
      </div>

      <!-- TỔNG KẾT & PHÂN TÍCH TIẾN TRÌNH HỌC TẬP GIAI ĐOẠN 1 -->
      @if(isset($lessonsStats) && $lessonsStats->isNotEmpty())
        <div class="col-12 mt-4" id="hoc-tap-tong-ket">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white; border-top: 4px solid #10b981 !important;">
            
            <!-- Summary Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 border-bottom pb-3">
              <div>
                <h3 class="fw-bold text-success mb-1 d-flex align-items-center gap-2" style="font-size: 20px;">
                  📊 Báo Cáo Tổng Kết & Phân Tích Học Tập (Giai Đoạn 1)
                </h3>
                <p class="text-muted small mb-0">Hệ thống hóa thời gian học, số lần ôn tập, và điểm kiểm tra năng lực từng phần.</p>
              </div>
              <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold" style="background-color: #10b981 !important;">
                Giai đoạn 1: Nền tảng
              </span>
            </div>

            <!-- Dashboard Analytics KPI Stats Row -->
            <div class="row g-3 mb-4">
              <!-- Total Time Spent -->
              <div class="col-sm-6 col-xl-3">
                <div class="p-3 rounded-4 border text-center bg-light h-100" style="border-color: #e2e8f0 !important;">
                  <div class="fs-4 mb-1">⏱️</div>
                  <h6 class="text-muted fw-bold small uppercase mb-1">TỔNG THỜI GIAN TÍCH LŨY</h6>
                  <h4 class="fw-extrabold text-dark m-0" style="font-size: 20px;">
                    {{ $totalStage1Time }} phút
                    <br><small class="text-muted fw-semibold" style="font-size: 12px;">(~{{ round($totalStage1Time / 60, 1) }} giờ học & ôn)</small>
                  </h4>
                </div>
              </div>
              <!-- Total Sessions -->
              <div class="col-sm-6 col-xl-3">
                <div class="p-3 rounded-4 border text-center bg-light h-100" style="border-color: #e2e8f0 !important;">
                  <div class="fs-4 mb-1">🏫</div>
                  <h6 class="text-muted fw-bold small uppercase mb-1">SỐ BUỔI RÈN LUYỆN</h6>
                  <h4 class="fw-extrabold text-dark m-0" style="font-size: 20px; line-height: 2;">
                    {{ $totalStage1Sessions }} buổi học & ôn
                  </h4>
                </div>
              </div>
              <!-- Review Status Coverage -->
              <div class="col-sm-6 col-xl-3">
                <div class="p-3 rounded-4 border bg-light h-100" style="border-color: #e2e8f0 !important;">
                  <div class="text-center">
                    <span class="fs-4 mb-1 d-inline-block">📚</span>
                    <h6 class="text-muted fw-bold small uppercase mb-1">KIẾN THỨC ĐÃ ÔN TẬP</h6>
                    <h4 class="fw-extrabold text-dark m-0 mb-1 text-center" style="font-size: 20px;">
                      {{ $reviewedLessonsCount }} / {{ $totalLessonsInStage1 }} bài học
                    </h4>
                  </div>
                  <div class="progress" style="height: 6px; border-radius: 3px;">
                    @php 
                      $percentage = round(($reviewedLessonsCount / max(1, $totalLessonsInStage1)) * 100);
                    @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="text-end small text-muted mt-1" style="font-size: 10px;">Đã ôn {{ $percentage }}% lộ trình</div>
                </div>
              </div>
              <!-- Cumulative Score -->
              <div class="col-sm-6 col-xl-3">
                <div class="p-3 rounded-4 border text-center bg-light h-100" style="border-color: #e2e8f0 !important;">
                  <div class="fs-4 mb-1">🎯</div>
                  <h6 class="text-muted fw-bold small uppercase mb-1">ĐIỂM NGỮ PHÁP TÍCH LŨY</h6>
                  <h4 class="fw-extrabold text-success m-0" style="font-size: 22px;">
                    {{ $overallScore }} / 100
                  </h4>
                  <span class="badge rounded-pill px-2.5 py-1 text-xs mt-1 
                    @if($overallScore >= 90) bg-success text-white
                    @elseif($overallScore >= 80) bg-primary text-white
                    @elseif($overallScore >= 60) bg-warning text-dark
                    @else bg-danger text-white @endif" style="font-size: 10px;">
                    @if($overallScore >= 90) Xuất Sắc 🌟
                    @elseif($overallScore >= 80) Khá Giỏi 👍
                    @elseif($overallScore >= 60) Đạt Yêu Cầu 👌
                    @else Cần Cố Gắng ⚠ @endif
                  </span>
                </div>
              </div>
            </div>

            <!-- Section 3: Comparative Layout (Kiến thức hiện tại vs Kiến thức đã ôn tập) -->
            <div class="card border-0 shadow-sm p-3 mb-4 rounded-4" style="background: #fafafa; border: 1px solid #f1f5f9 !important;">
              <h4 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-1.5" style="font-size: 15px;">
                🔄 So Sánh Tiến Trình Ôn Tập So Với Lộ Trình Hiện Tại (Xác định Điểm số)
              </h4>
              <div class="row g-3">
                <!-- Column 1: Current Roadmap Lessons -->
                <div class="col-md-6 border-end pr-md-3" style="border-color: #e2e8f0 !important;">
                  <div class="p-2 bg-secondary text-white rounded-3 small fw-bold mb-2 text-center" style="background-color: #475569 !important;">
                    📖 KIẾN THỨC HIỆN TẠI (12 THÌ TIÊU CHUẨN)
                  </div>
                  <div class="d-flex flex-column gap-1.5 overflow-auto" style="max-height: 240px;">
                    @foreach($lessonsStats as $stat)
                      <div class="p-2 bg-white rounded-3 border small d-flex justify-content-between align-items-center shadow-sm">
                        <span class="fw-semibold text-dark">{{ $stat['title'] }}</span>
                        <span class="badge bg-light text-muted border" style="font-size: 10px;">Phần {{ $loop->iteration }}</span>
                      </div>
                    @endforeach
                  </div>
                </div>

                <!-- Column 2: Reviewed / Logged Progress -->
                <div class="col-md-6">
                  <div class="p-2 bg-success text-white rounded-3 small fw-bold mb-2 text-center" style="background-color: #16a34a !important;">
                    🎯 KIẾN THỨC ĐÃ ÔN TẬP (TIẾN TRÌNH & ĐIỂM SỐ)
                  </div>
                  <div class="d-flex flex-column gap-1.5 overflow-auto" style="max-height: 240px;">
                    @foreach($lessonsStats as $stat)
                      <div class="p-2 rounded-3 border small d-flex justify-content-between align-items-center shadow-sm
                        @if($stat['review_time'] > 0) bg-white border-success-subtle @else bg-light text-muted @endif"
                        style="@if($stat['review_time'] > 0) border-color: #bbf7d0 !important; @endif">
                        <span class="fw-semibold @if($stat['review_time'] > 0) text-success @else text-muted @endif">
                          @if($stat['review_time'] > 0) ✓ @else ✕ @endif {{ $stat['title'] }}
                        </span>
                        <div>
                          @if($stat['review_time'] > 0)
                            <span class="badge bg-success text-white px-2 py-1" style="font-size: 10px; background-color: #16a34a !important;">
                              {{ $stat['average_score'] }} điểm
                            </span>
                            <span class="badge bg-info text-dark px-1.5 py-0.5 ms-1" style="font-size: 9px; font-weight: 500;">
                              Ôn {{ $stat['sessions_count'] - 1 }} lần
                            </span>
                          @else
                            <span class="badge bg-secondary text-white px-2 py-1" style="font-size: 10px; background-color: #94a3b8 !important;">
                              Chưa ôn tập
                            </span>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <!-- Section 4: Detailed Study/Review Time Table -->
            <h4 class="fw-bold mb-3 text-dark d-flex align-items-center gap-1.5" style="font-size: 16px;">
              📋 Chi Tiết Thời Gian Học, Ôn Tập & Đánh Giá Từng Bài
            </h4>
            <div class="alert alert-info py-2 px-3 border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background-color: #f0f9ff; color: #0369a1; font-size: 12.5px;">
              <span>💡</span>
              <span><strong>Mẹo dành cho Giáo viên/Admin:</strong> Nhấp vào bất kỳ dòng bài học nào có biểu tượng 🔍 để mở xem chi tiết thời gian học và ôn của từng phần nhỏ (Công thức, Cách dùng, Ví dụ)!</span>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle border">
                <thead class="table-light">
                  <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th>Tên Bài Học Ngữ Pháp</th>
                    <th class="text-center">Số Buổi (Học + Ôn)</th>
                    <th class="text-center">Học Lý Thuyết (Minutes)</th>
                    <th class="text-center">Đã Ôn Tập (Minutes)</th>
                    <th class="text-center">Tổng Tích Lũy (Minutes)</th>
                    <th class="text-center">Đánh Giá Từng Phần</th>
                    <th style="width: 140px;" class="text-center">Chi Tiết</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lessonsStats as $stat)
                    <!-- Main Lesson Stats Row -->
                    <tr class="align-middle hover-row" data-bs-toggle="collapse" data-bs-target="#sections-breakdown-{{ $stat['id'] }}" style="cursor: pointer; transition: all 0.2s;">
                      <td class="text-center fw-bold text-secondary">{{ $loop->iteration }}</td>
                      <td class="fw-bold text-dark">
                        {{ $stat['title'] }}
                        @if($stat['review_time'] > 0)
                          <span class="badge bg-success-subtle text-success border border-success-subtle ms-2 px-2 py-0.5 rounded-pill" style="font-size: 10px; background-color: #e6fdf0; border-color: #bbf7d0 !important; color: #15803d !important;">Đã ôn</span>
                        @else
                          <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-2 px-2 py-0.5 rounded-pill" style="font-size: 10px; background-color: #f1f5f9; border-color: #cbd5e1 !important; color: #475569 !important;">Chưa ôn</span>
                        @endif
                      </td>
                      <td class="text-center fw-semibold text-secondary">{{ $stat['sessions_count'] }} buổi</td>
                      <td class="text-center text-primary fw-bold">{{ $stat['study_time'] }} phút</td>
                      <td class="text-center text-success fw-bold">{{ $stat['review_time'] }} phút</td>
                      <td class="text-center fw-bold text-dark">{{ $stat['study_time'] + $stat['review_time'] }} phút</td>
                      <td class="text-center">
                        @if($stat['total_sections'] > 0)
                          <span class="badge rounded-pill px-2.5 py-1.5 fw-bold
                            @if($stat['passed_sections'] == $stat['total_sections']) bg-success text-white
                            @elseif($stat['passed_sections'] > 0) bg-warning text-dark
                            @else bg-danger text-white @endif" style="font-size: 11px;">
                            {{ $stat['passed_sections'] }} / {{ $stat['total_sections'] }} Đạt
                          </span>
                        @else
                          <span class="text-muted small italic">Chưa đánh giá</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-outline-success px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1" style="border-radius: 6px; font-size: 11px;">
                          🔍 Từng phần
                        </button>
                      </td>
                    </tr>

                    <!-- Collapsible Row for Section Breaks (Formula, Usage, Examples) -->
                    <tr class="collapse bg-light" id="sections-breakdown-{{ $stat['id'] }}">
                      <td colspan="8" class="p-3 border-0">
                        <div class="p-3 bg-white rounded-4 border shadow-sm mx-2">
                          <h6 class="fw-bold text-dark mb-3 border-bottom pb-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span>
                              📋 Chi tiết thời gian học & ôn từng phần: <span class="text-success">{{ $stat['title'] }}</span>
                              @php
                                $lessonObj = \App\Models\Grammar\GrammarLesson::find($stat['id']);
                                $lastRev = $lessonObj ? $lessonObj->reviews()->latest()->first() : null;
                              @endphp
                              @if($lastRev)
                                <a href="{{ route('grammar.reviews.show', $lastRev->id) }}" class="btn btn-primary fw-bold ms-3 px-2.5 py-0.5 text-white" style="border-radius: 4px; font-size: 10.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background-color: #3b82f6; border-color: #3b82f6;">
                                  📊 Xem chi tiết bài ôn gần nhất ({{ $lastRev->score }}đ)
                                </a>
                              @endif
                            </span>
                            <span class="small fw-normal text-muted" style="font-size: 12px;">Đạt yêu cầu khi: Điểm ôn tập >= 80 và Thời gian ôn >= thời gian tối thiểu.</span>
                          </h6>
                          
                          <div class="row g-3">
                            @forelse($stat['sections'] as $sec)
                              <div class="col-md-4">
                                <div class="p-3 rounded-4 border h-100" 
                                     style="{{ $sec->status == 'Đạt' ? 'background-color: #f0fdf4; border: 1.5px solid #bbf7d0 !important;' : 'background-color: #fffbeb; border: 1.5px solid #fef08a !important;' }}">
                                  
                                  <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-light">
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">⚡ {{ $sec->section_name }}</h5>
                                    <span class="badge {{ $sec->status == 'Đạt' ? 'bg-success text-white' : 'bg-warning text-dark' }} px-2.5 py-1 fw-bold rounded-pill" style="font-size: 10px;">
                                      {{ $sec->status }}
                                    </span>
                                  </div>

                                  <!-- Time & Score Bullet Metrics -->
                                  <ul class="list-unstyled mb-0 d-flex flex-column gap-1.5 small text-secondary" style="font-size: 12px;">
                                    <li>⏱️ <strong>Thời gian học lý thuyết:</strong> <span class="text-dark fw-semibold">{{ $sec->study_time }} phút</span></li>
                                    <li>⏱️ <strong>Thời gian ôn tối thiểu:</strong> <span class="text-dark fw-semibold">{{ $sec->min_review_time }} phút</span></li>
                                    <li>🔄 <strong>Số lần đã thực hiện ôn:</strong> <span class="text-dark fw-semibold">{{ $sec->review_count }} lần</span></li>
                                    <li>⏳ <strong>Tổng thời gian đã ôn:</strong> <span class="text-dark fw-semibold">{{ $sec->review_time }} phút</span></li>
                                    <li>🎯 <strong>Tổng điểm các lần ôn:</strong> 
                                      <span class="fw-bold @if($sec->review_score >= 80) text-success @else text-danger @endif">
                                        {{ $sec->review_score }} / 100 điểm
                                      </span>
                                    </li>
                                  </ul>

                                  <!-- Recommendation Warning Box -->
                                  <div class="mt-2.5 pt-2 border-top small" style="border-top: 1px dashed rgba(0,0,0,0.06) !important; font-size: 11px;">
                                    @if($sec->status == 'Đạt')
                                      <span class="text-success fw-semibold">✓ Đạt chuẩn thời gian & điểm kiểm tra!</span>
                                    @else
                                      @if($sec->review_count == 0)
                                        <span class="text-muted italic">⚠ Bài học chưa được thực hiện ôn tập.</span>
                                      @elseif($sec->review_time < $sec->min_review_time)
                                        <span class="text-danger fw-semibold">⚠ Thiếu thời gian ôn tập (Cần thêm {{ $sec->min_review_time - $sec->review_time }} phút)</span>
                                      @elseif($sec->review_score < 80)
                                        <span class="text-danger fw-semibold">⚠ Điểm kiểm tra chưa đạt (Cần đạt >= 80 điểm)</span>
                                      @endif
                                    @endif
                                  </div>
                                </div>
                              </div>
                            @empty
                              <div class="col-12 py-3 text-center text-muted small italic">
                                Bài học này chưa được khởi tạo dữ liệu theo dõi hoặc chưa có tương tác học tập của học viên.
                              </div>
                            @endforelse
                          </div>

                          <!-- Lịch sử ôn tập chi tiết -->
                          @php
                            $allReviews = $lessonObj ? $lessonObj->reviews()->orderBy('created_at', 'desc')->get() : collect();
                          @endphp
                          
                          @if($allReviews->count() > 0)
                            <div class="mt-4 pt-3 border-top">
                              <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-1.5" style="font-size: 14px;">
                                📜 Lịch sử chi tiết tất cả các lần ôn tập của học viên ({{ $allReviews->count() }} lần)
                              </h6>
                              <div class="table-responsive rounded-3 border">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12.5px;">
                                  <thead class="table-dark">
                                    <tr>
                                      <th class="text-center" style="width: 60px;">Lần</th>
                                      <th>Ngày giờ ôn tập</th>
                                      <th class="text-center">Thời gian làm bài</th>
                                      <th class="text-center">Điểm số đạt được</th>
                                      <th class="text-center">Trạng thái đánh giá</th>
                                      <th class="text-center" style="width: 140px;">Báo cáo chi tiết</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($allReviews as $rev)
                                      <tr>
                                        <td class="text-center fw-bold text-secondary">#{{ $allReviews->count() - $loop->index }}</td>
                                        <td class="fw-semibold text-dark">{{ $rev->created_at->format('H:i - d/m/Y') }}</td>
                                        <td class="text-center font-monospace">
                                          @php
                                            $mins = floor($rev->duration_seconds / 60);
                                            $secs = $rev->duration_seconds % 60;
                                          @endphp
                                          {{ $mins > 0 ? $mins . 'p ' : '' }}{{ $secs }}s
                                        </td>
                                        <td class="text-center fw-extrabold text-primary" style="font-size: 13.5px;">{{ $rev->score }} / 100</td>
                                        <td class="text-center">
                                          <span class="badge rounded-pill px-2.5 py-1"
                                                style="font-size: 10.5px; background-color: {{ $rev->passed ? '#e6fdf0' : '#fdf2f2' }}; color: {{ $rev->passed ? '#15803d' : '#b91c1c' }} !important; border: 1px solid {{ $rev->passed ? '#86efac' : '#fca5a5' }} !important;">
                                            {{ $rev->passed ? 'Đạt' : 'Chưa Đạt' }}
                                          </span>
                                        </td>
                                        <td class="text-center">
                                          <a href="{{ route('grammar.reviews.show', $rev->id) }}" class="btn btn-xs btn-outline-success px-2 py-0.5 fw-bold" style="border-radius: 4px; font-size: 10.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                            🔍 Xem bài làm
                                          </a>
                                        </td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      @endif

    </div>
  </div>

  <style>
    .hover-row:hover {
      background-color: #f8fafc !important;
    }
  </style>

  <!-- ================= MODALS FOR CATEGORY ================= -->
  <!-- Add Category Modal -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header bg-primary text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
          <h5 class="modal-title fw-bold" id="addCategoryModalLabel">⏱️ Thêm Giai Đoạn Mới</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('grammar.admin.categories.store') }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="mb-3">
              <label for="name" class="form-label fw-bold">Tên Giai Đoạn <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" placeholder="Ví dụ: Giai đoạn 1 (Nền tảng)" required style="border-radius: 8px;">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label fw-bold">Mô tả tóm tắt</label>
              <textarea class="form-control" name="description" rows="3" placeholder="Ví dụ: Trang bị kiến thức ngữ pháp cơ bản..." style="border-radius: 8px;"></textarea>
            </div>
            <div class="mb-3">
              <label for="order" class="form-label fw-bold">Thứ tự sắp xếp</label>
              <input type="number" class="form-control" name="order" value="0" style="border-radius: 8px;">
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 8px;">Lưu giai đoạn</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header bg-warning text-dark border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
          <h5 class="modal-title fw-bold" id="editCategoryModalLabel">✏️ Chỉnh Sửa Giai Đoạn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editCategoryForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body p-4">
            <div class="mb-3">
              <label for="edit_cat_name" class="form-label fw-bold">Tên Giai Đoạn <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_cat_name" name="name" required style="border-radius: 8px;">
            </div>
            <div class="mb-3">
              <label for="edit_cat_description" class="form-label fw-bold">Mô tả tóm tắt</label>
              <textarea class="form-control" id="edit_cat_description" name="description" rows="3" style="border-radius: 8px;"></textarea>
            </div>
            <div class="mb-3">
              <label for="edit_cat_order" class="form-label fw-bold">Thứ tự sắp xếp</label>
              <input type="number" class="form-control" id="edit_cat_order" name="order" style="border-radius: 8px;">
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            <button type="submit" class="btn btn-warning px-4 fw-bold" style="border-radius: 8px;">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- ================= MODALS FOR TOPIC ================= -->
  <!-- Add Topic Modal -->
  <div class="modal fade" id="addTopicModal" tabindex="-1" aria-labelledby="addTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header bg-primary text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
          <h5 class="modal-title fw-bold" id="addTopicModalLabel">📌 Thêm Lộ Trình Mới</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('grammar.admin.topics.store') }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="mb-3">
              <label for="category_id" class="form-label fw-bold">Chọn Giai Đoạn <span class="text-danger">*</span></label>
              <select class="form-select" name="category_id" required style="border-radius: 8px;">
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="title" class="form-label fw-bold">Tên Lộ Trình <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" placeholder="Ví dụ: 12 Thì cơ bản trong Tiếng Anh" required style="border-radius: 8px;">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label fw-bold">Mô tả tóm tắt</label>
              <textarea class="form-control" name="description" rows="3" placeholder="Ví dụ: Tìm hiểu công thức, cách dùng, dấu hiệu của 12 thì..." style="border-radius: 8px;"></textarea>
            </div>
            <div class="mb-3">
              <label for="order" class="form-label fw-bold">Thứ tự sắp xếp</label>
              <input type="number" class="form-control" name="order" value="0" style="border-radius: 8px;">
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 8px;">Lưu lộ trình</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Topic Modal -->
  <div class="modal fade" id="editTopicModal" tabindex="-1" aria-labelledby="editTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header bg-warning text-dark border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
          <h5 class="modal-title fw-bold" id="editTopicModalLabel">✏️ Chỉnh Sửa Lộ Trình</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editTopicForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body p-4">
            <div class="mb-3">
              <label for="edit_top_category" class="form-label fw-bold">Chọn Giai Đoạn <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_top_category" name="category_id" required style="border-radius: 8px;">
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_top_title" class="form-label fw-bold">Tên Lộ Trình <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_top_title" name="title" required style="border-radius: 8px;">
            </div>
            <div class="mb-3">
              <label for="edit_top_description" class="form-label fw-bold">Mô tả tóm tắt</label>
              <textarea class="form-control" id="edit_top_description" name="description" rows="3" style="border-radius: 8px;"></textarea>
            </div>
            <div class="mb-3">
              <label for="edit_top_order" class="form-label fw-bold">Thứ tự sắp xếp</label>
              <input type="number" class="form-control" id="edit_top_order" name="order" style="border-radius: 8px;">
            </div>
          </div>
          <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            <button type="submit" class="btn btn-warning px-4 fw-bold" style="border-radius: 8px;">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT HELPERS FOR FILLING FORMS IN MODALS -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // 1. Populate Category edit form
      const editCategoryBtns = document.querySelectorAll('.edit-category-btn');
      editCategoryBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.getAttribute('data-id');
          const name = this.getAttribute('data-name');
          const description = this.getAttribute('data-description');
          const order = this.getAttribute('data-order');
          
          document.getElementById('edit_cat_name').value = name;
          document.getElementById('edit_cat_description').value = description;
          document.getElementById('edit_cat_order').value = order;
          
          // Set form action dynamically
          const form = document.getElementById('editCategoryForm');
          form.action = `/grammar-admin/categories/${id}`;
        });
      });

      // 2. Populate Topic edit form
      const editTopicBtns = document.querySelectorAll('.edit-topic-btn');
      editTopicBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.getAttribute('data-id');
          const title = this.getAttribute('data-title');
          const description = this.getAttribute('data-description');
          const category = this.getAttribute('data-category');
          const order = this.getAttribute('data-order');
          
          document.getElementById('edit_top_title').value = title;
          document.getElementById('edit_top_description').value = description;
          document.getElementById('edit_top_category').value = category;
          document.getElementById('edit_top_order').value = order;
          
          // Set form action dynamically
          const form = document.getElementById('editTopicForm');
          form.action = `/grammar-admin/topics/${id}`;
        });
      });
    });
  </script>

  <style>
    .hover-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
  </style>
@endsection
