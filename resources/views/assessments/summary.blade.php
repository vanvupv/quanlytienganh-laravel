@extends('assessments.app')

@section('title', 'Summary')

@section('content')
  <div class="container my-4">
    <!-- Phần 1. Thông tin buổi đánh giá -->
    <style>
      .page {
        width: 100%;
        /* min-height: 297mm; */
        margin: 20px auto;
        /* padding: 25mm 20mm; */
        padding: 16px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
      }

      .block__1 {
        position: sticky;
        top: 20px;
        /* cách mép trên 20px */
        background: #f8f9fa;
        padding: 15px;
        border: 1px solid #ddd;
        /*  */
        overflow: scroll;
        height: 100vh;

      }

      .card_result {
        padding: 24px 16px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        /*  */
        margin-bottom: 16px;
      }

      .card_result:last-child {
        margin-bottom: 0px;
      }

      .card_result__point {
        margin-bottom: 16px;
      }

      .card_result_count {
        display: flex;
        justify-content: start;
        text-align: center;
        gap: 8px;
        /*  */
        margin-bottom: 16px;
      }

      h1,
      h2 {
        text-align: center;
        margin: 0 0 15px 0;
      }

      h2 {
        margin-top: 25px;
        font-size: 18px;
        text-transform: uppercase;
      }

      .info-block {
        margin-bottom: 20px;
      }

      .info-block ul {
        margin: 5px 0 0 20px;
      }

      .info-block ul li {
        margin: 4px 0;
      }

      .highlight {
        font-weight: bold;
      }

      .section-title {
        font-weight: bold;
        margin-bottom: 6px;
        text-decoration: underline;
      }
    </style>

    <!-- Start Section 1 -->
    <section class="summary__section--1 mb-4">
      <div class="card_result">
        <!-- Phần 1. Tiêu đề -->
        <div class="mb-4">
          <h1 class="mb-3">📊 Tổng kết buổi đánh giá: {{ $assessment->title }}</h1>
          <p>
            <strong>Mã bài:</strong> #{{ $assessment->id }} |
            <strong>Chủ đề đánh giá:</strong> {{ $assessment->type }} |
            <strong>Số câu:</strong> {{ $total }} |
            <strong>Thời gian:</strong> {{ $assessment->duration }} phút |
            <strong>Ngày đánh giá:</strong> {{ $assessment->start_time->format('d/m/Y (H\Hi A)') }}
          </p>
        </div>
      </div>
    </section>
    <!-- End Section 1 -->

    <!-- Start Section 2 -->
    <section class="summary__section--2">
      <div class="row">
        <!-- Section 1. Kết quả của bản thân -->
        <div class="col-lg-4">
          <div class="block__assessment_summary">
            <!-- Phần 1. Kết quả cá nhân -->
            <div class="card_result mb-4">
              <h3>Kết quả của bạn</h3>
              <div class="card_result__point">
                <p><strong>Điểm:</strong> {{ $correctCount }}/{{ $total }}</p>
                <p><strong>Thang 10:</strong> {{ round(($correctCount / $total) * 10, 2) }}/10</p>
              </div>

              <div class="card_result_count">
                <span class="text-success">Đúng: {{ $correctCount }}</span> |
                <span class="text-danger">Sai: {{ $wrongCount }}</span> |
                <span class="text-secondary">Chưa trả lời: {{ $blankCount }}</span>
              </div>

              <div class="card_result__time">
                @php
                  $actualMinutes = '';
                @endphp
                <p><strong>Thời gian làm:</strong> {{ $actualMinutes }} phút</p>
              </div>

              <!-- Progress bar chi tiết -->
              <div class="progress my-3" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $correctPercent }}%">
                  {{ $correctPercent }}%
                </div>
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $wrongCount }}%">
                  {{ $wrongCount }}%
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $blankCount }}%">
                  {{ $blankCount }}%
                </div>
              </div>
            </div>

            <!-- Phần 5. Hiệu suất -->
            <div class="card_result">
              <h3>📈 Hiệu suất</h3>
              @php
                $totalQuestions = $assessment->details->count();
                $duration = $assessment->duration;
                $wordsPerHour = $duration > 0 ? round($totalQuestions / ($duration / 60), 2) : 0;
              @endphp

              <p>
                <strong>Chủ đề: </strong> {{ $assessment->type }} <br>
                <strong>Phương pháp: </strong> {{ $assessment->method }} <br>
                <strong>Thời gian: </strong> {{ $duration }} phút <br>
                <strong>Tổng số câu: </strong> {{ $totalQuestions }} <br>
                <strong>Hiệu suất: </strong> {{ $wordsPerHour }} từ/tiếng
              </p>

              <h4>Bảng đánh giá hiệu suất</h4>
              <table border="1" cellspacing="0" cellpadding="8" width="100%">
                <thead style="background:#f2f2f2; text-align:center;">
                  <tr>
                    <th>Tiêu chí</th>
                    <th>Dự kiến</th>
                    <th>Thực tế</th>
                    <th>Đánh giá</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>Thời gian làm bài</strong></td>
                    @php
                      // --- Các biến cơ bản ---
                      $totalQuestions = $assessment->details->count();
                      $duration = $assessment->duration; // phút (trong DB)
                      $expectedMinutes = $duration;             // thời gian dự kiến = thời lượng đề
                      $actualMinutes = $actualMinutes ?? 0;   // bạn phải truyền từ controller (thời gian làm thực tế)

                      // Hiệu suất
                      $expectedWordsPerHour = $duration > 0 ? round($totalQuestions / ($duration / 60), 2) : 0;
                      $actualWordsPerHour = $actualMinutes > 0 ? round($totalQuestions / ($actualMinutes / 60), 2) : 0;

                      // Điểm số
                      $score = $score ?? 0;
                      $total = $total ?? 1;
                      $maxScore = $total;       // điểm tối đa = tổng số câu
                      $passScore = round($total * 0.5); // ví dụ: qua môn nếu đúng 50%
                      $isPassed = $score >= $passScore;
                    @endphp

                    <td style="text-align:center;">{{ $assessment->duration }} phút</td>
                    <td style="text-align:center;">{{ $actualMinutes }} phút</td>
                    <td style="text-align:center;">
                      @if($actualMinutes <= $expectedMinutes)
                        <span style="color:green; font-weight:bold;">Đạt ✅</span>
                      @else
                        <span style="color:red; font-weight:bold;">Không đạt ❌</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Tổng số câu hỏi</strong></td>
                    <td style="text-align:center;">{{ $totalQuestions }}</td>
                    <td style="text-align:center;">{{ $totalQuestions }}</td>
                    <td style="text-align:center;">✅ Luôn cố định</td>
                  </tr>
                  <tr>
                    <td><strong>Hiệu suất</strong></td>
                    <td style="text-align:center;">{{ $expectedWordsPerHour }} từ/tiếng</td>
                    <td style="text-align:center;">{{ $actualWordsPerHour }} từ/tiếng</td>
                    <td style="text-align:center;">
                      @if($actualWordsPerHour >= $expectedWordsPerHour)
                        <span style="color:green; font-weight:bold;">Đạt ✅</span>
                      @else
                        <span style="color:red; font-weight:bold;">Không đạt ❌</span>
                      @endif
                    </td>
                  </tr>
                  <tr style="background:#fafafa;">
                    <td><strong>Điểm số</strong></td>
                    <td style="text-align:center;">
                      Tối đa: {{ $maxScore }} <br>
                      Tối thiểu: {{ $passScore }}
                    </td>
                    <td style="text-align:center;">
                      {{ $correctCount }}/{{ $total }}
                    </td>
                    <td style="text-align:center;">
                      @if($isPassed)
                        <span style="color:green; font-weight:bold;">Đạt ✅</span>
                      @else
                        <span style="color:red; font-weight:bold;">Không đạt ❌</span>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Phần 6 - Từ vựng ôn tập lại trong bài -->
            <div class="card mb-4">
              <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📚 Danh sách từ vựng sai</h5>
                @if(!$wrongWords->isEmpty())
                  <!-- Nút mở modal -->
                  <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#reviewAllModal">
                    Ôn tập lại tất cả
                  </button>
                @endif
              </div>
              <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                @if($wrongWords->isEmpty())
                  <p class="text-muted mb-0">Chúc mừng! Bạn đã hoàn thành xuất sắc mà không sai câu nào.</p>
                @else
                  <ul class="list-group list-group-flush">
                    @foreach($wrongWords as $word)
                      <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <strong>{{ $word->word }}</strong>
                            <div class="text-muted small">Câu trả lời: <span class="text-danger">{{ $word->user_answer ?? '(Bỏ trống)' }}</span></div>
                          </div>
                          <span class="badge bg-success">Đáp án đúng: {{ $word->answer }}</span>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>
            </div>

            <!-- Modal Ôn tập lại tất cả -->
            <div class="modal fade" id="reviewAllModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">📖 Ôn tập lại từ vựng sai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Từ vựng</th>
                          <th>Nghĩa</th>
                          <th>Số lần sai</th>
                          <th>Ghi chú</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($wrongWords as $index => $word)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $word->word }}</strong></td>
                            <td>{{ $word->answer }}</td>
                            <td>{{ $word->user_answer }}</td>
                            <td>
                              <input type="text" class="form-control" placeholder="Ghi chú/nhập lại từ...">
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success">Hoàn tất ôn tập</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Section 2. Kết quả của lớp -->
        <div class="col-lg-8">
          <!-- Phần 1. Xếp hạng lớp (toàn lớp) -->
          <div class="row text-center mb-4">
            <div class="col-md-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h6>📊 Điểm trung bình</h6>
                  <p class="display-6">{{ $avgScore }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h6>🏆 Cao nhất</h6>
                  <p class="display-6">{{ $highestScore }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h6>📉 Thấp nhất</h6>
                  <p class="display-6">{{ $lowestScore }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Phần 2. Thanh đáp án -->
          <div class="mb-4">
            <div class="progress" style="height: 30px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $correctPercent }}%">
                {{ $correctPercent }}% đúng
              </div>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <span class="text-success">Đúng: {{ $correctCount }}</span>
              <span class="text-danger">Sai: {{ $wrongCount }}</span>
              <span class="text-muted">Bỏ trống: {{ $blankCount }}</span>
            </div>
          </div>

          <!-- Phần 3. Danh sách lớp -->
          <div class="card mb-4">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0">📊 Bảng theo dõi hiệu suất</h5>
            </div>
            <div class="card-body">
              @if($performanceTracking->isEmpty())
                <p class="text-muted">Chưa có dữ liệu hiệu suất.</p>
              @else
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Học sinh</th>
                      <th>Điểm (số câu đúng)</th>
                      <th>Điểm (%)</th>
                      <th>Thời gian làm</th>
                      <th>Ngày hoàn thành</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($performanceTracking as $index => $row)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['user_name'] }}</td>
                        <td>{{ $row['score'] }}</td>
                        <td>{{ $row['score_percent'] }}%</td>
                        <td>{{ $row['duration'] }}</td>
                        <td>{{ $row['finished_at'] ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>

          <!-- Phần 4. Biểu đồ -->
          <div class="row mb-4">
            <div class="col-md-6">
              <canvas id="categoryAccuracy"></canvas>
            </div>
            <div class="col-md-6">
              <canvas id="scoreDistribution"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 2 -->
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Phân bố điểm
    new Chart(document.getElementById("scoreDistribution"), {
      type: 'pie',
      data: {
        labels: @json(array_keys($scoreDistribution)),
        datasets: [{
          data: @json(array_values($scoreDistribution)),
          backgroundColor: ["#f87171", "#facc15", "#60a5fa", "#34d399"]
        }]
      }
    });

    // Độ chính xác theo kỹ năng
    const skillStats = @json($skillStats ?? []);
    const skillLabels = Object.keys(skillStats);
    const skillAccuracies = Object.values(skillStats).map(s => s.accuracy);

    new Chart(document.getElementById("categoryAccuracy"), {
      type: 'bar',
      data: {
        labels: skillLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
          label: 'Độ chính xác (%)',
          data: skillAccuracies,
          backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Độ chính xác theo kỹ năng (%)'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });
  </script>
@endsection