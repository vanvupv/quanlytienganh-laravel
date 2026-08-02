@extends('skills.listening.index')

@section('title', (isset($listening) ? 'Sửa' : 'Thêm') . ' File Nghe')

@section('content_listening')
<div class="form-page py-4 px-3">

  <nav class="breadcrumb-custom mb-3">
    <a href="{{ route('listening.index') }}">🎧 Listening</a>
    <span class="sep">›</span>
    <span>{{ isset($listening) ? 'Sửa File Nghe' : 'Thêm File Nghe Mới' }}</span>
  </nav>

  <div class="form-card">
    <div class="form-card__header">
      <h3>{{ isset($listening) ? '✏️ Sửa File Nghe' : '➕ Thêm File Nghe Mới' }}</h3>
    </div>

    <form action="{{ isset($listening) ? route('listening.update', $listening->id) : route('listening.store') }}"
          method="POST" class="form-body">
      @csrf
      @if(isset($listening)) @method('PUT') @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="form-section">
        <h5 class="form-section__title">📁 Thông tin cơ bản</h5>
        <div class="form-row-2">
          <div class="form-group">
            <label class="form-label" for="title">Tên File / Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" id="title" name="title" class="form-input"
                   value="{{ old('title', $listening->title ?? '') }}"
                   placeholder="VD: Headway 5e Unit 1.8 – Introduction" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="link">Đường dẫn File</label>
            <input type="text" id="link" name="link" class="form-input"
                   value="{{ old('link', $listening->link ?? '') }}"
                   placeholder="VD: /audio/headway_unit1_8.mp3">
          </div>
          <div class="form-group">
            <label class="form-label" for="type">Định dạng</label>
            <select id="type" name="type" class="form-input">
              @foreach(['mp3','wav','aac','ogg','m4a'] as $fmt)
                <option value="{{ $fmt }}" {{ old('type', $listening->type ?? 'mp3') === $fmt ? 'selected' : '' }}>
                  {{ strtoupper($fmt) }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="size">Dung lượng</label>
            <input type="text" id="size" name="size" class="form-input"
                   value="{{ old('size', $listening->size ?? '') }}"
                   placeholder="VD: 5.2 MB">
          </div>
          <div class="form-group">
            <label class="form-label" for="duration">Thời gian (giây)</label>
            <input type="number" id="duration" name="duration" class="form-input"
                   value="{{ old('duration', $listening->duration ?? '') }}"
                   placeholder="VD: 205 (= 3:25)" min="0">
          </div>
          <div class="form-group">
            <label class="form-label" for="level">Cấp độ</label>
            <select id="level" name="level" class="form-input">
              <option value="">— Chọn cấp độ —</option>
              @foreach(['A1','A2','B1','B2','C1','C2'] as $lv)
                <option value="{{ $lv }}" {{ old('level', $listening->level ?? '') === $lv ? 'selected' : '' }}>
                  {{ $lv }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="score_level">Mức điểm tối đa</label>
            <input type="number" id="score_level" name="score_level" class="form-input"
                   value="{{ old('score_level', $listening->score_level ?? '') }}"
                   min="0" max="100" step="0.5" placeholder="VD: 100">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h5 class="form-section__title">📎 Liên kết Tài liệu & Chương</h5>
        <div class="form-row-2">
          <div class="form-group">
            <label class="form-label" for="document_id">Tài liệu</label>
            <select id="document_id" name="document_id" class="form-input" id="docSelect">
              <option value="">— Chọn tài liệu —</option>
              @foreach($documents as $doc)
                <option value="{{ $doc->id }}"
                  {{ old('document_id', $listening->document_id ?? '') == $doc->id ? 'selected' : '' }}>
                  [{{ $doc->level }}] {{ $doc->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="chapter_id">Chương</label>
            <select id="chapter_id" name="chapter_id" class="form-input">
              <option value="">— Chọn chương —</option>
              @if(isset($listening) && $listening->document)
                @foreach($listening->document->parts as $part)
                  <option value="{{ $part->id }}"
                    {{ $listening->chapter_id == $part->id ? 'selected' : '' }}>
                    {{ $part->name }}
                  </option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="topic_id">Chủ đề</label>
            <select id="topic_id" name="topic_id" class="form-input">
              <option value="">— Chọn chủ đề —</option>
              @foreach($topics as $topic)
                <option value="{{ $topic->id }}"
                  {{ old('topic_id', $listening->topic_id ?? '') == $topic->id ? 'selected' : '' }}>
                  {{ $topic->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h5 class="form-section__title">📜 Transcript & Tách câu đồng bộ</h5>
        <div class="form-group mb-3">
          <label class="form-label" for="transcript">Bản gốc (Original)</label>
          <textarea id="transcript" name="transcript" class="form-input form-textarea" rows="8"
                    placeholder="Nhập transcript tiếng Anh gốc của file nghe...">{{ old('transcript', isset($listening) ? optional($listening->transcripts->where('type','original')->first())->transcript : '') }}</textarea>
          <div class="form-hint">💡 Transcript gốc sẽ được dùng để chấm điểm và phân tách câu.</div>
        </div>
        <div class="form-group mb-4">
          <label class="form-label" for="translation">Bản dịch (Vietnamese)</label>
          <textarea id="translation" name="translation" class="form-input form-textarea" rows="5"
                    placeholder="Nhập bản dịch tiếng Việt (không bắt buộc)...">{{ old('translation', isset($listening) ? optional($listening->transcripts->where('type','translation')->first())->transcript : '') }}</textarea>
        </div>

        {{-- Interactive Sentence Sync Block --}}
        <div class="sync-tool-card p-3 rounded bg-light border mb-3" style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius:12px;">
          <h6 class="fw-bold mb-2">⏱️ Công cụ đồng bộ âm thanh từng câu</h6>
          <p class="text-muted small">Nhập link audio ở trên, viết transcript, rồi nhấn nút "Tách câu & đồng bộ" dưới đây. Bạn có thể vừa nghe vừa dùng phím tắt F7/F8 để gán nhanh mốc thời gian.</p>
          
          <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
            <audio id="syncAudioPlayer" controls style="max-width:350px;">
              <source id="syncAudioSource" src="{{ isset($listening) && $listening->link ? asset($listening->link) : '' }}">
            </audio>
            <button type="button" class="btn-cancel" style="padding: 6px 16px; border-color:#6366f1; color:#6366f1; background:#fff; cursor:pointer;" onclick="generateSyncSentences()">
              🛠️ Tách câu & đồng bộ
            </button>
          </div>

          <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="data-table" style="width:100%;">
              <thead>
                <tr style="background:#f1f5f9;">
                  <th style="width: 50%; padding:8px;">Nội dung câu</th>
                  <th style="width: 15%; padding:8px; text-align:center;">Bắt đầu (s)</th>
                  <th style="width: 15%; padding:8px; text-align:center;">Kết thúc (s)</th>
                  <th style="width: 20%; padding:8px; text-align:center;">Công cụ gán</th>
                </tr>
              </thead>
              <tbody id="syncSentencesBody">
                @if(isset($listening) && $listening->sentences->count())
                  @foreach($listening->sentences as $idx => $sent)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                      <td style="padding:8px;">
                        <input type="hidden" name="sentences[{{ $idx }}][text]" value="{{ $sent->sentence_text }}">
                        <input type="hidden" name="sentences[{{ $idx }}][order]" value="{{ $sent->order }}">
                        <span class="sent-text-display" style="font-size:0.82rem; font-family: monospace;">{{ $sent->sentence_text }}</span>
                      </td>
                      <td style="padding:8px;">
                        <input type="number" step="0.1" name="sentences[{{ $idx }}][start_time]" id="start-{{ $idx }}" class="form-input" style="padding:4px; font-size:0.8rem; text-align:center; width:80px;" value="{{ $sent->start_time }}">
                      </td>
                      <td style="padding:8px;">
                        <input type="number" step="0.1" name="sentences[{{ $idx }}][end_time]" id="end-{{ $idx }}" class="form-input" style="padding:4px; font-size:0.8rem; text-align:center; width:80px;" value="{{ $sent->end_time }}">
                      </td>
                      <td style="padding:8px; text-align:center;">
                        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem;" onclick="setSyncTime({{ $idx }}, 'start')" title="Gán mốc bắt đầu (F7)">S (F7)</button>
                        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem; background:#fee2e2; color:#dc2626;" onclick="setSyncTime({{ $idx }}, 'end')" title="Gán mốc kết thúc (F8)">E (F8)</button>
                        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem; background:#e0f2fe; color:#0369a1;" onclick="testPlaySentence({{ $idx }})" title="Nghe thử đoạn này">▶</button>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr id="syncEmptyRow">
                    <td colspan="4" class="text-center text-muted py-3" style="text-align:center; padding:15px; color:#94a3b8;">Chưa có danh sách câu. Hãy nhấn "Tách câu & đồng bộ" phía trên.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <a href="{{ route('listening.index') }}" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-submit">
          {{ isset($listening) ? '💾 Lưu thay đổi' : '➕ Thêm File Nghe' }}
        </button>
      </div>
    </form>
  </div>

</div>
@endsection

@push('script')
<script>
// Khi chọn tài liệu, tự động load danh sách chương qua fetch
document.getElementById('document_id')?.addEventListener('change', async function() {
  const docId = this.value;
  const chapterSelect = document.getElementById('chapter_id');
  chapterSelect.innerHTML = '<option value="">— Chọn chương —</option>';
  if (!docId) return;

  try {
    let url = "{{ route('api.document.parts', ':id') }}";
    url = url.replace(':id', docId);
    const res = await fetch(url);
    const data = await res.json();
    data.forEach(part => {
      const opt = document.createElement('option');
      opt.value = part.id;
      opt.textContent = part.name;
      chapterSelect.appendChild(opt);
    });
  } catch(e) {
    console.log('Không load được chương, dùng thủ công.');
  }
});

// Link input "Đường dẫn File" changes to dynamically reload audio player source
document.getElementById('link')?.addEventListener('input', function() {
  const link = this.value;
  const audio = document.getElementById('syncAudioPlayer');
  const source = document.getElementById('syncAudioSource');
  if (audio && source && link) {
    source.src = link.startsWith('http') || link.startsWith('/') ? link : '/' + link;
    audio.load();
  }
});

// Auto-split transcript into sentences
function generateSyncSentences() {
  const text = document.getElementById('transcript').value.trim();
  if (!text) {
    alert('Vui lòng điền transcript gốc tiếng Anh trước!');
    return;
  }
  
  const sentences = text.split(/(?<=[.!?])\s+/).filter(s => s.trim().length > 0);
  const body = document.getElementById('syncSentencesBody');
  body.innerHTML = '';
  
  sentences.forEach((sentence, idx) => {
    const row = document.createElement('tr');
    row.style.borderBottom = '1px solid #f1f5f9';
    row.innerHTML = `
      <td style="padding:8px;">
        <input type="hidden" name="sentences[${idx}][text]" value="${sentence.replace(/"/g, '&quot;')}">
        <input type="hidden" name="sentences[${idx}][order]" value="${idx + 1}">
        <span class="sent-text-display" style="font-size:0.82rem; font-family: monospace;">${sentence}</span>
      </td>
      <td style="padding:8px;">
        <input type="number" step="0.1" name="sentences[${idx}][start_time]" id="start-${idx}" class="form-input" style="padding:4px; font-size:0.8rem; text-align:center; width:80px;" value="0">
      </td>
      <td style="padding:8px;">
        <input type="number" step="0.1" name="sentences[${idx}][end_time]" id="end-${idx}" class="form-input" style="padding:4px; font-size:0.8rem; text-align:center; width:80px;" value="0">
      </td>
      <td style="padding:8px; text-align:center;">
        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem;" onclick="setSyncTime(${idx}, 'start')" title="Gán mốc bắt đầu (F7)">S (F7)</button>
        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem; background:#fee2e2; color:#dc2626;" onclick="setSyncTime(${idx}, 'end')" title="Gán mốc kết thúc (F8)">E (F8)</button>
        <button type="button" class="btn-xs-action" style="padding:2px 6px; font-size:0.7rem; background:#e0f2fe; color:#0369a1;" onclick="testPlaySentence(${idx})" title="Nghe thử đoạn này">▶</button>
      </td>
    `;
    body.appendChild(row);
  });
}

function setSyncTime(idx, type) {
  const audio = document.getElementById('syncAudioPlayer');
  if (!audio) return;
  const time = parseFloat(audio.currentTime).toFixed(2);
  const input = document.getElementById(`${type}-${idx}`);
  if (input) {
    input.value = time;
  }
}

// Global hotkeys (F7 / F8) for easy timestamp mapping
let activeRowIndex = 0;
document.addEventListener('keydown', function(e) {
  const audio = document.getElementById('syncAudioPlayer');
  if (!audio) return;
  
  if (e.key === 'F7') {
    e.preventDefault();
    const activeEl = document.activeElement;
    if (activeEl && activeEl.id && activeEl.id.startsWith('start-')) {
      const idx = activeEl.id.split('-')[1];
      setSyncTime(idx, 'start');
    } else {
      setSyncTime(activeRowIndex, 'start');
      const startInp = document.getElementById(`start-${activeRowIndex}`);
      if (startInp) startInp.focus();
    }
  } else if (e.key === 'F8') {
    e.preventDefault();
    const activeEl = document.activeElement;
    if (activeEl && activeEl.id && activeEl.id.startsWith('end-')) {
      const idx = activeEl.id.split('-')[1];
      setSyncTime(idx, 'end');
    } else {
      setSyncTime(activeRowIndex, 'end');
      const endInp = document.getElementById(`end-${activeRowIndex}`);
      if (endInp) endInp.focus();
      
      // Advance to next row
      activeRowIndex++;
      const nextInput = document.getElementById(`start-${activeRowIndex}`);
      if (nextInput) nextInput.focus();
    }
  }
});

// Play only the sentence segment
let testPlayInterval = null;
function testPlaySentence(idx) {
  const audio = document.getElementById('syncAudioPlayer');
  if (!audio) return;
  
  const start = parseFloat(document.getElementById(`start-${idx}`).value || 0);
  const end = parseFloat(document.getElementById(`end-${idx}`).value || 0);
  
  if (end <= start) {
    alert('Thời gian kết thúc phải lớn hơn thời gian bắt đầu!');
    return;
  }
  
  clearInterval(testPlayInterval);
  audio.currentTime = start;
  audio.play();
  
  testPlayInterval = setInterval(() => {
    if (audio.currentTime >= end) {
      audio.pause();
      clearInterval(testPlayInterval);
    }
  }, 100);
}
</script>
@endpush

<style>
/* ====================================================
   FORM STYLES
   ==================================================== */
.form-page { max-width: 900px; }
.form-card {
  background: #fff; border-radius: 20px;
  box-shadow: 0 8px 32px rgba(0,0,0,.08); overflow: hidden;
}
.form-card__header {
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  padding: 24px 32px; color: #fff;
}
.form-card__header h3 { margin: 0; font-size: 1.25rem; font-weight: 700; }

.form-body { padding: 28px 32px; }

.form-section { margin-bottom: 28px; }
.form-section__title {
  font-size: .9rem; font-weight: 800; color: #6366f1;
  text-transform: uppercase; letter-spacing: .5px; margin-bottom: 16px;
  padding-bottom: 8px; border-bottom: 2px solid #e0e7ff;
}

.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-label { font-size: .82rem; font-weight: 700; color: #374151; }

.form-input {
  padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 10px;
  font-size: .9rem; color: #1e293b; background: #fff;
  transition: border-color .2s, box-shadow .2s; outline: none;
}
.form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

.form-textarea { resize: vertical; min-height: 120px; font-family: 'Courier New', monospace; }
.form-hint { font-size: .75rem; color: #94a3b8; margin-top: 4px; }

.form-actions {
  display: flex; justify-content: flex-end; gap: 12px;
  padding-top: 20px; border-top: 1px solid #f1f5f9; margin-top: 8px;
}
.btn-cancel {
  padding: 10px 24px; border-radius: 10px; border: 2px solid #e2e8f0;
  color: #64748b; font-weight: 600; text-decoration: none; display: inline-block;
  transition: all .2s;
}
.btn-cancel:hover { border-color: #94a3b8; color: #1e293b; }
.btn-submit {
  padding: 10px 28px; border-radius: 10px; border: none;
  background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
  font-weight: 700; font-size: .95rem; cursor: pointer;
  transition: all .25s; box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,.4); }

.breadcrumb-custom { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #64748b; }
.breadcrumb-custom a { color: #6366f1; text-decoration: none; font-weight: 600; }
.breadcrumb-custom .sep { color: #cbd5e1; }

@media (max-width: 600px) {
  .form-row-2 { grid-template-columns: 1fr; }
  .form-body { padding: 20px; }
}
</style>
