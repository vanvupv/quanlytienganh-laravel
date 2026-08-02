@extends('vocabularies.vocabulary')

@section('title', 'Kết Quả Ôn Tập Từ Vựng')

@section('content_voca')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Overall Score Summary Header Card -->
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="badge bg-secondary mb-2" style="font-size: 11px; padding: 5px 10px;">Review Result Report</span>
                        <h2 class="fw-bold m-0 text-dark" style="font-size: 24px;">📈 Kết Quả Ôn Tập: <span class="font-monospace text-primary">{{ $review->word->word }}</span></h2>
                        <p class="small text-muted mt-1 mb-0">Thời gian nộp bài: {{ $review->created_at->format('H:i - d/m/Y') }}</p>
                    </div>
                    <div class="text-center p-3 rounded-4 {{ $review->passed ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25' }}" style="min-width: 160px;">
                        <div class="small fw-semibold text-uppercase text-secondary mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Tổng điểm đạt được</div>
                        <div class="fw-extrabold {{ $review->passed ? 'text-success' : 'text-danger' }} font-monospace" style="font-size: 38px; line-height: 1;">{{ $review->score }}<span style="font-size: 18px;">đ</span></div>
                        <span class="badge rounded-pill mt-2 px-2.5 py-1" style="font-size: 10px; background-color: {{ $review->passed ? '#15803d' : '#b91c1c' }}; color: white !important;">
                            {{ $review->passed ? '🟢 ĐẠT CHUẨN (>= 80)' : '🔴 CHƯA ĐẠT CHUẨN' }}
                        </span>
                    </div>
                </div>

                <hr class="my-3" style="border-color: #e2e8f0;">

                <div class="row g-2 text-center">
                    <div class="col-6 col-sm-3 p-2 rounded bg-light border border-white">
                        <div class="small text-muted" style="font-size: 10.5px;">Phút làm bài</div>
                        <div class="fw-bold text-dark font-monospace" style="font-size: 15px;">
                            {{ floor($review->duration_seconds / 60) }}p {{ $review->duration_seconds % 60 }}s
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 p-2 rounded bg-light border border-white">
                        <div class="small text-muted" style="font-size: 10.5px;">Spelling Recall</div>
                        <div class="fw-bold {{ ($review->answers['spelling']['is_correct'] ?? false) ? 'text-success' : 'text-danger' }}" style="font-size: 15px;">
                            {{ ($review->answers['spelling']['is_correct'] ?? false) ? '30 / 30 điểm' : '0 / 30 điểm' }}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 p-2 rounded bg-light border border-white">
                        <div class="small text-muted" style="font-size: 10.5px;">Meanings Recall</div>
                        @php
                            $meanings = $review->answers['meanings'] ?? [];
                            $correctMeanings = collect($meanings)->where('is_correct', true)->count();
                            $totalMeanings = count($meanings);
                        @endphp
                        <div class="fw-bold text-dark" style="font-size: 15px;">
                            {{ $correctMeanings }} / {{ $totalMeanings }} đúng
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 p-2 rounded bg-light border border-white">
                        <div class="small text-muted" style="font-size: 10.5px;">Examples Translate</div>
                        @php
                            $examples = $review->answers['examples'] ?? [];
                            $correctExamples = collect($examples)->where('is_correct', true)->count();
                            $totalExamples = count($examples);
                        @endphp
                        <div class="fw-bold text-dark" style="font-size: 15px;">
                            {{ $correctExamples }} / {{ $totalExamples }} đúng
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAILED COMPARATIVE VIEW -->

            <!-- 1. Spelling Recall Comparative -->
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center" style="font-size: 15px;">
                    <span>🎯 THỬ THÁCH 1: Viết Chính Tả (Spelling Recall)</span>
                    <span class="badge {{ ($review->answers['spelling']['is_correct'] ?? false) ? 'bg-success' : 'bg-danger' }}">
                        {{ ($review->answers['spelling']['is_correct'] ?? false) ? 'Đúng' : 'Sai' }}
                    </span>
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" style="font-size: 13.5px; border-color: #cbd5e1;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50%;">Câu Trả Lời Của Bạn</th>
                                <th style="width: 50%;">Đáp Án Đúng Chuẩn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="{{ ($review->answers['spelling']['is_correct'] ?? false) ? 'table-success text-success' : 'table-danger text-danger' }} fw-bold font-monospace" style="font-size: 16px;">
                                    {{ $review->answers['spelling']['student'] ?: '[Bỏ trống]' }}
                                    @if(!($review->answers['spelling']['is_correct'] ?? false))
                                        <span class="badge bg-danger ms-2">✘</span>
                                    @else
                                        <span class="badge bg-success ms-2">✔</span>
                                    @endif
                                </td>
                                <td class="table-light text-dark fw-bold font-monospace" style="font-size: 16px;">
                                    {{ $review->answers['spelling']['actual'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Meaning Layers Comparative -->
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2" style="font-size: 15px;">
                    ⚡ THỬ THÁCH 2: Ý Nghĩa Đa Tầng Theo Giai Đoạn
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" style="font-size: 13px; border-color: #cbd5e1;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%;">Từ Loại & Giai Đoạn</th>
                                <th style="width: 37.5%;">Ý Nghĩa Bạn Nhớ</th>
                                <th style="width: 37.5%;">Đáp Án Đúng Chuẩn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meanings as $mean)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-secondary">{{ $mean['pos'] }}</span> <br>
                                        <small class="text-muted">{{ $mean['stage'] }}</small>
                                    </td>
                                    <td class="{{ $mean['is_correct'] ? 'table-success text-success fw-bold' : 'table-danger text-danger' }}">
                                        {{ $mean['student'] ?: '[Bỏ trống]' }}
                                        @if(!$mean['is_correct'])
                                            <span class="badge bg-danger ms-1">✘</span>
                                        @else
                                            <span class="badge bg-success ms-1">✔</span>
                                        @endif
                                    </td>
                                    <td class="text-dark fw-semibold">
                                        {{ $mean['actual'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted italic text-center py-2">Không có dữ liệu câu trả lời meanings.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Contextual Translation Comparative -->
            @if(!empty($examples))
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2" style="font-size: 15px;">
                        🌍 THỬ THÁCH 3: Dịch Câu Ngữ Cảnh
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        @foreach($examples as $ex)
                            <div class="p-3 rounded-3 border {{ $ex['is_correct'] ? 'border-success bg-success bg-opacity-5' : 'border-danger bg-danger bg-opacity-5' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1.5">
                                    <strong class="text-dark" style="font-size: 13px;">Gợi ý: "{{ $ex['vietnamese'] }}"</strong>
                                    <span class="badge {{ $ex['is_correct'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $ex['is_correct'] ? 'Đúng (✔)' : 'Sai (✘)' }}
                                    </span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <div class="small text-muted font-sans fw-semibold">Dịch của học viên:</div>
                                        <div class="font-monospace fw-bold {{ $ex['is_correct'] ? 'text-success' : 'text-danger' }}" style="font-size: 13.5px; line-height: 1.3;">
                                            "{{ $ex['student'] ?: '[Bỏ trống]' }}"
                                        </div>
                                    </div>
                                    <div class="col-sm-6 border-start">
                                        <div class="small text-muted font-sans fw-semibold">Bản dịch đúng chuẩn:</div>
                                        <div class="font-monospace fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                                            "{{ $ex['actual'] }}"
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Navigation Controls -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('vocabulary.index', ['word_id' => $review->word_id]) }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px; font-weight: bold; background-color: #2a5298; border-color: #2a5298;">
                    🌳 Trở lại Sơ đồ Cây Từ Vựng
                </a>
                <a href="{{ route('vocabulary.review', $review->word_id) }}" class="btn btn-success px-4 py-2 text-white" style="border-radius: 10px; font-weight: bold; background-color: #198754; border-color: #198754;">
                    🔄 Làm lại bài ôn tập
                </a>
            </div>

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
