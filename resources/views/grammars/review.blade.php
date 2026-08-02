@extends('grammars.grammar')

@section('title', 'Ôn Tập Ngữ Pháp: ' . $lesson->title)

@section('content_grammar')
  <div class="container py-4 px-3" style="max-width: 900px; background: #f8fafc; min-height: 100vh;">
    <!-- Review Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <a href="{{ route('grammar.lesson.show', $lesson->id) }}" class="text-decoration-none text-muted fw-bold small d-inline-flex align-items-center gap-1">
          ← Quay lại bài học
        </a>
        <h1 class="fw-bold text-dark mt-2 mb-1" style="font-size: 26px; letter-spacing: -0.5px;">🔄 Thử Thách Ôn Tập Củng Cố</h1>
        <p class="text-muted mb-0">Bài học: <span class="fw-semibold text-primary">{{ $lesson->title }}</span></p>
      </div>

      <!-- Live Stopwatch Badge -->
      <div class="card border-0 shadow-sm px-4 py-2 text-white d-flex align-items-center justify-content-center bg-dark" style="border-radius: 12px; min-width: 160px;">
        <div class="small text-muted fw-bold mb-0.5" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Thời gian ôn tập</div>
        <div class="fw-extrabold font-monospace text-success" id="live-timer" style="font-size: 20px;">00:00</div>
      </div>
    </div>

    <!-- Review Form -->
    <form action="{{ route('grammar.lessons.submit-review', $lesson->id) }}" method="POST" id="review-submission-form">
      @csrf
      <!-- Hidden field to pass duration seconds -->
      <input type="hidden" name="duration_seconds" id="duration_seconds" value="0">

      <!-- Context Info Banner -->
      <div class="alert alert-warning border-0 shadow-sm p-3 mb-4 rounded-3 d-flex align-items-start gap-2.5" style="background-color: #fffbeb; color: #b45309;">
        <span class="fs-5" style="margin-top: -2px;">💡</span>
        <div class="small">
          <strong class="d-block mb-1">Hướng dẫn làm bài ôn tập củng cố:</strong>
          Hệ thống sẽ dựa vào nội dung bài học để xây dựng khung bài ôn. Bạn cần ghi nhớ và điền lại công thức, diễn đạt lại các cách dùng, và dịch chính xác các câu ví dụ từ Tiếng Việt sang Tiếng Anh. Sau khi nộp bài, hệ thống sẽ tự động so sánh, cho điểm chi tiết và đánh giá mức độ <strong>Đạt / Chưa đạt</strong> của bạn.
        </div>
      </div>

      @if($isHtmlFormat && isset($sections))
        @foreach($sections as $secIndex => $sec)
          @php
            $secId = $sec['id'];
            $parsed = $questionsBySection[$secId] ?? null;
            $hasFormula = $parsed && !empty($parsed['formulas']);
            $hasUsage = $parsed && !empty($parsed['usages']);
            $hasExample = $parsed && !empty($parsed['examples']);
          @endphp

          @if($hasFormula || $hasUsage || $hasExample)
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; border-left: 5px solid #3b82f6 !important;">
              <h3 class="fw-bold text-dark mb-4 border-bottom pb-2" style="font-size: 18px; text-align: left;">
                📚 Phần {{ $secIndex + 1 }}: {{ $sec['title'] }}
              </h3>

              <!-- 1. Công thức -->
              @if($hasFormula)
                <div class="mb-4 p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                  <h4 class="fw-bold text-primary mb-2 d-flex align-items-center gap-2" style="font-size: 14px;">
                    <span>🔹 1. Tái Hiện Công Thức (Formula Recall)</span>
                  </h4>
                  <p class="text-muted small mb-3">Hãy điền công thức cấu trúc tổng quát của phần này tương ứng với các thể:</p>

                  <div class="row g-3">
                    @if(isset($parsed['formulas']['affirmative']) && trim($parsed['formulas']['affirmative']) !== '')
                      <div class="col-12">
                        <label class="form-label fw-bold text-secondary small">Thể khẳng định (+) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-monospace border-primary-subtle" name="answers_formula[{{ $secId }}][affirmative]" placeholder="Ví dụ: S + V..." required style="border-radius: 8px;">
                      </div>
                    @endif
                    @if(isset($parsed['formulas']['negative']) && trim($parsed['formulas']['negative']) !== '')
                      <div class="col-12">
                        <label class="form-label fw-bold text-secondary small">Thể phủ định (-) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-monospace border-danger-subtle" name="answers_formula[{{ $secId }}][negative]" placeholder="Ví dụ: S + not..." required style="border-radius: 8px;">
                      </div>
                    @endif
                    @if(isset($parsed['formulas']['question']) && trim($parsed['formulas']['question']) !== '')
                      <div class="col-12">
                        <label class="form-label fw-bold text-secondary small">Thể nghi vấn (?) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-monospace border-success-subtle" name="answers_formula[{{ $secId }}][question]" placeholder="Ví dụ: Auxiliary + S + V...?" required style="border-radius: 8px;">
                      </div>
                    @endif
                  </div>
                </div>
              @endif

              <!-- 2. Cách dùng -->
              @if($hasUsage)
                <div class="mb-4 p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                  <h4 class="fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 14px; color: #7c3aed !important;">
                    <span>📘 2. Diễn Đạt Quy Tắc & Cách Dùng (Usage Recall)</span>
                  </h4>
                  <p class="text-muted small mb-3">Điền các quy tắc / tình huống sử dụng của phần này (mỗi ô tương ứng với 1 cách dùng cốt lõi):</p>

                  <div class="d-flex flex-column gap-3">
                    @foreach($parsed['usages'] as $i => $u)
                      <div>
                        <label class="form-label fw-semibold text-secondary small">Cách dùng thứ {{ $i + 1 }} <span class="text-danger">*</span></label>
                        <textarea name="answers_usage[{{ $secId }}][]" class="form-control" rows="2" placeholder="Ví dụ: Diễn tả..." required style="border-radius: 8px;"></textarea>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 3. Ví dụ -->
              @if($hasExample)
                <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                  <h4 class="fw-bold text-success mb-2 d-flex align-items-center gap-2" style="font-size: 14px;">
                    <span>✏️ 3. Thử Thách Dịch Thuật Câu Ví Dụ (Example Translation)</span>
                  </h4>
                  <p class="text-muted small mb-3">Dựa vào gợi ý / ngữ cảnh dưới đây, hãy dịch chính xác câu đó sang Tiếng Anh:</p>

                  <div class="d-flex flex-column gap-3">
                    @foreach($parsed['examples'] as $index => $ex)
                      <div class="p-3 bg-white rounded-3 border" style="border-color: #e2e8f0 !important;">
                        <div class="fw-bold text-dark mb-2 small d-flex justify-content-between">
                          <span>Câu số {{ $index + 1 }}</span>
                          <span class="text-muted fw-normal">Ngữ cảnh: {{ $ex['note'] ?? 'Thông thường' }}</span>
                        </div>
                        <input type="text" class="form-control font-monospace border-success-subtle shadow-sm" name="answers_example[{{ $secId }}][]" placeholder="Dịch câu Tiếng Anh tương ứng..." required style="border-radius: 8px; background-color: #fafafa;">
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

            </div>
          @endif
        @endforeach
      @else
        <!-- Section 1: Recall Formula -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; border-left: 5px solid #3b82f6 !important;">
          <h4 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
            <span>🔹 1. Tái Hiện Công Thức (Formula Recall)</span>
            <span class="badge bg-primary-subtle text-primary border px-2 py-1 rounded ms-auto" style="font-size: 11px;">Tối đa: 30 điểm</span>
          </h4>
          <p class="text-muted small mb-3">Hãy điền công thức cấu trúc tổng quát của thì này tương ứng với các thể:</p>

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-bold text-secondary small">Thể khẳng định (+) <span class="text-danger">*</span></label>
              <input type="text" class="form-control font-monospace border-primary-subtle" name="answers_affirmative" placeholder="Ví dụ: S + V(s/es) / S + am/is/are + O" required style="border-radius: 8px;">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold text-secondary small">Thể phủ định (-) <span class="text-danger">*</span></label>
              <input type="text" class="form-control font-monospace border-danger-subtle" name="answers_negative" placeholder="Ví dụ: S + do/does + not + V / S + am/is/are + not + O" required style="border-radius: 8px;">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold text-secondary small">Thể nghi vấn (?) <span class="text-danger">*</span></label>
              <input type="text" class="form-control font-monospace border-success-subtle" name="answers_question" placeholder="Ví dụ: Do/Does + S + V? / Am/Is/Are + S + O?" required style="border-radius: 8px;">
            </div>
          </div>
        </div>

        <!-- Section 2: Recall Usages -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; border-left: 5px solid #8b5cf6 !important;">
          <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 17px; color: #7c3aed !important;">
            <span style="color: #8b5cf6;">📘 2. Diễn Đạt Quy Tắc & Cách Dùng (Usage Recall)</span>
            <span class="badge bg-purple-subtle text-purple border px-2 py-1 rounded ms-auto" style="font-size: 11px; background-color: #f3e8ff; border-color: #e9d5ff !important; color: #7c3aed !important;">Tối đa: 30 điểm</span>
          </h4>
          <p class="text-muted small mb-3">Điền các quy tắc / tình huống sử dụng của thì này (mỗi ô tương ứng với 1 cách dùng cốt lõi):</p>

          <div class="d-flex flex-column gap-3">
            @php
              $usagesCount = count($content['usage'] ?? [1]);
            @endphp
            @for($i = 0; $i < $usagesCount; $i++)
              <div>
                <label class="form-label fw-semibold text-secondary small">Cách dùng thứ {{ $i + 1 }} <span class="text-danger">*</span></label>
                <textarea name="answers_usage[]" class="form-control" rows="2" placeholder="Ví dụ: Diễn tả một thói quen hoặc hành động lặp đi lặp lại ở hiện tại..." required style="border-radius: 8px;"></textarea>
              </div>
            @endfor
          </div>
        </div>

        <!-- Section 3: Translate Examples -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; border-left: 5px solid #10b981 !important;">
          <h4 class="fw-bold text-success mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
            <span>✏️ 3. Thử Thách Dịch Thuật Câu Ví Dụ (Example Translation)</span>
            <span class="badge bg-success-subtle text-success border px-2 py-1 rounded ms-auto" style="font-size: 11px; background-color: #ecfdf5; border-color: #a7f3d0 !important; color: #047857 !important;">Tối đa: 40 điểm</span>
          </h4>
          <p class="text-muted small mb-3">Dựa vào gợi ý / ngữ cảnh dưới đây, hãy dịch chính xác câu đó sang Tiếng Anh:</p>

          <div class="d-flex flex-column gap-3">
            @foreach($examples as $index => $ex)
              <div class="p-3 bg-light rounded-3 border" style="border-color: #e2e8f0 !important;">
                <div class="fw-bold text-dark mb-2 small d-flex justify-content-between">
                  <span>Câu số {{ $index + 1 }}</span>
                  <span class="text-muted fw-normal">Ngữ cảnh: {{ $ex['note'] ?? 'Thông thường' }}</span>
                </div>
                
                <!-- English translation input field -->
                <input type="text" class="form-control font-monospace border-success-subtle shadow-sm" name="answers_example[]" placeholder="Dịch câu Tiếng Anh tương ứng..." required style="border-radius: 8px; background-color: #fafafa;">
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Submit Block -->
      <div class="card border-0 shadow-sm p-4 text-center rounded-4 mb-5" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white;">
        <h5 class="fw-bold mb-2" style="font-size: 16px;">Sẵn sàng hoàn thành bài ôn tập?</h5>
        <p class="text-muted small mb-3" style="color: #94a3b8 !important;">Hãy kiểm tra kỹ các câu trả lời trước khi nộp. Hệ thống sẽ so sánh tự động ngay lập tức.</p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <a href="{{ route('grammar.lesson.show', $lesson->id) }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 8px;">
            Hủy bỏ
          </a>
          <button type="submit" class="btn btn-success px-5 py-2 fw-bold d-flex align-items-center gap-2 shadow" style="border-radius: 8px; background-color: #10b981; border-color: #10b981;">
            🚀 Nộp Bài & Chấm Điểm
          </button>
        </div>
      </div>

    </form>
  </div>

  <!-- Live Stopwatch JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let seconds = 0;
      const timerElement = document.getElementById('live-timer');
      const hiddenDuration = document.getElementById('duration_seconds');

      // Ticking interval
      const timerInterval = setInterval(function () {
        seconds++;
        hiddenDuration.value = seconds;

        // Formats seconds to mm:ss
        let mins = Math.floor(seconds / 60);
        let secs = seconds % 60;
        
        mins = mins < 10 ? '0' + mins : mins;
        secs = secs < 10 ? '0' + secs : secs;

        timerElement.textContent = `${mins}:${secs}`;
      }, 1000);

      // Stop timer on form submit to capture final seconds
      const form = document.getElementById('review-submission-form');
      form.addEventListener('submit', function () {
        clearInterval(timerInterval);
      });
    });
  </script>
@endsection
