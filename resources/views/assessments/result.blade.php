@extends('assessments.app')

@section('title', 'Summary')

@section('content')
  <div class="container-fluid">

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

    <style>
      #scrollTopBtn,
      #scrollBottomBtn {
        position: fixed;
        /* bám theo màn hình */
        right: 20px;
        /* cách lề phải */
        bottom: 20px;
        /* cách lề dưới */
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: antiquewhite;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        cursor: pointer;
      }

      #scrollTopBtn {
        bottom: 70px;
        /* cách nút dưới 1 đoạn */
      }

      #scrollBottomBtn {
        bottom: 20px;
      }

      .answer__active {
        background-color: #eaf4ff;
        /* nền xanh nhạt */
        border: 2px solid #007bff;
        /* viền xanh */
        border-radius: 6px;
        transition: all 0.3s ease;
      }
    </style>

    <style>
      .question-bar {
        height: 400px;
        overflow: scroll;
      }
    </style>

    <!--  -->
    @php
      $totalQuestions = $assessment->details->count();

      // Dự kiến (từ bảng assessments)
      $expectedMinutes = $assessment->duration ?? 0;
      $expectedWordsPerHour = $expectedMinutes > 0 ? round($totalQuestions / ($expectedMinutes / 60), 2) : 0;

      // Thực tế (từ user_assessments)
      $actualMinutes = $actualDuration ?? 0;
      $actualWordsPerHour = $actualMinutes > 0 ? round($totalQuestions / ($actualMinutes / 60), 2) : 0;

      // Điểm số
      $maxScore = $totalQuestions;
      $passScore = ceil($maxScore * 0.5); // 50% để qua
      $actualScore = $userScore ?? 0; // lấy điểm thực tế của user
      $isPassed = $actualScore >= $passScore;
    @endphp


    <div class="row">
      <div class="col-lg-4">
        <div class="block__1">
          <!-- Phần 1. Thông tin - Buổi đánh giá -->
          <div class="page">
            <h1>BÁO CÁO BÀI ĐÁNH GIÁ</h1>
            <h1>{{ $assessment->title }}</h1>
            @php
              \Carbon\Carbon::setLocale('vi');
            @endphp
            <p style="text-align:center; font-style:italic;">
              {{ 'Hà Nội, ' . \Carbon\Carbon::parse($assessment->start_time)->translatedFormat('l, \N\gà\y d/m/Y') }}
            </p>

            <!-- Phần 1 -->
            <h2>Phần 1. Thông tin bài đánh giá</h2>

            <!-- Thời gian -->
            <div class="info-block">
              <div class="section-title">1. Thời gian buổi đánh giá</div>
              <ul>
                <li>Bắt đầu: <span class="highlight">{{ $assessment->start_time->format('H\hi') }}</span></li>
                <li>Kết thúc: <span class="highlight">{{ $assessment->end_time->format('H\hi') }}</span></li>
                <li>=&gt; Tổng thời gian:
                  <span class="highlight">{{ $assessment->duration }} phút</span>
                </li>
              </ul>
            </div>

            <!-- Kiến thức -->
            <div class="info-block">
              <div class="section-title">2. Kiến thức buổi đánh giá</div>
              <ul>
                <li>Chủ đề chính: <span class="highlight"> {{ $assessment->type }} </span></li>
                <li>Mô tả: </li>
                <ul>
                  <li>Khối lượng kiến thức: <span class="highlight">{{ $assessment->quantity }} (Mã 1001 - 1500)</span>
                  </li>
                  <li>Phương pháp đánh giá: <span class="highlight"> {{ $assessment->method }} </span></li>
                </ul>
              </ul>
            </div>

            <!-- Yêu cầu -->
            <div class="info-block">
              <div class="section-title">3. Yêu cầu buổi đánh giá</div>
              <ul>
                <li>Điểm tối thiểu: <span class="highlight">8/10 (≥ {{ $assessment->quantity * 80 / 100 }} câu)</span>
                </li>
                <li>Hiệu suất tối thiểu: <span class="highlight">{{   $assessment->min_performance }}</span></li>
              </ul>
            </div>

            <!-- Chi tiết -->
            <div class="info-block">
              <div class="section-title">4. Chi tiết buổi đánh giá</div>
              <ul>
                <li>Ca 1: <span class="highlight">50 Phút</span></li>
                <li>Chấm bài: <span class="highlight">10 phút</span></li>
                <li>Kết thúc ca: <span class="highlight">Thời gian thừa để xem lại bài</span></li>
              </ul>
            </div>
          </div>
          <!-- Kết thúc - Phần 1. Thông tin - Buổi đánh giá -->

          <!-- Kết quả -->
          <div class="card_result">
            <!-- 1. Tiêu đề -->
            <h1 class="">Kết quả buổi đánh giá - Phương pháp: {{ $assessment->title }}</h1>

            <!-- 2. Điểm số -->
            <div class="card_result__point">
              <p><strong>Điểm (Số câu đúng):</strong> {{ $score }}/{{ $total }}</p>
              <p><strong>Điểm (Hệ 10):</strong> {{ round(($score / $total) * 10, 2) }} /{{ 10 }}</p>
            </div>

            <!-- 3. Chi tiết điểm số -->
            <div class="card_result_count">
              <span class="text-success">Đúng: {{ $correctCount }}</span> |
              <span class="text-danger">Sai: {{ $wrongCount }}</span> |
              <span class="text-secondary">Chưa trả lời: {{ $blankCount }}</span>
            </div>

            <div class="card_result__time">
              <p><strong>Thời gian:</strong> {{ $actualMinutes }} phút</p>
            </div>

            <!-- 4. Phần trăm điểm số -->
            @php
              // $total = $totalQuestions ?? 1;
              $correct = $correctCount ?? 0;
              $wrong = $wrongCount ?? 0;
              $blank = $blankCount ?? 0;

              $correctPercent = round(($correct / $total) * 100, 2);
              $wrongPercent = round(($wrong / $total) * 100, 2);
              $blankPercent = round(($blank / $total) * 100, 2);
            @endphp

            <div class="col-lg-12">
              <div class="progress my-3" style="height: 25px;">
                <!-- Đúng (xanh) -->
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $correctPercent }}%"
                  aria-valuenow="{{ $correctPercent }}" aria-valuemin="0" aria-valuemax="100">
                  {{ $correctPercent }}%
                </div>

                <!-- Sai (đỏ) -->
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $wrongPercent }}%"
                  aria-valuenow="{{ $wrongPercent }}" aria-valuemin="0" aria-valuemax="100">
                  {{ $wrongPercent }}%
                </div>

                <!-- Bỏ trống (xám, nếu có) -->
                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $blankPercent }}%"
                  aria-valuenow="{{ $blankPercent }}" aria-valuemin="0" aria-valuemax="100">
                  {{ $blankPercent }}%
                </div>
              </div>
            </div>
            <!-- Kết thúc - Phần 2. Kết quả - Buổi đánh giá -->
          </div>

          <!-- Hiệu suất -->
          <div class="card_result">
            <div class="performance mt-4">
              @php
                $totalQuestions = $assessment->details->count();
                $duration = $assessment->duration; // thời lượng lưu trong DB, tính bằng phút
                $wordsPerHour = $duration > 0 ? round($totalQuestions / ($duration / 60), 2) : 0;
              @endphp

              <div class="performance mt-4 mb-4">
                <h4>Hiệu suất</h4>
                <p>
                  <strong>Chủ đề đánh giá: </strong> {{ $assessment->type }}<br>
                  <strong>Phương pháp đánh giá: </strong> {{ $assessment->method }} <br>
                  <strong>Thời gian đánh giá (Dự kiến): </strong> {{ $duration }} phút<br>
                  <strong>Tổng số câu: </strong> {{ $totalQuestions }}<br>
                  <strong>Hiệu suất (Dự kiến):</strong> {{ $wordsPerHour }} từ / tiếng
                </p>
              </div>

              <!--  -->
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
                  <!-- Thời gian làm bài -->
                  <tr>
                    <td><strong>Thời gian làm bài</strong></td>
                    <td style="text-align:center;">{{ $expectedMinutes }} phút</td>
                    <td style="text-align:center;">{{ $actualMinutes }} phút</td>
                    <td style="text-align:center;">
                      @if($actualMinutes <= $expectedMinutes)
                        <span style="color:green; font-weight:bold;">Đạt ✅</span>
                      @else
                        <span style="color:red; font-weight:bold;">Không đạt ❌</span>
                      @endif
                    </td>
                  </tr>

                  <!-- Tổng số câu hỏi -->
                  <tr>
                    <td><strong>Tổng số câu hỏi</strong></td>
                    <td style="text-align:center;">{{ $totalQuestions }}</td>
                    <td style="text-align:center;">{{ $totalQuestions }}</td>
                    <td style="text-align:center;">
                      ✅ Luôn cố định
                    </td>
                  </tr>

                  <!-- Hiệu suất -->
                  <tr>
                    <td><strong>Hiệu suất</strong></td>
                    <td style="text-align:center;">{{ $expectedWordsPerHour }} từ / tiếng</td>
                    <td style="text-align:center;">{{ $actualWordsPerHour }} từ / tiếng</td>
                    <td style="text-align:center;">
                      @if($actualWordsPerHour >= $expectedWordsPerHour)
                        <span style="color:green; font-weight:bold;">Đạt ✅</span>
                      @else
                        <span style="color:red; font-weight:bold;">Không đạt ❌</span>
                      @endif
                    </td>
                  </tr>

                  <!-- Điểm số -->
                  <tr style="background:#fafafa;">
                    <td><strong>Điểm số</strong></td>
                    <td style="text-align:center;">
                      Tối đa: {{ $maxScore }} <br>
                      Tối thiểu: {{ $passScore }}
                    </td>
                    <td style="text-align:center;">
                      <p><strong>Điểm:</strong> {{ $score }}/{{ $total }}</p>
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
          </div>

          <!-- Xuất File -->
          <!-- Xuất PDF -->
          <a href="{{ route('assessments.export', $assessment->id) }}" class="btn btn-success">
            Xuất PDF
          </a>

          <!-- Question Next -->
          <div class="d-flex justify-content-between my-3">
            <button id="prevBtn" class="btn btn-outline-primary">⬅ Câu trước</button>
            <button id="nextBtn" class="btn btn-outline-primary">Câu tiếp theo ➡</button>
          </div>

          <!-- Phần 3. Điều hướng - Buổi đánh giá -->
          <div class="card_result__direct">
            <div class="mt-4 d-flex gap-2">
              <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                Quay lại danh sách bài đánh giá
              </a>

              <a href="{{ route('assessments.do', $assessment->id) }}" class="btn btn-warning">
                Làm lại bài này
              </a>

              <a href="{{ route('assessments.summary', $assessment->id) }}" class="btn btn-info">
                Xem tổng kết
              </a>
            </div>
          </div>
          <!-- Kết thúc - Phần 3. Điều hướng - Buổi đánh giá -->

        </div>
      </div>

      <div class="col-lg-8">
        <!-- Phần 2. Kết quả - Buổi đánh giá -->
        <div class="card_result">
          <!-- Thanh câu hỏi -->
          <div class="question-bar mb-4 d-flex flex-wrap" id="questionBar">
            @foreach($assessment->details as $index => $detail)
              @php
                $result = $detail->results->first();
                $statusClass = 'question-blank';
                if ($result) {
                  $statusClass = $result->is_correct ? 'question-correct' : 'question-wrong';
                }
              @endphp
              <div class="question-item rounded text-center me-2 mb-2 {{ $statusClass }}"
                data-question-index="{{ $index }}">
                {{ $index + 1 }}
              </div>
            @endforeach
          </div>

          <!-- Danh sách câu hỏi -->
          <ul class="list-group" id="questionList">
            @foreach($assessment->details as $index => $detail)
              @php
                $result = $detail->results->first();
              @endphp
              <li class="list-group-item question-block" id="question-{{ $index }}">
                <div class="question">
                  <strong>Câu {{ $index + 1 }}:</strong> {{ $detail->question }}
                </div>

                <div class="row mt-2">
                  <div class="col-md-6">
                    <strong>Đáp án đúng:</strong> {{ $detail->answer }}
                  </div>
                  <div class="col-md-6">
                    <strong>Câu trả lời của bạn:</strong> {{ $result->user_answer ?? 'Chưa trả lời' }}
                  </div>
                </div>

                <div class="result mt-2">
                  <strong>Kết quả:</strong>
                  @if($result && $result->is_correct)
                    <span class="text-success">Chính xác ✅</span>
                  @else
                    <span class="text-danger">Không chính xác ❌</span>
                  @endif
                </div>
              </li>
            @endforeach
          </ul>
          <!--  -->
        </div>
        <!-- Kết thúc - Phần 2. Kết quả - Buổi đánh giá -->

        <!-- Dạng Điền đáp án -->
        <div class="sheet">
          <h3>Kết quả bài làm</h3>
          <table border="1" cellspacing="0" cellpadding="8" width="100%">
            <thead style="background:#f2f2f2; text-align:center;">
              <tr>
                <th width="5%">Câu</th>
                <th width="35%">Đáp án đúng</th>
                <th width="35%">Câu trả lời của bạn</th>
                <th width="25%">Kết quả</th>
              </tr>
            </thead>
            <tbody>
              @foreach($assessment->details as $index => $detail)
                @php
                  $result = $detail->results->first();
                  $userAnswer = $result->user_answer ?? 'Chưa trả lời';
                  $isCorrect = $result && $result->is_correct;
                @endphp
                <tr>
                  <td style="text-align:center;">{{ $index + 1 }}</td>
                  <td>{{ $detail->answer }}</td>
                  <td>{{ $userAnswer }}</td>
                  <td style="text-align:center; color:{{ $isCorrect ? 'green' : 'red' }}">
                    @if($isCorrect)
                      ✅ Chính xác
                    @else
                      ❌ Không chính xác
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- Kết thúc - -->
      </div>
    </div>

    <!-- Scroll Top -->
    <button id="scrollTopBtn" class="btn btn-secondary">⬆</button>

    <!-- Scroll Bottom -->
    <button id="scrollBottomBtn" class="btn btn-secondary">⬇</button>
  </div>

  <!-- JS điều hướng -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const questionItems = document.querySelectorAll(".question-item");
      const questions = document.querySelectorAll(".list-group-item");

      questionItems.forEach((item, idx) => {
        item.addEventListener("click", () => {
          // Bỏ active ở tất cả
          questionItems.forEach(el => el.classList.remove("active"));
          questions.forEach(q => q.classList.remove("active-question"));

          // Active cả trên thanh và dưới list
          item.classList.add("active");
          questions[idx].classList.add("active-question");

          // Cuộn đến câu hỏi
          questions[idx].scrollIntoView({ behavior: "smooth", block: "center" });
        });
      });

      // Khi nhập input thì đánh dấu answered
      const inputs = document.querySelectorAll("input[name^='answers']");
      inputs.forEach((input, idx) => {
        input.addEventListener("input", () => {
          if (input.value.trim() !== "") {
            questionItems[idx].classList.add("answered");
          } else {
            questionItems[idx].classList.remove("answered");
          }
        });
      });
    });
  </script>

  <!-- Scroll -->
  <script>
    const btn = document.getElementById("scrollTopBtn");

    window.onscroll = function () {
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    };

    btn.onclick = function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };     
  </script>

  <script>
    const btnTop = document.getElementById("scrollTopBtn");
    const btnBottom = document.getElementById("scrollBottomBtn");

    window.onscroll = function () {
      // Hiện 2 nút khi lướt quá 100px
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        btnTop.style.display = "block";
        btnBottom.style.display = "block";
      } else {
        btnTop.style.display = "none";
        btnBottom.style.display = "block"; // nút xuống vẫn hiện khi ở đầu trang
      }
    };

    btnTop.onclick = function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };

    btnBottom.onclick = function () {
      window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
    };
  </script>

  <!--  -->
  <script>
    const questions = document.querySelectorAll('.list-group-item');
    let currentIndex = 0;

    function scrollToQuestion(idx) {
      if (idx >= 0 && idx < questions.length) {
        // Xóa class cũ
        questions.forEach(q => q.classList.remove('answer__active'));

        // Cập nhật index
        currentIndex = idx;

        // Thêm class cho câu hiện tại
        questions[idx].classList.add('answer__active');

        // Cuộn đến câu
        questions[idx].scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

    document.getElementById('prevBtn').addEventListener('click', () => scrollToQuestion(currentIndex - 1));
    document.getElementById('nextBtn').addEventListener('click', () => scrollToQuestion(currentIndex + 1));

    // Gọi lần đầu cho câu 1
    scrollToQuestion(0);
  </script>
@endsection