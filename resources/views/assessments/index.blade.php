@extends('assessments.app')

@section('title', 'Đề Thi & Đánh Giá Năng Lực')

@section('content')
  <style>
  .assessment__fall {
    border: 2px solid #dc3545 !important;
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
      <h1 class="mb-4">Đề Thi & Đánh Giá Năng Lực</h1>
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
    
    <!-- Start Section 3 - Buổi đánh giá sắp diễn ra -->
    <section class="mb-5">
      <div class="card border-0 p-0 shadow-none">
        <div class="card card__upComing mb-4">
          <!-- Sắp diễn ra -->
          <div class="section__4_assessment--upComing">
            <h4>Buổi sắp diễn ra</h4>
            <div class="table-responsive">
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
                    <th>Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($upcomingAssessments as $assessment)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $assessment->id }}</td>
                      <td>{{ $assessment->title }}</td>
                      <td>{{ $assessment->type }}</td>
                      <td>{{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</td>
                      <td>{{ $assessment->end_time->format('d/m/Y (H\Hi A)') }}</td>
                      <td>{{ $assessment->quantity }}</td>
                      <td>{{ $assessment->duration }} phút </td>
                      <td>{{ $assessment->min_score }} Điểm - {{ $assessment->min_performance }} %  </td>
                      <td><span class="badge bg-warning">Sắp diễn ra</span></td>
                      <td>
                        <button class="btn btn-sm btn-secondary" disabled>Chưa bắt đầu</button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="11" class="text-center text-muted">Không có buổi đánh giá nào sắp diễn ra.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
                  
        <div class="card card__onGoing">
          <!-- Đang diễn ra -->                                      
          <div class="section__4_assessment--onGoing">
            <h4>Buổi đang diễn ra</h4>
            <div class="table-responsive">
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
                    <th>Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($ongoingAssessments as $assessment)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $assessment->id }}</td>
                      <td>{{ $assessment->title }}</td>
                      <td>{{ $assessment->type }}</td>
                      <td>{{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}</td>
                      <td>{{ $assessment->end_time->format('d/m/Y (H\Hi A)') }}</td>
                      <td>{{ $assessment->quantity }}</td>
                      <td>{{ $assessment->duration }} phút </td>
                      <td>{{ $assessment->min_score }} Điểm - {{ $assessment->min_performance }} %  </td>
                      <td><span class="badge bg-primary">Đang diễn ra</span></td>
                      <td>
                        <a href="{{ route('assessments.do', $assessment->id) }}" class="btn btn-sm btn-success">Vào thi</a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="11" class="text-center text-muted">Không có buổi đánh giá nào đang diễn ra.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> 
    </section>
    <!-- End Section 3 - Buổi đánh giá sắp diễn ra -->

    <!-- Start Section 4 - Buổi đánh giá kết thúc -->  
    <section class="mb-5">
      <h4>Buổi đánh giá đã hoàn thành</h4>
      <div class="finished-slider">
        @forelse($assessment_full as $assessment)
          @php
            $ua = $assessment->userAssessments->firstWhere('user_id', auth()->id() ?? 1);

            $correct = 0;
            $total = 0;
            $duration = null;
            $efficiency = 0;

            if ($ua) {
              $correct = $assessment->results
                ->where('user_id', $ua->user_id)
                ->where('is_correct', 1)
                ->count();

              $total = $assessment->results
                ->where('user_id', $ua->user_id)
                ->count();

              $duration = $ua->finished_at && $ua->started_at
                ? $ua->finished_at->diffInMinutes($ua->started_at)
                : null;

              $efficiency = $duration > 0 ? round(($total / $duration) * 60, 2) : 0;
            }
            
            $requiredEfficiency = 600;
            $requiredCorrect = 100;
            
            $efficiencyPassed = $efficiency > $requiredEfficiency;
            $correctPassed = $correct >= $requiredCorrect;

            if ($correctPassed || ($correctPassed && $efficiencyPassed)) {
                $finalResult = "✅ Bài đánh giá đạt yêu cầu";
                $cardClass = "";
            } else {
                $finalResult = "❌ Bài đánh giá không đạt";
                $cardClass = "assessment__fall";
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
                $dayOfWeek = $assessment->start_time->dayOfWeekIso; 
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
        @empty
          <div class="text-center text-muted p-4">Không có buổi đánh giá nào đã hoàn thành.</div>
        @endforelse
      </div>
    </section>   
    <!-- End Section 4 - Buổi đánh giá kết thúc -->    
  </div>
@endsection
