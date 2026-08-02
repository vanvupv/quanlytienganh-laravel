@extends('assessments.app')

@section('title', 'Quản Lý Đề Thi (Admin)')

@section('content')
  <style>
  .assessment__fall {
    border: 2px solid #dc3545 !important; /* đỏ Bootstrap */
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.4);
  }

  .assessment__fall .card-header {
    background-color: #dc3545 !important;
    color: #fff !important;
  }
  </style>
     
  <div class="container mt-5">
    <!-- Start Section 1 -->
    <section>
      <h1 class="mb-4">Quản Lý Đề Thi (Admin)</h1>
    </section>
    <!-- End Section 1 -->
    
    <!-- Start Section 2 - Thống kê nhanh -->
    <section>
      <div class="quick_ mb-4">
        <h2 class="mb-4 fw-bold">Thống kê nhanh</h2>
        <div class="stats d-flex gap-3 flex-wrap">
          <div class="stat-card upcoming flex-fill p-4 text-center rounded shadow-sm">
            <div class="stat-icon mb-2">
              <i class="bi bi-calendar-event"></i>
            </div>
            <h3 class="stat-number">{{ $upcomingCount }}</h3>
            <p class="stat-label">Sắp diễn ra</p>
          </div>
          <div class="stat-card ongoing flex-fill p-4 text-center rounded shadow-sm">
            <div class="stat-icon mb-2">
              <i class="bi bi-play-circle"></i>
            </div>
            <h3 class="stat-number">{{ $ongoingCount }}</h3>
            <p class="stat-label">Đang diễn ra</p>
          </div>
          <div class="stat-card finished flex-fill p-4 text-center rounded shadow-sm">
            <div class="stat-icon mb-2">
              <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stat-number">{{ $finishedCount }}</h3>
            <p class="stat-label">Đã kết thúc</p>
          </div>
        </div>
      </div>
    </section>   
    <!-- End Section 2 - Thống kê nhanh -->
     
    <!-- Start Section 3 - Tạo buổi đánh giá mới -->
    <section>
      <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createAssessmentModal">
        Tạo buổi đánh giá mới
      </button>
    </section>
    <!-- End Section 3 - Tạo buổi đánh giá mới -->
    
    <!-- Start Section 4 - Buổi đánh giá sắp diễn ra -->
    <section>
      <div class="card">
        <div class="card card__upComing">
          <!-- Sắp diễn ra -->
          <div class="section__4_assessment--upComing">
            <!-- Table danh sách buổi đánh giá -->
            <h4>Buổi sắp diễn ra</h4>
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>STT</th> 
                  <th>Mã buổi</th> 
                  <th>Tên buổi đánh giá</th>
                  <th>Chủ đề</th>
                  <th>Bắt đầu</th>
                  <th>Kết thúc</th>
                  <th>Số lượng câu hỏi</th>
                  <th>Thời gian làm bài</th>
                  <th>Yêu cầu làm bài</th>
                  <th>Trạng thái</th>
                  <th>Học sinh</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($upcomingAssessments as $assessment)
                  <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Số thứ tự -->
                    <td>{{ $assessment->id }}</td> <!-- Mã buổi -->
                    <td>{{ $assessment->title }}</td>
                    <td>{{ $assessment->type }}</td>
                    <td>{{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</td>
                    <td>{{ $assessment->end_time->format('d/m/Y (H\Hi A)') }}</td>
                    <td>{{ $assessment->quantity }}</td>
                    <td>{{ $assessment->duration }} phút </td>
                    <td>{{ $assessment->min_score }} Điểm - {{ $assessment->min_performance }} %  </td>
                    <td><span class="badge bg-warning">Sắp diễn ra</span></td>
                    <td>{{ $assessment->user_assessments_count ?? 0 }}</td>
                    <td>
                      <a href="{{ route('assessments.do', $assessment->id) }}" class="btn btn-sm btn-info">Xem</a>
                      <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                      <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
                  
        <div class="card card__onGoing">
          <!-- Đang diễn ra -->                                      
          <div class="section__4_assessment--onGoing">
            <!-- Table danh sách buổi đánh giá -->
            <h4>Buổi đang diễn ra</h4>
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>STT</th> 
                  <th>Mã buổi</th> 
                  <th>Tên buổi đánh giá</th>
                  <th>Chủ đề</th>
                  <th>Bắt đầu</th>
                  <th>Kết thúc</th>
                  <th>Số lượng câu hỏi</th>
                  <th>Thời gian làm bài</th>
                  <th>Yêu cầu làm bài</th>
                  <th>Trạng thái</th>
                  <th>Học sinh</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ongoingAssessments as $assessment)
                  <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Số thứ tự -->
                    <td>{{ $assessment->id }}</td> <!-- Mã buổi -->
                    <td>{{ $assessment->title }}</td>
                    <td>{{ $assessment->type }}</td>
                    <td>{{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</td>
                    <td>{{ $assessment->end_time->format('d/m/Y (H\Hi A)') }}</td>
                    <td>{{ $assessment->quantity }}</td>
                    <td>{{ $assessment->duration }} phút </td>
                    <td>{{ $assessment->min_score }} Điểm - {{ $assessment->min_performance }} %  </td>
                    <td><span class="badge bg-primary">Đang diễn ra</span></td>
                    <td>{{ $assessment->user_assessments_count ?? 0 }}</td>
                    <td>
                      <a href="{{ route('assessments.do', $assessment->id) }}" class="btn btn-sm btn-info">Xem</a>
                      <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                      <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div> 
    </section>
    <!-- End Section 4 - Buổi đánh giá sắp diễn ra -->

    <!-- Start Section 5 - Buổi đánh giá kết thúc -->  
    <section>
      <h4>Buổi đánh giá đã hoàn thành</h4>
      <div class="finished-slider">
        @foreach($assessment_full as $assessment)
          @php
            // Lấy userAssessment của user hiện tại
            $ua = $assessment->userAssessments->firstWhere('user_id', auth()->id() ?? 1);

            $correct = 0;
            $total = 0;
            $duration = null;
            $efficiency = 0;

            if ($ua) {
              // Đếm số câu đúng
              $correct = $assessment->results
                ->where('user_id', $ua->user_id)
                ->where('is_correct', 1)
                ->count();

              // Đếm tổng số câu đã làm
              $total = $assessment->results
                ->where('user_id', $ua->user_id)
                ->count();

              // Thời gian làm bài
              $duration = $ua->finished_at && $ua->started_at
                ? $ua->finished_at->diffInMinutes($ua->started_at)
                : null;

              // Hiệu suất làm bài   
              $efficiency = $duration > 0 ? round(($total / $duration) * 60, 2) : 0;
            }
            
            $requiredEfficiency = 600; // ví dụ: yêu cầu 600 từ/giờ (tùy bạn đặt)
            $requiredCorrect = 100;    // ví dụ: yêu cầu 350 câu đúng
            
            $efficiencyPassed = $efficiency > $requiredEfficiency;
            $correctPassed = $correct >= $requiredCorrect;

            if ($correctPassed || ($correctPassed && $efficiencyPassed)) {
                $finalResult = "✅ Bài đánh giá đạt yêu cầu";
                $cardClass = ""; // mặc định
            } else {
                $finalResult = "❌ Bài đánh giá không đạt";
                $cardClass = "assessment__fall"; // thêm class cho card fail
            }
          @endphp
          <div class="card h-100 m-2 shadow-sm {{ $cardClass }}">
            <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
              <span>{{ $assessment->title }}</span>
              @if($assessment->start_time->isCurrentWeek())
                <span class="badge bg-warning text-dark">NEW</span>
              @endif
            </div>
            <div class="card-body">
              @php     
                $start = $assessment->start_time;
                $weekOfMonth = intval(ceil($start->day / 7));
              @endphp
                      
              <div>
                <p><strong>Tuần {{ $weekOfMonth }} Tháng {{ $start->month }} Năm {{ $start->year }}</strong></p>
                <div><strong>Chủ đề:</strong> {{ $assessment->type }}</div>      
                <p><strong>Ngày bắt đầu:</strong> {{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</p>
                <p><strong>Ngày kết thúc:</strong> {{ $assessment->end_time->format('d/m/Y (H\Hi A)') }}</p>   
                <p><strong>Thời lượng làm bài:</strong> {{ $assessment->duration }} phút</p>
                <p><strong>Khối lượng kiến thức:</strong> {{ $assessment->quantity }} câu</p>
              </div>
              
              <hr>

              @php
                // Thứ tự trong tuần (vd: thứ 2 -> 1, Chủ nhật -> 7)
                $dayOfWeek = $assessment->start_time->dayOfWeekIso; 
                // Thứ tự buổi trong tuần (tạm tính theo index vòng foreach)
                $assessmentIndexInWeek = $loop->iteration; 
              @endphp
   
              <p><strong>Bài đánh giá số:</strong> {{ $assessmentIndexInWeek }} trong tuần (Thứ {{ $dayOfWeek }})</p>
              @if($ua)
                <p><strong>Ngày đánh giá:</strong> {{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</p>   
                <p><strong>Điểm:</strong> {{ $correct }}/{{ $total }}</p>                    
                <p><strong>Thời gian làm bài:</strong>
                  {{ $duration ? $duration . ' phút' : 'Chưa hoàn thành' }}
                </p>
                <p><strong>Hiệu suất làm bài:</strong> {{ $efficiency }} từ / giờ</p>  
              @else
                <p class="text-muted">Bạn chưa tham gia</p>
              @endif

              <hr>

              <!-- Tổng kết -->
              <p><strong>Hiệu suất làm bài:</strong> {{ $efficiency }} từ / giờ 
                (Yêu cầu: {{ $requiredEfficiency }} từ / giờ) 
                → {!! $efficiencyPassed ? '<span style="color:green;">Đạt</span>' : '<span style="color:red;">Không đạt</span>' !!}
              </p>

              <p><strong>Kết quả kiến thức:</strong> {{ $correct }}/{{ $total }} 
                (Yêu cầu: {{ $requiredCorrect }}) 
                → {!! $correctPassed ? '<span style="color:green;">Đạt</span>' : '<span style="color:red;">Không đạt</span>' !!}
              </p>

              <hr>

              <h4>{{ $finalResult }}</h4>
            </div>
            <div class="card-footer text-center">
              <a href="{{ route('assessments.summary', $assessment->id) }}" class="btn btn-info btn-sm">
                Xem kết quả
              </a>
            </div>
          </div>   
        @endforeach
      </div>
    </section>   
    <!-- End Section 5 - Buổi đánh giá kết thúc -->    

    <!-- Start Section 6 - Biểu đồ buổi đánh giá -->
    <section>
      <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalAssessmentModal">
        Biểu đồ tổng kết buổi đánh giá
      </button>
    </section>   
    <!-- Start Section 6 - Biểu đồ buổi đánh giá -->
           
    <!-- Modal Form Tạo buổi đánh giá -->
    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form action="{{ route('assessments.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="createAssessmentModalLabel">Tạo buổi đánh giá mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">

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

            <!-- Khoảng từ (chỉ hiện khi chọn 'Từ vựng') -->
            <div class="mb-3 vocabulary-range d-none">
              <label for="from" class="form-label">Mã từ bắt đầu</label>
              <input type="number" class="form-control" name="from" id="from" placeholder="Ví dụ: 1001">
            </div>
            <div class="mb-3 vocabulary-range d-none">
              <label for="to" class="form-label">Mã từ kết thúc</label>
              <input type="number" class="form-control" name="to" id="to" placeholder="Ví dụ: 1500">
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

            <!-- Mức độ kiến thức -->
            <div class="mb-3">
              <label for="level" class="form-label">Mức độ kiến thức</label>
              <select class="form-select" name="level" id="level" required>
                <option value="">-- Chọn mức độ --</option>
                <option value="basic">TACB (Cơ bản)</option>
                <option value="toeic">TOEIC (Trung bình)</option>
                <option value="ielts">IELTS (Khó nhất)</option>
              </select>
            </div>

            <!-- Độ khó kiến thức -->
            <div class="mb-3">
              <label for="difficulty" class="form-label">Độ khó kiến thức</label>
              <select class="form-select" name="difficulty" id="difficulty" required>
                <option value="">-- Chọn độ khó --</option>
                <option value="basic">Mức dễ (1 - 5) - Cơ bản</option>
                <option value="medium">Mức khá (6 - 7) - Trung bình</option>
                <option value="hard">Mức giỏi (7 - 8) - Khó</option>
                <option value="master">Mức xuất sắc (9 - 10)</option>
              </select>
            </div>
             
            <!-- Yêu cầu buổi đánh giá -->
            <div class="mb-3">
              <label for="require_completion" class="form-label">Hiệu suất hoàn thành (%)</label>
              <input type="number" class="form-control" name="require_completion" id="require_completion" min="0" max="100" placeholder="Ví dụ: 80">
            </div>

            <div class="mb-3">     
              <label for="require_score" class="form-label">Điểm số tối thiểu (Hệ 10)</label>
              <input type="number" class="form-control" name="require_score" id="require_score" min="0" placeholder="Ví dụ: 70">
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

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Tạo buổi đánh giá</button>
          </div>
        </form>
        </div>
      </div>
    </div>

             
    <!-- Modal Biểu đồ -->
    <div class="modal fade" id="modalAssessmentModal" tabindex="-1" aria-labelledby="modalAssessmentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="modalAssessmentModalLabel">Biểu đồ tổng kết</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6">
                <h3>1. Biểu đồ Gantt (Timeline)</h3>
                <canvas id="ganttChart" width="400" height="200"></canvas>
              </div>
              <div class="col-lg-6">
                <h3>2. Biểu đồ Cột</h3>
                <canvas id="barChart" width="400" height="200"></canvas>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <h3>3. Biểu đồ Đường</h3>
                <canvas id="lineChart" width="400" height="200"></canvas>
              </div>
              <div class="col-lg-6">
                <h3>4. Biểu đồ Tròn</h3>
                <canvas id="pieChart" width="400" height="200"></canvas>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <h3>5. Biểu đồ Radar</h3>
                <canvas id="radarChart" width="400" height="200"></canvas>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('topic').addEventListener('change', function() {
      const rangeFields = document.querySelectorAll('.vocabulary-range');
      if (this.value === 'Từ vựng') {
        rangeFields.forEach(el => el.classList.remove('d-none'));
      } else {
        rangeFields.forEach(el => el.classList.add('d-none'));
      }
    });
  </script>
@endsection
