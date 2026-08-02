@extends('skills.listening.index')

@section('title', 'Luyện nghe — ' . $listening->title)

@section('content_listening')
<div class="practice-page py-4 px-3">

  {{-- Breadcrumb --}}
  <nav class="breadcrumb-custom mb-3">
    <a href="{{ route('listening.index') }}">🎧 Listening</a>
    <span class="sep">›</span>
    @if($listening->document)
      <a href="{{ route('document.show', $listening->document->id) }}">{{ $listening->document->name }}</a>
      <span class="sep">›</span>
    @endif
    <span>{{ $listening->title }}</span>
  </nav>

  <div class="practice-layout">

    {{-- ==============================
         LEFT: Player + Info
         ============================== --}}
    <div class="practice-left">

      {{-- File Info Header --}}
      <div class="player-card">
        <div class="player-card__header">
          <div class="player-card__meta">
            @if($listening->level)
              <span class="level-badge level-{{ strtolower($listening->level) }}">{{ $listening->level }}</span>
            @endif
            @if($listening->topic)
              <span class="topic-badge">🏷️ {{ $listening->topic->name }}</span>
            @endif
            @if($listening->chapter)
              <span class="chapter-badge">📌 {{ $listening->chapter->name }}</span>
            @endif
          </div>
          <h3 class="player-card__title">{{ $listening->title }}</h3>
          @if($listening->document)
            <p class="player-card__doc text-muted small">
              <i class="mdi mdi-book-open-variant me-1"></i>{{ $listening->document->name }}
            </p>
          @endif
        </div>

        {{-- Stats Bar --}}
        <div class="stats-bar">
          <div class="stats-bar__item">
            <span class="stats-bar__val" id="listenCount">{{ $listening->listen_count }}</span>
            <span class="stats-bar__label">Lần nghe</span>
          </div>
          <div class="stats-bar__item">
            <span class="stats-bar__val text-success" id="efficiency">
              {{ $listening->efficiency() > 0 ? $listening->efficiency() . '%' : '—' }}
            </span>
            <span class="stats-bar__label">Hiệu suất</span>
          </div>
          <div class="stats-bar__item">
            <span class="stats-bar__val">{{ gmdate('i:s', $listening->duration ?? 0) }}</span>
            <span class="stats-bar__label">Thời lượng</span>
          </div>
          <div class="stats-bar__item">
            <span class="stats-bar__val">{{ $listening->type ? strtoupper($listening->type) : 'MP3' }}</span>
            <span class="stats-bar__label">Định dạng</span>
          </div>
        </div>

        {{-- Audio Player --}}
        <div class="audio-player-wrap">
          @if($listening->link)
            <audio id="audioPlayer" controls preload="metadata" class="w-100">
              <source src="{{ asset($listening->link) }}" type="audio/{{ $listening->type ?? 'mpeg' }}">
              Trình duyệt của bạn không hỗ trợ phát audio.
            </audio>
          @else
            <div class="no-audio-msg">
              <i class="mdi mdi-music-note-off" style="font-size:2rem"></i>
              <p>Chưa có file audio. Thêm đường dẫn file trong phần sửa.</p>
            </div>
          @endif
        </div>

        {{-- Controls --}}
        <div class="player-controls">
          <button class="ctrl-btn ctrl-btn--primary" id="btnStartSession">
            ▶ Bắt đầu nghe
          </button>
          <button class="ctrl-btn ctrl-btn--secondary" id="btnToggleTranscript">
            📜 Transcript gốc
          </button>
          @if($listening->link)
            <a href="{{ asset($listening->link) }}" download class="ctrl-btn ctrl-btn--ghost">
              ⬇ Tải về
            </a>
          @endif
        </div>

        {{-- Transcript (toggle) --}}
        <div class="transcript-box" id="transcriptBox" style="display:none">
          @if($original)
            <div class="transcript-header">
              <h5>📜 Original Transcript</h5>
              <div class="transcript-meta">
                <span>{{ $original->word_count }} từ</span>
                <span>{{ $original->sentence_count }} câu</span>
                <span>{{ strtoupper($original->language_code) }}</span>
              </div>
            </div>
            <div class="transcript-text" id="transcriptText">{{ $original->transcript }}</div>
          @else
            <p class="text-muted text-center py-3">Chưa có transcript gốc.</p>
          @endif

          @if($translation)
            <div class="transcript-divider">🌐 Bản dịch Tiếng Việt</div>
            <div class="transcript-text translation-text">{{ $translation->transcript }}</div>
          @endif
        </div>
      </div>{{-- /player-card --}}

      {{-- Weekly Timeline --}}
      @if($weeklyStats->count())
        <div class="timeline-card mt-3">
          <h5 class="timeline-card__title">📅 Timeline nghe theo tuần</h5>
          <div class="timeline-weeks">
            @foreach($weeklyStats as $ws)
              <div class="week-item {{ $ws->pass_count > 0 ? 'week-item--passed' : 'week-item--tried' }}">
                <div class="week-item__label">T{{ $ws->week_number }}</div>
                <div class="week-item__bar" style="height:{{ min(60, max(8, $ws->listen_count * 12)) }}px"></div>
                <div class="week-item__val">{{ $ws->listen_count }}x</div>
                @if($ws->pass_count)
                  <div class="week-item__pass">✓{{ $ws->pass_count }}</div>
                @endif
              </div>
            @endforeach
          </div>
          <div class="timeline-legend">
            <span class="legend-dot legend-dot--passed"></span> Tuần có đạt
            <span class="legend-dot legend-dot--tried ms-3"></span> Tuần đã nghe
          </div>
        </div>
      @endif

    </div>{{-- /practice-left --}}

    {{-- ==============================
         RIGHT: Assessment Panel
         ============================== --}}
    <div class="practice-right">
      <div class="assessment-card">
        <div class="assessment-card__header">
          <h4>✍️ Viết lại Transcript</h4>
          <p class="text-muted small mb-0">
            Nghe và viết lại toàn bộ nội dung bạn nghe được. Nghe 1 lần và viết đúng hết = 100% hiệu suất.
          </p>
        </div>

        {{-- Efficiency Formula --}}
        <div class="efficiency-info-box">
          <div class="efficiency-row">
            <span>🥇 Nghe 1 lần + hiểu hết =</span>
            <strong class="text-success">100%</strong>
          </div>
          <div class="efficiency-row">
            <span>🥈 Nghe 2 lần =</span>
            <strong style="color:#f59e0b">~50%</strong>
          </div>
          <div class="efficiency-row">
            <span>🥉 Nghe 3+ lần =</span>
            <strong style="color:#ef4444">≤33%</strong>
          </div>
        </div>

        {{-- Attempt counter --}}
        <div class="attempt-display mb-3">
          <span class="attempt-label">Lần nộp:</span>
          <span class="attempt-number" id="attemptNumber">
            {{ $listening->assessments()->count() + 1 }}
          </span>
        </div>

        {{-- Timer --}}
        <div class="timer-display" id="timerDisplay">00:00</div>

        {{-- Mode Switcher --}}
        <div class="mode-switcher" id="modeSwitcher">
          <button class="mode-btn active" id="btnModeFull" onclick="switchPracticeMode('full')">📝 Luyện cả bài</button>
          <button class="mode-btn" id="btnModeSent" onclick="switchPracticeMode('sentence')">✂️ Luyện từng câu</button>
          <button class="mode-btn" id="btnModeGap" onclick="switchPracticeMode('gap')">🕳️ Điền từ trống</button>
        </div>

        {{-- Mode 1: Full Dictation --}}
        <div id="fullPracticeContainer">
          <div class="assessment-body">
            <label class="form-label" for="submittedTranscript">
              Viết những gì bạn nghe được:
            </label>
            <textarea id="submittedTranscript" class="transcript-input" rows="10"
                      placeholder="Nhập transcript theo những gì bạn nghe được..."></textarea>
            <div class="d-flex justify-content-between text-muted mt-1" style="font-size:.75rem">
              <span id="wordCountDisplay">0 từ</span>
              @if($original)
                <span>Gốc: {{ $original->word_count }} từ</span>
              @endif
            </div>
          </div>

          {{-- Submit Button --}}
          <button class="btn-submit-full" id="btnSubmitAssessment" disabled>
            📤 Nộp & Chấm điểm
          </button>
        </div>

        {{-- Mode 2: Sentence-by-Sentence Dictation (Tách câu) --}}
        <div id="sentencePracticeContainer" class="sentence-practice-wrap" style="display:none">
          <p class="text-muted small mb-3">Hệ thống đã tự động tách file nghe thành từng câu. Bạn có thể nghe và nhập câu trả lời của từng câu phía dưới:</p>
          <div id="sentenceList"></div>
          
          <div class="p-3 bg-light rounded text-center mt-3">
            <p class="small text-muted mb-2">Sau khi luyện tập xong từng câu, bạn có thể nộp kết quả tổng quát:</p>
            <button class="btn-submit-full m-0" id="btnSubmitSentenceMode" onclick="submitSentenceModeResults()" disabled>
              📤 Nộp kết quả tổng quát
            </button>
        </div>

        {{-- Mode 3: Gap Fill Dictation (Điền từ còn thiếu) --}}
        <div id="gapPracticeContainer" class="sentence-practice-wrap" style="display:none">
          <p class="text-muted small mb-3">Hệ thống đã ẩn một số từ ngẫu nhiên trong tài liệu. Hãy nghe và điền các từ còn thiếu vào ô trống:</p>
          <div id="gapList"></div>
          
          <div class="p-3 bg-light rounded text-center mt-3">
            <p class="small text-muted mb-2">Sau khi điền xong tất cả các ô trống, bạn có thể nộp kết quả tổng quát:</p>
            <button class="btn-submit-full m-0" id="btnSubmitGapMode" onclick="submitGapModeResults()" disabled>
              📤 Nộp kết quả điền từ
            </button>
          </div>
        </div>

        {{-- Result Panel (hidden until submitted) --}}
        <div class="result-panel" id="resultPanel" style="display:none">
          <div class="result-score-circle">
            <svg viewBox="0 0 120 120" class="score-svg">
              <circle cx="60" cy="60" r="50" fill="none" stroke="#e2e8f0" stroke-width="10"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke-width="10" stroke-linecap="round"
                      id="scoreCircle" stroke-dasharray="314" stroke-dashoffset="314"
                      style="transition:stroke-dashoffset 1.5s ease"/>
            </svg>
            <div class="score-text">
              <span class="score-number" id="scoreNumber">0</span>
              <span class="score-pct">%</span>
            </div>
          </div>

          <div class="result-details">
            <div class="result-item">
              <span class="result-label">Từ đúng</span>
              <span class="result-val" id="correctWords">—</span>
            </div>
            <div class="result-item">
              <span class="result-label">Tổng từ</span>
              <span class="result-val" id="totalWords">—</span>
            </div>
            <div class="result-item">
              <span class="result-label">Hiệu suất</span>
              <span class="result-val fw-bold" id="efficiencyResult">—</span>
            </div>
            <div class="result-item">
              <span class="result-label">Kết quả</span>
              <span class="result-val" id="passedResult">—</span>
            </div>
          </div>

          <button class="btn-retry" id="btnRetry">🔄 Nghe lại & Nộp lần nữa</button>
          <a href="{{ route('listening.index') }}" class="btn-back-home">← Về danh sách</a>
        </div>

      </div>{{-- /assessment-card --}}
    </div>{{-- /practice-right --}}

  </div>{{-- /practice-layout --}}

</div>
@endsection

@push('script')
<script>
const LISTENING_ID = {{ $listening->id }};
const ORIGINAL_WORDS = {{ $original ? $original->word_count : 0 }};
const ORIGINAL_TRANSCRIPT_TEXT = `{!! isset($original) ? addslashes(preg_replace("/\r|\n/", " ", $original->transcript)) : '' !!}`;
const DB_SENTENCES = {!! json_encode($listening->sentences) !!};

// ─── State ───────────────────────────────────────────────
let sessionId   = null;
let timerInterval = null;
let elapsed     = 0;
let sessionStarted = false;
let splitSentences = [];
let gapFillData = [];

if (DB_SENTENCES && DB_SENTENCES.length > 0) {
  splitSentences = DB_SENTENCES;
} else if (ORIGINAL_TRANSCRIPT_TEXT) {
  splitSentences = ORIGINAL_TRANSCRIPT_TEXT.split(/(?<=[.!?])\s+/).filter(s => s.trim().length > 0).map((s, idx) => {
    return { sentence_text: s, start_time: 0.00, end_time: 0.00, order: idx + 1 };
  });
}

// ─── Elements ────────────────────────────────────────────
const btnStart      = document.getElementById('btnStartSession');
const btnToggle     = document.getElementById('btnToggleTranscript');
const btnSubmit     = document.getElementById('btnSubmitAssessment');
const btnRetry      = document.getElementById('btnRetry');
const transcriptBox = document.getElementById('transcriptBox');
const textarea      = document.getElementById('submittedTranscript');
const timerDisplay  = document.getElementById('timerDisplay');
const resultPanel   = document.getElementById('resultPanel');
const wordCountEl   = document.getElementById('wordCountDisplay');

// ─── Event Listeners ─────────────────────────────────────
btnStart.addEventListener('click', startPracticeSession);
btnToggle.addEventListener('click', toggleTranscriptBox);
btnRetry.addEventListener('click', resetAndRetryPractice);

if (textarea) {
  textarea.addEventListener('input', function() {
    const text = this.value.trim();
    const words = text.split(/\s+/).filter(Boolean).length;
    wordCountEl.textContent = `${words} từ`;
    
    if (sessionStarted && text.length > 0) {
      btnSubmit.disabled = false;
    } else {
      btnSubmit.disabled = true;
    }
  });
}

btnSubmit.addEventListener('click', submitFullDictationResults);

// ─── Session Management ──────────────────────────────────
async function startPracticeSession() {
  if (sessionStarted) return;
  
  btnStart.disabled = true;
  btnStart.textContent = '⏳ Khởi tạo...';
  
  try {
    const res = await fetch(`{{ route('listening.session.start', $listening->id) }}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    
    const data = await res.json();
    sessionId = data.session_id;
    
    document.getElementById('listenCount').textContent = data.attempt_number;
    document.getElementById('attemptNumber').textContent = data.attempt_number;
    
    startStopwatch();
    
    sessionStarted = true;
    btnStart.textContent = '🟢 Đang nghe...';
    btnStart.classList.remove('ctrl-btn--primary');
    btnStart.classList.add('ctrl-btn--ghost');
    
    if (textarea) textarea.disabled = false;
    document.querySelectorAll('.sentence-input-field').forEach(input => input.disabled = false);
    document.querySelectorAll('.gap-input-field').forEach(input => input.disabled = false);
    
    if (textarea && textarea.value.trim().length > 0) {
      btnSubmit.disabled = false;
    }
    
    const btnSubSent = document.getElementById('btnSubmitSentenceMode');
    if (btnSubSent) btnSubSent.disabled = false;
    
    const btnSubGap = document.getElementById('btnSubmitGapMode');
    if (btnSubGap) btnSubGap.disabled = false;
    
    const audio = document.getElementById('audioPlayer');
    if (audio) audio.play().catch(e => console.log('Auto-play blocked by browser, user needs to click play'));
    
  } catch(e) {
    console.error(e);
    btnStart.disabled = false;
    btnStart.textContent = '▶ Bắt đầu nghe';
    alert('Không thể kết nối máy chủ để bắt đầu phiên nghe.');
  }
}

function startStopwatch() {
  clearInterval(timerInterval);
  elapsed = 0;
  timerInterval = setInterval(() => {
    elapsed++;
    const min = String(Math.floor(elapsed / 60)).padStart(2, '0');
    const sec = String(elapsed % 60).padStart(2, '0');
    timerDisplay.textContent = `${min}:${sec}`;
  }, 1000);
}

function toggleTranscriptBox() {
  if (transcriptBox.style.display === 'none') {
    transcriptBox.style.display = 'block';
    btnToggle.innerHTML = '🙈 Ẩn transcript';
  } else {
    transcriptBox.style.display = 'none';
    btnToggle.innerHTML = '📜 Transcript gốc';
  }
}

// ─── Submit Full Dictation (Mode 1) ──────────────────────
async function submitFullDictationResults() {
  if (!sessionStarted || !sessionId) return;
  
  clearInterval(timerInterval);
  btnSubmit.disabled = true;
  btnSubmit.textContent = '⏳ Đang chấm...';
  
  const text = textarea.value;
  
  try {
    const res = await fetch(`{{ route('listening.assessment.submit', $listening->id) }}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        submitted_transcript: text || " ",
        session_id: sessionId,
        duration_spent: elapsed
      })
    });
    
    const data = await res.json();
    showResult(data);
  } catch(e) {
    console.error(e);
    btnSubmit.disabled = false;
    btnSubmit.textContent = '📤 Nộp & Chấm điểm';
    alert('Có lỗi xảy ra khi gửi bài chấm điểm.');
  }
}

// ─── Switch Dictation Mode ─────────────────────────────────
function switchPracticeMode(mode) {
  const btnFull = document.getElementById('btnModeFull');
  const btnSent = document.getElementById('btnModeSent');
  const btnGap  = document.getElementById('btnModeGap');
  const fullContainer = document.getElementById('fullPracticeContainer');
  const sentContainer = document.getElementById('sentencePracticeContainer');
  const gapContainer  = document.getElementById('gapPracticeContainer');
  
  btnFull.classList.remove('active');
  btnSent.classList.remove('active');
  btnGap.classList.remove('active');
  
  fullContainer.style.display = 'none';
  sentContainer.style.display = 'none';
  gapContainer.style.display  = 'none';
  
  if (mode === 'full') {
    btnFull.classList.add('active');
    fullContainer.style.display = 'block';
  } else if (mode === 'sentence') {
    btnSent.classList.add('active');
    sentContainer.style.display = 'block';
    renderSentencePractice();
  } else if (mode === 'gap') {
    btnGap.classList.add('active');
    gapContainer.style.display = 'block';
    renderGapFillPractice();
  }
}

// ─── Render Sentence Practice (Mode 2) ─────────────────────
function renderSentencePractice() {
  const container = document.getElementById('sentenceList');
  if (container.children.length > 0) return;
  
  let html = '';
  splitSentences.forEach((sent, index) => {
    const sentence = sent.sentence_text;
    const wordCount = sentence.trim().split(/\s+/).filter(Boolean).length;
    
    let playBtnHtml = '';
    if (parseFloat(sent.end_time) > parseFloat(sent.start_time)) {
      playBtnHtml = `<button class="btn-xs-action" style="padding:2px 8px; border:none; border-radius:4px; font-size:0.75rem; background:#6366f1; color:#fff; cursor:pointer;" onclick="playSentenceSegment(${sent.start_time}, ${sent.end_time})" title="Nghe đoạn âm thanh câu này">🔊 Nghe</button>`;
    }
    
    html += `
      <div class="sentence-item">
        <div class="sentence-item__header">
          <span class="sentence-item__title">Câu ${index + 1} (${wordCount} từ)</span>
          <div style="display:flex; gap:8px; align-items:center;">
            ${playBtnHtml}
            <button class="btn-reveal-sent" onclick="toggleSentenceHint(${index})">👁️ Xem gợi ý</button>
          </div>
        </div>
        <input type="text" class="sentence-input-field" id="sent-input-${index}" 
               placeholder="Nhập những gì bạn nghe được..." 
               oninput="onSentenceInput(${index})" ${sessionStarted ? '' : 'disabled'}>
        <div class="sentence-feedback">
          <span id="sent-pct-${index}" style="color: #94a3b8;">Khớp: 0%</span>
          <span id="sent-correct-words-${index}" style="color: #94a3b8;">Đúng: 0/${wordCount} từ</span>
        </div>
        <div class="sentence-hint-box" id="sent-hint-${index}" style="display:none;">
          ${sentence}
        </div>
      </div>
    `;
  });
  container.innerHTML = html;
}

// ─── Render Gap Fill Practice (Mode 3) ─────────────────────
function renderGapFillPractice() {
  const container = document.getElementById('gapList');
  if (container.children.length > 0) return;
  
  let html = '';
  gapFillData = [];
  
  splitSentences.forEach((sent, sentenceIndex) => {
    const sentence = sent.sentence_text;
    const words = sentence.split(/\s+/);
    
    // Tìm các từ có thể làm ô trống (độ dài thực tế >= 3 ký tự)
    const candidateIndices = [];
    words.forEach((word, idx) => {
      const clean = word.replace(/^[^\p{L}\p{N}]+/gu, "").replace(/[^\p{L}\p{N}]+$/gu, "");
      if (clean.length >= 3) {
        candidateIndices.push(idx);
      }
    });
    
    // Chọn các index để làm ô trống (khoảng 25% số từ thực, tối thiểu 1 từ nếu có từ hợp lệ)
    const gapIndices = new Set();
    if (candidateIndices.length > 0) {
      const numGaps = Math.max(1, Math.round(candidateIndices.length * 0.25));
      const step = candidateIndices.length / numGaps;
      for (let i = 0; i < numGaps; i++) {
        const candidateIdx = Math.floor(i * step + step / 2);
        if (candidateIdx < candidateIndices.length) {
          gapIndices.add(candidateIndices[candidateIdx]);
        }
      }
    }
    
    let gapWordsCount = 0;
    const processedWords = words.map((word, wordIndex) => {
      const cleanWord = word.replace(/^[^\p{L}\p{N}]+/gu, "").replace(/[^\p{L}\p{N}]+$/gu, "");
      const shouldBeGap = gapIndices.has(wordIndex);
      
      if (shouldBeGap) {
        gapWordsCount++;
        gapFillData.push({
          sentenceIndex: sentenceIndex,
          wordIndex: wordIndex,
          answer: cleanWord
        });
        
        // Tách và giữ lại dấu câu ở đầu và cuối của từ để in ra ngoài ô input
        const leadingMatch = word.match(/^[^\p{L}\p{N}]+/gu);
        const trailingMatch = word.match(/[^\p{L}\p{N}]+$/gu);
        const leadingPunct = leadingMatch ? leadingMatch[0] : "";
        const trailingPunct = trailingMatch ? trailingMatch[0] : "";
        
        return `${leadingPunct}<input type="text" class="gap-input-field font-monospace" 
                       id="gap-${sentenceIndex}-${wordIndex}" 
                       data-answer="${cleanWord}" 
                       placeholder="..." 
                       style="width: ${Math.max(55, cleanWord.length * 11 + 12)}px; display: inline-block; padding: 2px 6px; border: 1px dashed #6366f1; border-radius: 4px; font-size: .85rem; outline: none; margin: 0 3px; text-align: center; font-weight: bold;"
                       oninput="checkGapRealtime(${sentenceIndex}, ${wordIndex})"
                       ${sessionStarted ? '' : 'disabled'}>${trailingPunct}`;
      }
      return `<span>${word}</span>`;
    });
    
    let playBtnHtml = '';
    if (parseFloat(sent.end_time) > parseFloat(sent.start_time)) {
      playBtnHtml = `<button class="btn-xs-action" style="padding:2px 8px; border:none; border-radius:4px; font-size:0.75rem; background:#6366f1; color:#fff; cursor:pointer;" onclick="playSentenceSegment(${sent.start_time}, ${sent.end_time})" title="Nghe đoạn âm thanh câu này">🔊 Nghe</button>`;
    }
    
    html += `
      <div class="sentence-item" id="gap-item-${sentenceIndex}">
        <div class="sentence-item__header">
          <span class="sentence-item__title">Câu ${sentenceIndex + 1} (Điền ${gapWordsCount} từ)</span>
          <div>
            ${playBtnHtml}
          </div>
        </div>
        <div class="gap-text-paragraph" style="font-size: 0.95rem; color: #334155; line-height: 1.8; word-break: break-word; text-align: left;">
          ${processedWords.join(' ')}
        </div>
        <div class="gap-answer-correct mt-2 d-none" id="gap-answers-${sentenceIndex}" style="font-size: 0.8rem; color: #10b981; font-weight: bold; background: #f0fdf4; padding: 6px 12px; border-radius: 6px; text-align: left;">
          🔑 Đáp án đúng: <span class="answers-list-text"></span>
        </div>
      </div>
    `;
  });
  
  container.innerHTML = html;
}

// ─── Gap Input Realtime Check ─────────────────────────────
function checkGapRealtime(sentenceIndex, wordIndex) {
  const input = document.getElementById(`gap-${sentenceIndex}-${wordIndex}`);
  if (!input) return;
  
  const answer = input.getAttribute('data-answer');
  const typed = input.value.trim();
  
  if (typed === '') {
    input.style.borderColor = '#6366f1';
    input.style.backgroundColor = '#fff';
    input.style.color = '#000';
    return;
  }
  
  if (normalizeWord(typed) === normalizeWord(answer)) {
    input.style.borderColor = '#10b981';
    input.style.backgroundColor = '#ecfdf5';
    input.style.color = '#047857';
  } else {
    input.style.borderColor = '#f59e0b';
    input.style.backgroundColor = '#fffbeb';
    input.style.color = '#b45309';
  }
}

// ─── Play Sentence Segment ────────────────────────────────
let playSentenceInterval = null;
function playSentenceSegment(start, end) {
  const audio = document.getElementById('audioPlayer');
  if (!audio) return;
  
  clearInterval(playSentenceInterval);
  audio.currentTime = start;
  audio.play();
  
  playSentenceInterval = setInterval(() => {
    if (audio.currentTime >= end) {
      audio.pause();
      clearInterval(playSentenceInterval);
    }
  }, 50);
}

// ─── Sentence Input Realtime Check ────────────────────────
function normalizeWord(word) {
  return word.toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?"“”]/g, "").trim();
}

function onSentenceInput(index) {
  const typed = document.getElementById(`sent-input-${index}`).value.trim();
  const sent = splitSentences[index];
  const original = sent.sentence_text;
  const typedWords = typed.split(/\s+/).filter(Boolean);
  const origWords = original.split(/\s+/).filter(Boolean);
  
  let correctCount = 0;
  const origCounts = {};
  origWords.forEach(w => {
    const nw = normalizeWord(w);
    origCounts[nw] = (origCounts[nw] || 0) + 1;
  });
  
  typedWords.forEach(w => {
    const nw = normalizeWord(w);
    if (origCounts[nw] > 0) {
      correctCount++;
      origCounts[nw]--;
    }
  });
  
  const pct = origWords.length > 0 ? Math.round((correctCount / origWords.length) * 100) : 0;
  
  const pctEl = document.getElementById(`sent-pct-${index}`);
  const wordsEl = document.getElementById(`sent-correct-words-${index}`);
  
  pctEl.textContent = `Khớp: ${pct}%`;
  pctEl.style.color = pct >= 80 ? '#10b981' : (pct >= 50 ? '#f59e0b' : '#ef4444');
  wordsEl.textContent = `Đúng: ${correctCount}/${origWords.length} từ`;
}

function toggleSentenceHint(index) {
  const hint = document.getElementById(`sent-hint-${index}`);
  const btn = document.querySelectorAll('.btn-reveal-sent')[index];
  if (hint.style.display === 'none') {
    hint.style.display = 'block';
    btn.textContent = '🙈 Ẩn gợi ý';
  } else {
    hint.style.display = 'none';
    btn.textContent = '👁️ Xem gợi ý';
  }
}

// ─── Submit Sentence Mode (Mode 2) ─────────────────────────
async function submitSentenceModeResults() {
  if (!sessionStarted || !sessionId) return;

  const combinedInputs = [];
  for (let i = 0; i < splitSentences.length; i++) {
    const val = document.getElementById(`sent-input-${i}`) ? document.getElementById(`sent-input-${i}`).value.trim() : '';
    combinedInputs.push(val);
  }
  const combinedText = combinedInputs.filter(Boolean).join(" ");
  
  clearInterval(timerInterval);
  const btnSub = document.getElementById('btnSubmitSentenceMode');
  btnSub.disabled = true;
  btnSub.textContent = '⏳ Đang chấm...';
  
  try {
    const res = await fetch(`{{ route('listening.assessment.submit', $listening->id) }}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        submitted_transcript: combinedText || " ",
        session_id: sessionId,
        duration_spent: elapsed
      })
    });
    const data = await res.json();
    showResult(data);
  } catch(e) {
    console.error(e);
    btnSub.disabled = false;
    btnSub.textContent = '📤 Nộp kết quả tổng quát';
    alert('Lỗi khi chấm điểm. Vui lòng thử lại.');
  }
}

// ─── Submit Gap Fill Mode (Mode 3) ──────────────────────────
async function submitGapModeResults() {
  if (!sessionStarted || !sessionId) return;
  
  clearInterval(timerInterval);
  const btnSub = document.getElementById('btnSubmitGapMode');
  btnSub.disabled = true;
  btnSub.textContent = '⏳ Đang chấm...';
  
  const combinedSentences = [];
  
  splitSentences.forEach((sent, sentenceIndex) => {
    const words = sent.sentence_text.split(/\s+/);
    const reconstructedWords = words.map((word, wordIndex) => {
      const input = document.getElementById(`gap-${sentenceIndex}-${wordIndex}`);
      if (input) {
        return input.value.trim() || "_";
      }
      return word;
    });
    combinedSentences.push(reconstructedWords.join(" "));
  });
  
  const reconstructedTranscript = combinedSentences.join(" ");
  
  try {
    const res = await fetch(`{{ route('listening.assessment.submit', $listening->id) }}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        submitted_transcript: reconstructedTranscript || " ",
        session_id: sessionId,
        duration_spent: elapsed
      })
    });
    
    const data = await res.json();
    showResult(data);
    
    // Hiển thị đáp án đúng
    splitSentences.forEach((sent, sentenceIndex) => {
      const answersDiv = document.getElementById(`gap-answers-${sentenceIndex}`);
      if (answersDiv) {
        const answersListText = answersDiv.querySelector('.answers-list-text');
        
        const sentenceAnswers = [];
        const words = sent.sentence_text.split(/\s+/);
        words.forEach((word, wordIndex) => {
          const input = document.getElementById(`gap-${sentenceIndex}-${wordIndex}`);
          if (input) {
            const clean = input.getAttribute('data-answer');
            const userVal = input.value.trim();
            const isCorrect = normalizeWord(userVal) === normalizeWord(clean);
            
            input.disabled = true;
            if (isCorrect) {
              input.style.borderColor = '#10b981';
              input.style.backgroundColor = '#dcfce7';
              input.style.color = '#15803d';
            } else {
              input.style.borderColor = '#ef4444';
              input.style.backgroundColor = '#fee2e2';
              input.style.color = '#b91c1c';
            }
            
            sentenceAnswers.push(`<span style="text-decoration: ${isCorrect ? 'none' : 'line-through'}; color: ${isCorrect ? '#15803d' : '#b91c1c'}">${clean}</span>`);
          }
        });
        
        answersListText.innerHTML = sentenceAnswers.join(', ');
        answersDiv.classList.remove('d-none');
      }
    });
    
  } catch(e) {
    console.error(e);
    btnSub.disabled = false;
    btnSub.textContent = '📤 Nộp kết quả điền từ';
    alert('Lỗi khi nộp kết quả điền từ.');
  }
}

// ─── Show Results ─────────────────────────────────────────
function showResult(data) {
  document.getElementById('fullPracticeContainer').style.display = 'none';
  document.getElementById('sentencePracticeContainer').style.display = 'none';
  document.getElementById('gapPracticeContainer').style.display = 'none';
  document.getElementById('modeSwitcher').style.display = 'none';
  
  resultPanel.style.display = 'block';
  
  document.getElementById('scoreNumber').textContent = Math.round(data.score_percent);
  document.getElementById('correctWords').textContent = data.correct_words;
  document.getElementById('totalWords').textContent = data.total_words;
  document.getElementById('efficiencyResult').textContent = data.efficiency + '%';
  
  const passedResult = document.getElementById('passedResult');
  if (data.is_passed) {
    passedResult.innerHTML = '<span class="badge bg-success px-3 py-1.5 rounded-pill text-white fw-bold">✔️ Đạt</span>';
  } else {
    passedResult.innerHTML = '<span class="badge bg-danger px-3 py-1.5 rounded-pill text-white fw-bold">❌ Chưa Đạt</span>';
  }
  
  const efficiencyVal = document.getElementById('efficiency');
  if (efficiencyVal) {
    efficiencyVal.textContent = data.efficiency + '%';
  }
  
  const circle = document.getElementById('scoreCircle');
  if (circle) {
    const score = Math.min(100, Math.max(0, data.score_percent));
    const offset = 314 - (314 * score / 100);
    setTimeout(() => {
      circle.style.strokeDashoffset = offset;
      if (score >= 80) {
        circle.style.stroke = '#10b981';
      } else if (score >= 50) {
        circle.style.stroke = '#f59e0b';
      } else {
        circle.style.stroke = '#ef4444';
      }
    }, 100);
  }
}

// ─── Reset and Retry (Nghe lại) ───────────────────────────
function resetAndRetryPractice() {
  sessionId = null;
  sessionStarted = false;
  elapsed = 0;
  clearInterval(timerInterval);
  
  timerDisplay.textContent = '00:00';
  if (textarea) {
    textarea.value = '';
    textarea.disabled = true;
    wordCountEl.textContent = '0 từ';
  }
  
  btnStart.disabled = false;
  btnStart.textContent = '▶ Bắt đầu nghe';
  btnStart.classList.add('ctrl-btn--primary');
  btnStart.classList.remove('ctrl-btn--ghost');
  
  btnSubmit.disabled = true;
  btnSubmit.textContent = '📤 Nộp & Chấm điểm';
  
  const btnSubSent = document.getElementById('btnSubmitSentenceMode');
  if (btnSubSent) {
    btnSubSent.disabled = true;
    btnSubSent.textContent = '📤 Nộp kết quả tổng quát';
  }
  
  const btnSubGap = document.getElementById('btnSubmitGapMode');
  if (btnSubGap) {
    btnSubGap.disabled = true;
    btnSubGap.textContent = '📤 Nộp kết quả điền từ';
  }
  
  document.getElementById('sentenceList').innerHTML = '';
  document.getElementById('gapList').innerHTML = '';
  
  resultPanel.style.display = 'none';
  document.getElementById('modeSwitcher').style.display = 'flex';
  
  const circle = document.getElementById('scoreCircle');
  if (circle) {
    circle.style.strokeDashoffset = 314;
    circle.style.stroke = '#6366f1';
  }
  
  switchPracticeMode('full');
}
</script>
@endpush

<style>
/* ====================================================
   PRACTICE PAGE STYLES
   ==================================================== */
.practice-page { max-width: 1400px; }
/* Mode Switcher */
.mode-switcher {
  display: flex; gap: 4px; padding: 4px; background: #f1f5f9; border-radius: 12px; margin: 10px 20px;
}
.mode-btn {
  flex: 1; padding: 8px 12px; border: none; background: none; font-size: .8rem; font-weight: 700;
  color: #64748b; border-radius: 8px; cursor: pointer; transition: all .2s;
}
.mode-btn.active {
  background: #fff; color: #6366f1; box-shadow: 0 4px 10px rgba(0,0,0,.05);
}

/* Sentence Practice */
.sentence-practice-wrap {
  padding: 0 20px 20px; max-height: 500px; overflow-y: auto;
}
.sentence-item {
  background: #f8fafc; border-radius: 12px; padding: 14px; border: 1px solid #e2e8f0; margin-bottom: 12px;
  transition: all .2s;
}
.sentence-item:hover { border-color: #cbd5e1; }
.sentence-item__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.sentence-item__title { font-size: .78rem; font-weight: 800; color: #475569; }
.btn-reveal-sent {
  background: none; border: none; font-size: .75rem; color: #6366f1; font-weight: 700; cursor: pointer;
}
.btn-reveal-sent:hover { text-decoration: underline; }
.sentence-input-field {
  width: 100%; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 8px;
  font-size: .85rem; outline: none; transition: border-color .2s; font-family: monospace;
}
.sentence-input-field:focus { border-color: #6366f1; }
.sentence-feedback { display: flex; justify-content: space-between; font-size: .75rem; font-weight: 700; margin-top: 6px; }
.sentence-hint-box {
  margin-top: 8px; padding: 8px 12px; background: #ecfdf5; border-left: 3px solid #10b981;
  border-radius: 4px; font-family: monospace; font-size: .8rem; color: #065f46;
}

/* Gap Fill Practice Styles */
.gap-input-field {
  transition: border-color 0.2s, background-color 0.2s, color 0.2s;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
}
.gap-input-field:focus {
  border-color: #6366f1 !important;
  border-style: solid !important;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
}
.gap-text-paragraph span {
  display: inline-block;
  margin-right: 2px;
}
.gap-answer-correct {
  animation: gapFadeIn 0.3s ease-out;
}
@keyframes gapFadeIn {
  from { opacity: 0; transform: translateY(2px); }
  to { opacity: 1; transform: translateY(0); }
}

.practice-layout { display: grid; grid-template-columns: 1fr 420px; gap: 24px; align-items: start; }

/* ── Player Card ── */
.player-card {
  background: #fff; border-radius: 20px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.08); border: 1px solid #f1f5f9;
}
.player-card__header { padding: 20px 24px 0; }
.player-card__meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.level-badge {
  font-size: .72rem; font-weight: 800; padding: 3px 12px; border-radius: 10px; color: #fff;
}
.level-a1{background:#10b981} .level-a2{background:#06b6d4}
.level-b1{background:#3b82f6} .level-b2{background:#8b5cf6}
.level-c1{background:#f59e0b} .level-c2{background:#ef4444}
.topic-badge { background: #fef3c7; color: #d97706; font-size:.72rem; font-weight:700; padding:3px 12px; border-radius:10px; }
.chapter-badge { background: #e0f2fe; color: #0369a1; font-size:.72rem; font-weight:700; padding:3px 12px; border-radius:10px; }

.player-card__title { font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 4px 0; }
.player-card__doc { margin-bottom: 0; }

/* Stats Bar */
.stats-bar {
  display: flex; gap: 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;
  margin: 16px 0 0;
}
.stats-bar__item {
  flex: 1; padding: 12px 8px; text-align: center;
  border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 2px;
}
.stats-bar__item:last-child { border-right: none; }
.stats-bar__val { font-size: 1.1rem; font-weight: 800; color: #1e293b; }
.stats-bar__label { font-size: .7rem; color: #94a3b8; font-weight: 600; }

/* Audio Player */
.audio-player-wrap { padding: 20px 24px 10px; }
.audio-player-wrap audio { width: 100%; border-radius: 10px; }
.no-audio-msg { text-align: center; color: #94a3b8; padding: 30px; }

/* Controls */
.player-controls { display: flex; gap: 10px; padding: 0 24px 16px; flex-wrap: wrap; }
.ctrl-btn {
  padding: 9px 18px; border-radius: 10px; border: none; cursor: pointer;
  font-weight: 700; font-size: .85rem; transition: all .2s; text-decoration: none; display: inline-block;
}
.ctrl-btn--primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
.ctrl-btn--primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(99,102,241,.35); }
.ctrl-btn--secondary { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
.ctrl-btn--secondary:hover { background: #e0f2fe; }
.ctrl-btn--ghost { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
.ctrl-btn--ghost:hover { color: #1e293b; }
.ctrl-btn:disabled { opacity: .5; cursor: not-allowed; }

/* Transcript Box */
.transcript-box {
  margin: 0 24px 20px; padding: 16px; background: #f8fafc;
  border-radius: 12px; border: 1px solid #e2e8f0;
}
.transcript-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.transcript-header h5 { font-size: .9rem; font-weight: 700; color: #1e293b; margin: 0; }
.transcript-meta { display: flex; gap: 12px; font-size: .72rem; color: #94a3b8; font-weight: 600; }
.transcript-text { font-family: 'Courier New', monospace; font-size: .85rem; line-height: 1.7; color: #374151; white-space: pre-wrap; }
.translation-text { color: #6366f1; margin-top: 12px; }
.transcript-divider { font-size: .78rem; font-weight: 700; color: #10b981; margin: 12px 0 8px; text-align: center; }

/* Timeline Card */
.timeline-card {
  background: #fff; border-radius: 16px; padding: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,.06); border: 1px solid #f1f5f9;
}
.timeline-card__title { font-size: .95rem; font-weight: 700; color: #1e293b; margin-bottom: 16px; }
.timeline-weeks { display: flex; gap: 8px; align-items: flex-end; overflow-x: auto; padding-bottom: 8px; }
.week-item { display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 36px; }
.week-item__label { font-size: .65rem; color: #94a3b8; font-weight: 700; }
.week-item__bar { width: 28px; border-radius: 4px 4px 0 0; transition: height .3s; min-height: 8px; }
.week-item--passed .week-item__bar { background: linear-gradient(180deg, #10b981, #06b6d4); }
.week-item--tried  .week-item__bar { background: linear-gradient(180deg, #f59e0b, #fb923c); }
.week-item__val { font-size: .65rem; font-weight: 700; color: #475569; }
.week-item__pass { font-size: .6rem; color: #10b981; font-weight: 700; }
.timeline-legend { font-size: .72rem; color: #94a3b8; margin-top: 8px; display: flex; align-items: center; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.legend-dot--passed { background: #10b981; }
.legend-dot--tried  { background: #f59e0b; }

/* ── Assessment Card ── */
.assessment-card {
  background: #fff; border-radius: 20px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.08); border: 1px solid #f1f5f9;
  position: sticky; top: 20px;
}
.assessment-card__header {
  background: linear-gradient(135deg, #1e293b, #334155);
  padding: 20px 24px; color: #fff;
}
.assessment-card__header h4 { margin: 0 0 6px; font-size: 1.1rem; font-weight: 800; }

/* Efficiency Info Box */
.efficiency-info-box {
  margin: 16px 20px; background: #f8fafc; border-radius: 12px;
  padding: 12px 16px; border: 1px solid #e2e8f0;
}
.efficiency-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: .8rem; color: #475569; padding: 3px 0;
}

/* Attempt Display */
.attempt-display { padding: 0 20px; display: flex; align-items: center; gap: 10px; }
.attempt-label { font-size: .78rem; font-weight: 700; color: #94a3b8; }
.attempt-number { font-size: 1.5rem; font-weight: 900; color: #6366f1; }

/* Timer */
.timer-display {
  text-align: center; font-size: 2.2rem; font-weight: 900;
  font-family: 'Courier New', monospace; color: #1e293b;
  letter-spacing: 3px; margin: 8px 0;
}

/* Assessment Body */
.assessment-body { padding: 8px 20px 12px; }
.form-label { font-size: .8rem; font-weight: 700; color: #374151; display: block; margin-bottom: 6px; }
.transcript-input {
  width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px;
  font-size: .88rem; color: #1e293b; resize: vertical; outline: none;
  font-family: 'Courier New', monospace; line-height: 1.6;
  transition: border-color .2s, box-shadow .2s;
}
.transcript-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

/* Submit Full Width */
.btn-submit-full {
  display: block; width: calc(100% - 40px); margin: 0 20px 20px;
  padding: 12px; border: none; border-radius: 12px; cursor: pointer;
  background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
  font-size: 1rem; font-weight: 800; transition: all .25s;
  box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.btn-submit-full:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,.4); }
.btn-submit-full:disabled { opacity: .5; cursor: not-allowed; }

/* Result Panel */
.result-panel { padding: 20px; border-top: 2px solid #f1f5f9; }

.result-score-circle {
  width: 140px; height: 140px; position: relative; margin: 0 auto 20px;
}
.score-svg { transform: rotate(-90deg); }
.score-text {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
  text-align: center; display: flex; align-items: baseline; gap: 2px;
}
.score-number { font-size: 2.2rem; font-weight: 900; color: #1e293b; }
.score-pct { font-size: 1rem; color: #94a3b8; font-weight: 700; }

.result-details { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.result-item {
  background: #f8fafc; border-radius: 10px; padding: 10px 14px;
  display: flex; flex-direction: column; gap: 3px;
}
.result-label { font-size: .7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
.result-val { font-size: 1rem; font-weight: 700; color: #1e293b; }

.btn-retry {
  display: block; width: 100%; padding: 10px; border: 2px solid #6366f1;
  border-radius: 10px; background: #fff; color: #6366f1; font-weight: 700;
  cursor: pointer; margin-bottom: 8px; transition: all .2s; font-size: .9rem;
}
.btn-retry:hover { background: #eef2ff; }
.btn-back-home {
  display: block; text-align: center; color: #94a3b8; font-size: .82rem;
  text-decoration: none; padding: 6px;
}
.btn-back-home:hover { color: #475569; }

/* Breadcrumb */
.breadcrumb-custom { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #64748b; }
.breadcrumb-custom a { color: #6366f1; text-decoration: none; font-weight: 600; }
.breadcrumb-custom a:hover { text-decoration: underline; }
.breadcrumb-custom .sep { color: #cbd5e1; }

@media (max-width: 960px) {
  .practice-layout { grid-template-columns: 1fr; }
  .assessment-card { position: static; }
}
</style>
