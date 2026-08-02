@extends('grammars.grammar')

@section('title', 'Chi Tiết Bài Ôn Tập - Điểm: ' . $review->score)

@section('content_grammar')
  <div class="container py-4 px-3" style="max-width: 900px; background: #f8fafc; min-height: 100vh;">
    
    <!-- Action Header -->
    <div class="mb-4">
      <a href="{{ route('grammar.lesson.show', $review->lesson_id) }}" class="text-decoration-none text-muted fw-bold small d-inline-flex align-items-center gap-1">
        ← Quay lại bài học
      </a>
      <h1 class="fw-bold text-dark mt-2 mb-1" style="font-size: 26px; letter-spacing: -0.5px;">📊 Báo Cáo Chi Tiết Bài Ôn Tập</h1>
      <p class="text-muted mb-0">Bài học: <span class="fw-semibold text-primary">{{ $review->lesson->title }}</span> • Ngày ôn: {{ $review->created_at->format('H:i - d/m/Y') }}</p>
    </div>

    <!-- Overall KPI Banner Card -->
    <div class="card border-0 shadow-sm p-4 mb-4 text-white position-relative overflow-hidden" 
         style="border-radius: 20px; background: linear-gradient(135deg, {{ $review->passed ? '#059669, #10b981' : '#dc2626, #ef4444' }});">
      
      <!-- Decorative circle -->
      <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; right: -30px; top: -30px;"></div>

      <div class="row align-items-center position-relative">
        <div class="col-md-7 mb-3 mb-md-0">
          <div class="small fw-bold text-white-50 uppercase tracking-wider mb-1" style="text-transform: uppercase; font-size: 11px;">Kết Quả Đạt Được</div>
          <h2 class="fw-extrabold mb-2" style="font-size: 32px;">
            {{ $review->passed ? 'Chúc mừng! Bạn đã ĐẠT' : 'CHƯA ĐẠT (Cần cố gắng thêm)' }}
          </h2>
          <p class="mb-0 text-white-50 small">
            Điểm chuẩn yêu cầu của bài học này là: <strong>{{ $review->lesson->passing_score }}%</strong>. 
            Điểm số này đã được tự động đồng bộ hóa lên hệ thống phân tích học lực chung.
          </p>
        </div>

        <div class="col-md-5 d-flex gap-3 justify-content-md-end justify-content-start">
          <!-- Score Badge -->
          <div class="bg-white text-dark p-3 text-center shadow-sm" style="border-radius: 16px; min-width: 110px;">
            <div class="small text-muted fw-bold mb-0.5" style="font-size: 10px; text-transform: uppercase;">Điểm số</div>
            <div class="fw-extrabold text-primary" style="font-size: 28px; line-height: 1;">{{ $review->score }}</div>
            <div class="small text-muted fw-bold" style="font-size: 10px;">trên 100</div>
          </div>
          <!-- Duration Badge -->
          <div class="bg-white text-dark p-3 text-center shadow-sm" style="border-radius: 16px; min-width: 110px;">
            <div class="small text-muted fw-bold mb-0.5" style="font-size: 10px; text-transform: uppercase;">Thời gian làm</div>
            <div class="fw-extrabold font-monospace text-warning" style="font-size: 24px; line-height: 1.2;">
              @php
                $minutes = floor($review->duration_seconds / 60);
                $seconds = $review->duration_seconds % 60;
              @endphp
              {{ $minutes > 0 ? $minutes . 'm' : '' }}{{ $seconds }}s
            </div>
            <div class="small text-muted fw-bold" style="font-size: 10px;">hoàn thành</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Comparative Sections Breakdown -->
    @php
      $answers = $review->answers;
      $isHtmlFormat = isset($answers['is_html_format']) && $answers['is_html_format'];
      $formulas = !$isHtmlFormat ? ($answers['formulas'] ?? []) : [];
      $usages = !$isHtmlFormat ? ($answers['usages'] ?? []) : [];
      $examples = !$isHtmlFormat ? ($answers['examples'] ?? []) : [];
    @endphp

    @if($isHtmlFormat && isset($answers['sections']))
      @foreach($answers['sections'] as $secId => $sec)
        <!-- Section Card -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; border-left: 5px solid #3b82f6 !important;">
          <h3 class="fw-bold text-dark mb-4 border-bottom pb-2" style="font-size: 18px; text-align: left;">
            📚 Phần: {{ $sec['title'] }} <span class="badge bg-primary-subtle text-primary border px-2 py-1 rounded ms-2" style="font-size: 12px; background-color: #dbeafe !important; border-color: #bfdbfe !important; color: #1d4ed8 !important;">Điểm phần này: {{ $sec['score'] }}%</span>
          </h3>

          <!-- 1. Formula -->
          @if(!empty($sec['formulas']))
            <div class="mb-4">
              <h5 class="fw-bold text-primary mb-3 small d-flex align-items-center gap-2">
                <span>🔹 Công thức (Formula Recall)</span>
              </h5>
              <div class="d-flex flex-column gap-3.5">
                @foreach($sec['formulas'] as $key => $f)
                  <div class="p-3 rounded-3 border {{ $f['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}" 
                       style="background-color: {{ $f['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $f['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="fw-bold text-dark small">{{ $f['name'] }}</span>
                      <span class="badge {{ $f['is_correct'] ? 'bg-success text-white' : 'bg-danger text-white' }} small">
                        {{ $f['is_correct'] ? '✓ Đúng' : '✕ Sai' }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <span class="text-secondary small d-block mb-1">Câu trả lời của bạn:</span>
                      <code class="font-monospace fw-bold fs-6 d-block p-2 bg-white rounded border {{ $f['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                            style="border-color: {{ $f['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                        {{ $f['student'] ?: '(Để trống)' }}
                      </code>
                    </div>
                    @if(!$f['is_correct'])
                      <div>
                        <span class="text-secondary small d-block mb-1">Đáp án chính xác:</span>
                        <code class="font-monospace text-muted d-block p-2 bg-white rounded border border-secondary-subtle">
                          {{ $f['actual'] }}
                        </code>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- 2. Usages -->
          @if(!empty($sec['usages']))
            <div class="mb-4">
              <h5 class="fw-bold mb-3 small d-flex align-items-center gap-2" style="color: #7c3aed !important;">
                <span>📘 Cách dùng (Usage Recall)</span>
              </h5>
              <div class="d-flex flex-column gap-3.5">
                @foreach($sec['usages'] as $idx => $u)
                  <div class="p-3 rounded-3 border {{ $u['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}"
                       style="background-color: {{ $u['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $u['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="fw-bold text-dark small">{{ $u['name'] }}</span>
                      <span class="badge {{ $u['is_correct'] ? 'bg-success' : 'bg-danger' }} small">
                        {{ $u['is_correct'] ? '✓ Đúng (Đạt)' : '✕ Sai / Chưa đủ ý' }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <span class="text-secondary small d-block mb-1">Câu trả lời của bạn:</span>
                      <div class="p-2 bg-white rounded border {{ $u['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                           style="font-size: 13.5px; border-color: {{ $u['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                        {{ $u['student'] ?: '(Để trống)' }}
                      </div>
                    </div>
                    @if(!$u['is_correct'])
                      <div>
                        <span class="text-secondary small d-block mb-1">Gợi ý cách dùng chuẩn:</span>
                        <div class="p-2 bg-white rounded border border-secondary-subtle text-muted" style="font-size: 13.5px;">
                          {{ $u['actual'] }}
                        </div>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- 3. Examples -->
          @if(!empty($sec['examples']))
            <div>
              <h5 class="fw-bold text-success mb-3 small d-flex align-items-center gap-2">
                <span>✏️ Ví dụ (Example Translation)</span>
              </h5>
              <div class="d-flex flex-column gap-3.5">
                @foreach($sec['examples'] as $idx => $ex)
                  <div class="p-3 rounded-3 border {{ $ex['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}"
                       style="background-color: {{ $ex['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $ex['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                      <span class="fw-bold text-dark small">Câu số {{ $idx + 1 }} (Ngữ cảnh: {{ $ex['vietnamese'] }})</span>
                      <span class="badge {{ $ex['is_correct'] ? 'bg-success' : 'bg-danger' }} small">
                        {{ $ex['is_correct'] ? '✓ Đúng (Dịch chuẩn)' : '✕ Chưa chính xác' }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <span class="text-secondary small d-block mb-1">Bản dịch của bạn:</span>
                      <code class="font-monospace fw-bold fs-6 d-block p-2 bg-white rounded border {{ $ex['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                            style="border-color: {{ $ex['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                        {{ $ex['student'] ?: '(Để trống)' }}
                      </code>
                    </div>
                    @if(!$ex['is_correct'])
                      <div>
                        <span class="text-secondary small d-block mb-1">Bản dịch chính xác:</span>
                        <code class="font-monospace text-muted d-block p-2 bg-white rounded border border-secondary-subtle">
                          {{ $ex['actual'] }}
                        </code>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

        </div>
      @endforeach
    @else
      <!-- 1. Formula Section Results -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white;">
        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 17px;">
          <span class="text-primary">🔹</span> 1. So Sánh Phần Công Thức (Formula Evaluation)
        </h4>

        <div class="d-flex flex-column gap-3.5">
          @foreach($formulas as $key => $f)
            <div class="p-3 rounded-3 border {{ $f['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}" 
                 style="background-color: {{ $f['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $f['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
              
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark small">{{ $f['name'] }}</span>
                <span class="badge {{ $f['is_correct'] ? 'bg-success text-white' : 'bg-danger text-white' }} small">
                  {{ $f['is_correct'] ? '✓ Đúng (+10 đ)' : '✕ Sai (+0 đ)' }}
                </span>
              </div>

              <!-- Student Answer -->
              <div class="mb-2">
                <span class="text-secondary small d-block mb-1">Câu trả lời của bạn:</span>
                <code class="font-monospace fw-bold fs-6 d-block p-2 bg-white rounded border {{ $f['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                      style="border-color: {{ $f['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                  {{ $f['student'] ?: '(Để trống)' }}
                </code>
              </div>

              <!-- Correct Answer if Incorrect -->
              @if(!$f['is_correct'])
                <div>
                  <span class="text-secondary small d-block mb-1">Đáp án chính xác:</span>
                  <code class="font-monospace text-muted d-block p-2 bg-white rounded border border-secondary-subtle">
                    {{ $f['actual'] }}
                  </code>
                </div>
              @endif

            </div>
          @endforeach
        </div>
      </div>

      <!-- 2. Usage Section Results -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white;">
        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 17px;">
          <span style="color: #8b5cf6;">📘</span> 2. So Sánh Phần Cách Dùng (Usage Evaluation)
        </h4>

        <div class="d-flex flex-column gap-3.5">
          @forelse($usages as $idx => $u)
            <div class="p-3 rounded-3 border {{ $u['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}"
                 style="background-color: {{ $u['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $u['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
              
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark small">{{ $u['name'] }}</span>
                <span class="badge {{ $u['is_correct'] ? 'bg-success' : 'bg-danger' }} small">
                  {{ $u['is_correct'] ? '✓ Đúng (Đạt)' : '✕ Sai / Chưa đủ ý' }}
                </span>
              </div>

              <!-- Student Answer -->
              <div class="mb-2">
                <span class="text-secondary small d-block mb-1">Câu trả lời của bạn:</span>
                <div class="p-2 bg-white rounded border {{ $u['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                     style="font-size: 13.5px; border-color: {{ $u['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                  {{ $u['student'] ?: '(Để trống)' }}
                </div>
              </div>

              <!-- Correct Answer if Incorrect -->
              @if(!$u['is_correct'])
                <div>
                  <span class="text-secondary small d-block mb-1">Gợi ý cách dùng chuẩn từ bài học:</span>
                  <div class="p-2 bg-white rounded border border-secondary-subtle text-muted" style="font-size: 13.5px;">
                    {{ $u['actual'] }}
                  </div>
                </div>
              @endif

            </div>
          @empty
            <p class="text-muted small">Bài học này không thiết lập cấu hình kiểm tra cách dùng.</p>
          @endforelse
        </div>
      </div>

      <!-- 3. Example Section Results -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white;">
        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 17px;">
          <span class="text-success">✏️</span> 3. Kết Quả Dịch Thuật Câu Ví Dụ (Example Translation Evaluation)
        </h4>

        <div class="d-flex flex-column gap-3.5">
          @foreach($examples as $idx => $ex)
            <div class="p-3 rounded-3 border {{ $ex['is_correct'] ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}"
                 style="background-color: {{ $ex['is_correct'] ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $ex['is_correct'] ? '#bbf7d0' : '#fecaca' }} !important;">
              
              <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <span class="fw-bold text-dark small">Câu số {{ $idx + 1 }} (Ngữ cảnh: {{ $ex['vietnamese'] }})</span>
                <span class="badge {{ $ex['is_correct'] ? 'bg-success' : 'bg-danger' }} small">
                  {{ $ex['is_correct'] ? '✓ Đúng (Dịch chuẩn)' : '✕ Chưa chính xác' }}
                </span>
              </div>

              <!-- Student Answer -->
              <div class="mb-2">
                <span class="text-secondary small d-block mb-1">Bản dịch của bạn:</span>
                <code class="font-monospace fw-bold fs-6 d-block p-2 bg-white rounded border {{ $ex['is_correct'] ? 'text-success border-success' : 'text-danger border-danger' }}"
                      style="border-color: {{ $ex['is_correct'] ? '#86efac' : '#fca5a5' }} !important;">
                  {{ $ex['student'] ?: '(Để trống)' }}
                </code>
              </div>

              <!-- Correct Answer if Incorrect -->
              @if(!$ex['is_correct'])
                <div>
                  <span class="text-secondary small d-block mb-1">Bản dịch chính xác:</span>
                  <code class="font-monospace text-muted d-block p-2 bg-white rounded border border-secondary-subtle">
                    {{ $ex['actual'] }}
                  </code>
                </div>
              @endif

            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Final Navigation Actions -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 mb-5">
      <div>
        <a href="{{ route('grammar.admin.index') }}" class="btn btn-dark px-4 py-2" style="border-radius: 8px;">
          📊 Bảng Điều Khiển Admin
        </a>
      </div>

      <div class="d-flex gap-3">
        <a href="{{ route('grammar.lessons.review', $review->lesson_id) }}" class="btn btn-outline-primary px-4 py-2" style="border-radius: 8px;">
          🔄 Ôn Tập Lại Bài Này
        </a>
        <a href="{{ route('grammar.lesson.show', $review->lesson_id) }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
          📚 Tiếp Tục Học Lý Thuyết
        </a>
      </div>
    </div>

  </div>

@if(session()->has('newly_unlocked_achievements'))
    <!-- Modal Chúc Mừng Huy Hiệu Mới -->
    <div id="achievementModal" class="modal fade show" tabindex="-1" style="display: block; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 1060;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content border-0 shadow-lg position-relative overflow-hidden" style="border-radius: 24px; background: linear-gradient(145deg, #1e1b4b, #0f0a26); color: white;">
                
                <!-- Glowing background elements -->
                <div style="position: absolute; width: 150px; height: 150px; background: rgba(124, 58, 237, 0.4); filter: blur(60px); top: -20px; left: -20px; pointer-events: none;"></div>
                <div style="position: absolute; width: 150px; height: 150px; background: rgba(6, 182, 212, 0.3); filter: blur(60px); bottom: -20px; right: -20px; pointer-events: none;"></div>
                
                <div class="modal-body text-center p-5 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" style="font-size: 14px; filter: invert(1);" onclick="closeAchievementModal()"></button>
                    
                    <div class="mb-2 text-warning fw-bold text-uppercase tracking-wider" style="font-size: 11px; letter-spacing: 2px;">
                        🏆 Thành tựu mới đã mở khóa!
                    </div>
                    
                    <h3 class="fw-black mb-4" style="font-size: 22px; background: linear-gradient(to right, #fbbf24, #f59e0b, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        BẠN QUÁ XUẤT SẮC!
                    </h3>

                    <div class="d-flex flex-column align-items-center gap-4 py-2">
                        @foreach(session('newly_unlocked_achievements') as $ach)
                            <div class="achievement-card text-center w-100 p-4 rounded-3 border position-relative" 
                                 style="background: rgba(255, 255, 255, 0.05); border-color: rgba(251, 191, 36, 0.25); border-radius: 16px !important;">
                                
                                <!-- Animated rotating cup/emoji wrapper -->
                                <div class="achievement-icon-wrapper mb-3" style="animation: bounce 2s infinite ease-in-out;">
                                    <span style="font-size: 72px; line-height: 1; display: inline-block;">{{ $ach->icon }}</span>
                                </div>
                                
                                <h4 class="fw-bold mb-2 text-warning" style="font-size: 17px;">
                                    {{ $ach->title }}
                                </h4>
                                
                                <p class="text-light text-opacity-80 small mb-0 px-3" style="font-size: 12px; line-height: 1.5;">
                                    {{ $ach->description }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-2 d-flex flex-column gap-2">
                        <button class="btn btn-warning w-100 fw-bold shadow-sm py-2.5 d-flex align-items-center justify-content-center gap-2" 
                                style="border-radius: 12px; background: linear-gradient(135deg, #fbbf24, #d97706); border: none; color: #0f0a26; font-size: 13.5px;"
                                onclick="shareAchievement()">
                            🔗 Chia sẻ thành tựu này
                        </button>
                        <button class="btn btn-outline-white w-100 fw-bold py-2.5" 
                                style="border-radius: 12px; border: 1.5px solid rgba(255, 255, 255, 0.2); color: white; background: transparent; font-size: 13.5px;" 
                                onclick="closeAchievementModal()">
                            Tiếp tục học tập
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script Kích hoạt Pháo hoa Confetti và đóng Modal -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiệu ứng pháo hoa Confetti tung bay
            var duration = 3 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 4,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#a78bfa', '#34d399', '#fbbf24', '#38bdf8']
                });
                confetti({
                    particleCount: 4,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#a78bfa', '#34d399', '#fbbf24', '#38bdf8']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
            
            // Thêm pháo hoa bùng nổ chính giữa
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        });

        function closeAchievementModal() {
            var modal = document.getElementById('achievementModal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            }
        }

        function shareAchievement() {
            var shareText = "Kỳ tích! Tôi vừa mở khóa huy hiệu thành tựu học tập tiếng Anh xuất sắc: [{{ session('newly_unlocked_achievements')->first()->title }}]! Hãy cùng tôi chinh phục lộ trình học tiếng Anh đỉnh cao nhé! 🚀✨";
            navigator.clipboard.writeText(shareText).then(function() {
                alert("Đã sao chép thông điệp chia sẻ vào bộ nhớ tạm! Bạn có thể gửi ngay cho bạn bè hoặc đăng lên mạng xã hội.");
            });
        }
    </script>
    
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-8px) scale(1.05); }
        }
    </style>
@endif
@endsection
