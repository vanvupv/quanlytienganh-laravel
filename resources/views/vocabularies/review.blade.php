@extends('vocabularies.vocabulary')

@section('title', 'Ôn Tập Từ Vựng: ' . $word->word)

@section('content_voca')
<div class="container-fluid py-4">
    <form action="{{ route('vocabulary.submit-review', $word->id) }}" method="POST" id="vocabQuizForm">
        @csrf
        <!-- Hidden input to track total time taken in seconds -->
        <input type="hidden" name="duration_seconds" id="duration_seconds" value="0">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Header Card with live stopwatch -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div style="text-align: left;">
                            <span class="badge bg-success mb-2" style="font-size: 11px; padding: 5px 10px;">Active Recall Quiz</span>
                            <h2 class="fw-bold m-0" style="font-size: 24px;">📝 Ôn Tập: <span class="font-monospace text-warning">{{ $word->word }}</span></h2>
                            <p class="small opacity-75 mt-1 mb-0">Hệ thống so khớp chuỗi mờ sẽ tự động chuẩn hóa đáp án của bạn.</p>
                        </div>
                        <div class="text-center bg-white bg-opacity-20 px-4 py-2 rounded-3 border border-white border-opacity-25">
                            <div class="small fw-semibold text-uppercase opacity-75" style="font-size: 10px; letter-spacing: 0.5px;">Thời gian làm bài</div>
                            <div class="fw-extrabold font-monospace text-warning" id="stopwatchDisplay" style="font-size: 26px; line-height: 1;">00:00</div>
                        </div>
                    </div>
                </div>

                <!-- CHALLENGE 1: Spelling Recall (30 Points) -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 16px;">
                        🎯 THỬ THÁCH 1: Tái Hiện Từ Vựng (Spelling Recall) <small class="text-muted">(30 điểm)</small>
                    </h5>
                    <p class="text-muted small">Hãy gõ lại từ Tiếng Anh chính xác dựa trên ý nghĩa cốt lõi và phiên âm chuẩn bên dưới:</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border: 1px solid #e2e8f0;">
                        <div>
                            <div class="small text-muted font-sans fw-semibold">Nghĩa Gốc Nền Tảng:</div>
                            <div class="fw-bold text-primary" style="font-size: 18px;">
                                {{ $word->properties->firstWhere('stage_id', 1)->meaning ?? 'Ý nghĩa nền tảng' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-secondary font-monospace px-2 py-1.5" style="font-size: 11px;">UK Phonetic: {{ $word->uk_phonetic }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label fw-bold text-secondary mb-1" style="font-size: 13px;">Đáp án Tiếng Anh của bạn:</label>
                        <input type="text" name="spelling" class="form-control form-control-lg text-primary font-monospace fw-bold" 
                               placeholder="Gõ từ Tiếng Anh chính xác tại đây..." required autocomplete="off" style="border-radius: 10px; border: 2px solid #cbd5e1;">
                    </div>
                </div>

                <!-- CHALLENGE 2: Parts of Speech & Meanings (40 Points) -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 16px;">
                        ⚡ THỬ THÁCH 2: Ý Nghĩa Đa Tầng Theo Giai Đoạn <small class="text-muted">(40 điểm)</small>
                    </h5>
                    <p class="text-muted small">Từ vựng phát triển thêm các từ loại (POS) và nét nghĩa mới qua từng giai đoạn. Điền nghĩa chính xác của từ loại được yêu cầu:</p>
                    
                    <div class="d-flex flex-column gap-3">
                        @foreach($word->properties as $prop)
                            <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary px-2.5 py-1" style="font-size: 10.5px; font-weight: bold;">
                                        {{ $prop->stage->name }} ({{ $prop->stage->description }})
                                    </span>
                                    <span class="badge bg-info text-dark font-monospace" style="font-size: 10.5px; font-weight: bold;">
                                        Từ Loại: {{ $prop->part_of_speech }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-secondary small mb-1">Nghĩa tương ứng trong giai đoạn này là gì?</label>
                                    <input type="text" name="meanings[{{ $prop->id }}]" class="form-control" 
                                           placeholder="Nhập ý nghĩa đã học..." required autocomplete="off" style="border-radius: 8px;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- CHALLENGE 3: Contextual Examples Translation (30 Points) -->
                @php
                    $propsWithExamples = $word->properties->filter(function($p) {
                        return !empty($p->example_sentence) && !empty($p->example_translation);
                    });
                @endphp
                @if($propsWithExamples->isNotEmpty())
                    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white; text-align: left;">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 16px;">
                            🌍 THỬ THÁCH 3: Dịch Câu Ví Dụ Ngữ Cảnh <small class="text-muted">(30 điểm)</small>
                        </h5>
                        <p class="text-muted small">Hãy dịch các câu ví dụ Tiếng Việt gợi ý dưới đây sang câu Tiếng Anh chuẩn ngữ pháp và từ vựng:</p>
                        
                        <div class="d-flex flex-column gap-3">
                            @foreach($propsWithExamples as $prop)
                                <div class="p-3 rounded-3" style="background-color: #fcf8f2; border: 1px solid #f5e6d3;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-warning text-dark font-monospace" style="font-size: 10.5px; font-weight: bold;">
                                            Contextual Translation
                                        </span>
                                        <span class="text-muted small font-sans">({{ $prop->part_of_speech }} - {{ $prop->stage->name }})</span>
                                    </div>
                                    <div class="mb-3">
                                        <div class="small text-muted fw-semibold">Câu gợi ý Tiếng Việt:</div>
                                        <div class="fw-bold text-dark fs-6">👉 "{{ $prop->example_translation }}"</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-secondary small mb-1">Dịch câu Tiếng Anh của bạn:</label>
                                        <input type="text" name="examples[{{ $prop->id }}]" class="form-control font-monospace" 
                                               placeholder="Viết câu dịch Tiếng Anh hoàn chỉnh..." required autocomplete="off" style="border-radius: 8px;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Submit Button and Action Controls -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('vocabulary.index', ['word_id' => $word->id]) }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px; font-weight: bold;">
                        ↩ Quay lại Cây Từ vựng
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2.5 shadow" style="border-radius: 10px; font-weight: bold; background-color: #2a5298; border-color: #2a5298;">
                        🚀 Nộp bài ôn tập & xem điểm
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Javascript live stopwatch logic -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let startTime = Date.now();
        let stopwatchInterval = setInterval(updateStopwatch, 1000);
        let durationInput = document.getElementById("duration_seconds");
        let stopwatchDisplay = document.getElementById("stopwatchDisplay");

        function updateStopwatch() {
            let elapsedMs = Date.now() - startTime;
            let elapsedSec = Math.floor(elapsedMs / 1000);
            durationInput.value = elapsedSec;

            let minutes = Math.floor(elapsedSec / 60);
            let seconds = elapsedSec % 60;
            
            let minStr = minutes.toString().padStart(2, '0');
            let secStr = seconds.toString().padStart(2, '0');
            stopwatchDisplay.textContent = minStr + ":" + secStr;
        }

        // Clean up interval on form submit
        document.getElementById("vocabQuizForm").addEventListener("submit", function() {
            clearInterval(stopwatchInterval);
        });
    });
</script>
@endsection
