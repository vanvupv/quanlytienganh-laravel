@extends('skills.listening.index')

@section('title', '🧩 Ghép File Nghe — Audio Merger')

@section('content_listening')
<div class="listening-merger py-4 px-3">
  
  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h2 text-white font-weight-bold mb-1">🧩 Công Cụ Ghép File Nghe (Audio Merger)</h1>
      <p class="text-muted mb-0">Ghép nhiều tệp tin âm thanh cục bộ hoặc tệp tin trên hệ thống thành một bài luyện nghe duy nhất.</p>
    </div>
    <a href="{{ route('listening.index') }}" class="btn btn-outline-light d-flex align-items-center gap-2">
      <span>⬅️ Quay lại</span>
    </a>
  </div>

  <div class="row">
    {{-- Cột trái: Quản lý danh sách Track & Ghép file --}}
    <div class="col-lg-7 mb-4">
      <div class="card bg-dark text-white border-secondary mb-4 shadow-lg">
        <div class="card-header border-secondary bg-black d-flex justify-content-between align-items-center py-3">
          <h5 class="mb-0 text-cyan font-weight-bold d-flex align-items-center gap-2">
            🎵 Danh Sách Các Track Nhạc
          </h5>
          <span class="badge badge-info" id="track-count">0 tracks</span>
        </div>
        <div class="card-body">
          
          {{-- Drag and Drop Upload --}}
          <div id="drop-zone" class="border border-secondary rounded p-4 text-center mb-4 cursor-pointer hover-bg-dark transition-all d-flex flex-column align-items-center justify-content-center" style="border-style: dashed !important; background-color: rgba(255,255,255,0.02);">
            <div class="display-4 text-muted mb-2">📁</div>
            <h6 class="font-weight-bold">Kéo thả file âm thanh vào đây</h6>
            <p class="text-muted small mb-3">Hỗ trợ định dạng .mp3, .wav, .m4a</p>
            <button type="button" class="btn btn-sm btn-outline-info" onclick="document.getElementById('file-input').click()">
              Chọn từ máy tính
            </button>
            <input type="file" id="file-input" class="d-none" multiple accept="audio/*">
          </div>

          {{-- Select from System Button --}}
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">Hoặc sử dụng tài nguyên hệ thống:</span>
            <button type="button" class="btn btn-sm btn-info d-flex align-items-center gap-1" data-toggle="modal" data-target="#systemAudiosModal">
              🔍 Chọn từ hệ thống
            </button>
          </div>

          {{-- Empty state --}}
          <div id="empty-tracks-state" class="text-center py-5 text-muted">
            <p class="mb-0">Chưa có track nào được chọn. Hãy kéo thả file hoặc chọn từ hệ thống.</p>
          </div>

          {{-- Sortable Track List --}}
          <div id="track-list" class="list-group mb-4">
            {{-- Được render động qua JS --}}
          </div>

          {{-- Settings --}}
          <div class="row bg-black p-3 rounded mb-4 border border-secondary">
            <div class="col-md-6 mb-3 mb-md-0">
              <label class="font-weight-bold mb-1 d-block">⏳ Khoảng lặng giữa các track:</label>
              <select class="form-control bg-dark text-white border-secondary" id="silence-gap">
                <option value="0">Không có khoảng lặng</option>
                <option value="1">1 giây</option>
                <option value="2" selected>2 giây</option>
                <option value="3">3 giây</option>
                <option value="5">5 giây</option>
                <option value="10">10 giây</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="font-weight-bold mb-1 d-block">💾 Định dạng đầu ra (Output Format):</label>
              <select class="form-control bg-dark text-white border-secondary" id="output-format">
                <option value="mp3" selected>MP3 (Dung lượng nhẹ, nén tốt)</option>
                <option value="wav">WAV (Chất lượng gốc, không nén)</option>
              </select>
            </div>
          </div>

          {{-- Merge Actions --}}
          <div class="d-flex gap-3">
            <button type="button" class="btn btn-primary btn-block py-2 font-weight-bold d-flex align-items-center justify-content-center gap-2" id="btn-merge-audio">
              ⚡ Bắt đầu ghép file
            </button>
          </div>

          {{-- Preview Player Container --}}
          <div id="preview-container" class="mt-4 p-3 bg-black rounded border border-info" style="display: none;">
            <h6 class="text-info font-weight-bold mb-3 d-flex align-items-center gap-2">
              🔊 File Sau Khi Ghép (Xem trước)
            </h6>
            <audio id="preview-player" controls class="w-100 mb-3"></audio>
            <div class="d-flex justify-content-between">
              <span class="text-muted small d-flex align-items-center gap-1">
                ⏱️ Tổng thời lượng: <strong id="preview-duration">0s</strong>
              </span>
              <button type="button" class="btn btn-sm btn-outline-success" id="btn-download-audio">
                💾 Tải file về máy
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Cột phải: Thông tin lưu bài nghe --}}
    <div class="col-lg-5">
      <div class="card bg-dark text-white border-secondary shadow-lg">
        <div class="card-header border-secondary bg-black py-3">
          <h5 class="mb-0 text-success font-weight-bold">
            📝 Thông Tin Bài Luyện Nghe Mới
          </h5>
        </div>
        <div class="card-body">
          <form id="merge-save-form">
            
            <div class="form-group">
              <label for="title" class="font-weight-bold">Tiêu đề bài nghe <span class="text-danger">*</span></label>
              <input type="text" class="form-control bg-dark text-white border-secondary" id="title" required placeholder="Ví dụ: Ghép bài nghe IELTS Listening Section 1 & 2">
            </div>

            <div class="row">
              <div class="col-md-6 form-group">
                <label for="level" class="font-weight-bold">Cấp độ <span class="text-danger">*</span></label>
                <select class="form-control bg-dark text-white border-secondary" id="level" required>
                  <option value="A1">A1 (Cơ bản)</option>
                  <option value="A2">A2</option>
                  <option value="B1" selected>B1 (Trung cấp)</option>
                  <option value="B2">B2</option>
                  <option value="C1">C1</option>
                  <option value="C2">C2 (Thành thạo)</option>
                </select>
              </div>
              <div class="col-md-6 form-group">
                <label for="score_level" class="font-weight-bold">Điểm đạt chuẩn (%)</label>
                <input type="number" class="form-control bg-dark text-white border-secondary" id="score_level" value="80" min="0" max="100">
              </div>
            </div>

            <div class="form-group">
              <label for="topic_id" class="font-weight-bold">Chủ đề (Topic)</label>
              <select class="form-control bg-dark text-white border-secondary" id="topic_id">
                <option value="">-- Không chọn chủ đề --</option>
                @foreach($topics as $topic)
                  <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Liên kết tài liệu --}}
            <div class="bg-black p-3 rounded mb-3 border border-secondary">
              <h6 class="font-weight-bold text-muted mb-3">📚 Liên kết Giáo Trình / Tài Liệu</h6>
              
              <div class="form-group">
                <label for="document_id" class="small">Tài liệu (Document)</label>
                <select class="form-control bg-dark text-white border-secondary form-control-sm" id="document_id">
                  <option value="">-- Không liên kết tài liệu --</option>
                  @foreach($documents as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->name }} ({{ strtoupper($doc->level) }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="chapter_id" class="small">Chương (Part)</label>
                <select class="form-control bg-dark text-white border-secondary form-control-sm" id="chapter_id">
                  <option value="">-- Chọn Chương (Part) --</option>
                </select>
              </div>

              <div class="form-group">
                <label for="unit_id" class="small">Bài học (Unit)</label>
                <select class="form-control bg-dark text-white border-secondary form-control-sm" id="unit_id">
                  <option value="">-- Chọn Bài (Unit) --</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="transcript" class="font-weight-bold">Lời thoại gốc (Transcript - Tiếng Anh)</label>
              <textarea class="form-control bg-dark text-white border-secondary" id="transcript" rows="4" placeholder="Nhập transcript tiếng Anh của bài nghe..."></textarea>
            </div>

            <div class="form-group">
              <label for="translation" class="font-weight-bold">Bản dịch (Translation - Tiếng Việt)</label>
              <textarea class="form-control bg-dark text-white border-secondary" id="translation" rows="4" placeholder="Nhập bản dịch tiếng Việt tương ứng..."></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold" id="btn-save-merged" disabled>
              💾 Lưu lên hệ thống
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- Modal: Chọn File Nghe từ Hệ Thống --}}
<div class="modal fade" id="systemAudiosModal" tabindex="-1" role="dialog" aria-labelledby="systemAudiosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content bg-dark text-white border-secondary">
      <div class="modal-header border-secondary bg-black">
        <h5 class="modal-title text-cyan" id="systemAudiosModalLabel">🔍 Chọn File Nghe Hệ Thống</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="form-group">
          <input type="text" class="form-control bg-dark text-white border-secondary" id="search-system-audio" placeholder="Tìm kiếm file nghe theo tiêu đề...">
        </div>

        <div style="max-height: 400px; overflow-y: auto;">
          <table class="table table-dark table-hover table-striped mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 70%;">Tiêu đề</th>
                <th scope="col" style="width: 15%; text-align: center;">Thời lượng</th>
                <th scope="col" style="width: 15%; text-align: center;">Hành động</th>
              </tr>
            </thead>
            <tbody id="system-audio-tbody">
              @forelse($existingAudios as $audio)
                <tr class="audio-row" data-title="{{ strtolower($audio->title) }}">
                  <td>
                    <div class="font-weight-bold">{{ $audio->title }}</div>
                    <div class="text-muted small text-truncate" style="max-width: 450px;">{{ $audio->link }}</div>
                  </td>
                  <td class="text-center align-middle">{{ $audio->duration }}s</td>
                  <td class="text-center align-middle">
                    <button type="button" class="btn btn-xs btn-outline-info btn-add-system-audio" 
                            data-id="{{ $audio->id }}" 
                            data-title="{{ $audio->title }}" 
                            data-link="{{ $audio->link }}" 
                            data-duration="{{ $audio->duration }}">
                      ➕ Thêm
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Không tìm thấy file nghe nào trên hệ thống.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer border-secondary bg-black">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

{{-- Inline CSS --}}
<style>
  .text-cyan {
    color: #0dcaf0;
  }
  .hover-bg-dark:hover {
    background-color: rgba(255, 255, 255, 0.05) !important;
  }
  .cursor-pointer {
    cursor: pointer;
  }
  .transition-all {
    transition: all 0.2s ease-in-out;
  }
  .btn-xs {
    padding: 0.15rem 0.4rem;
    font-size: 0.75rem;
  }
  .track-card {
    background-color: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    padding: 10px 15px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: grab;
    transition: background-color 0.2s;
  }
  .track-card:hover {
    background-color: rgba(255, 255, 255, 0.07);
  }
  .track-card:active {
    cursor: grabbing;
  }
  .track-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }
  .track-drag-handle {
    color: #6c757d;
    cursor: grab;
  }
  .track-details {
    min-width: 0;
  }
  .track-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .track-meta {
    font-size: 0.75rem;
    color: #a0aec0;
  }
</style>

{{-- Scripts --}}
{{-- Sử dụng CDN cho SortableJS và LameJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lamejs/1.2.1/lame.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // 1. Dữ liệu tài liệu liên kết
  const documentsData = @json($documents->load('parts.units'));
  const docSelect = document.getElementById('document_id');
  const chapterSelect = document.getElementById('chapter_id');
  const unitSelect = document.getElementById('unit_id');

  docSelect.addEventListener('change', function() {
    const docId = this.value;
    chapterSelect.innerHTML = '<option value="">-- Chọn Chương (Part) --</option>';
    unitSelect.innerHTML = '<option value="">-- Chọn Bài (Unit) --</option>';
    
    if (!docId) return;
    
    const doc = documentsData.find(d => d.id == docId);
    if (doc && doc.parts) {
      doc.parts.forEach(part => {
        const opt = document.createElement('option');
        opt.value = part.id;
        opt.textContent = part.name;
        chapterSelect.appendChild(opt);
      });
    }
  });

  chapterSelect.addEventListener('change', function() {
    const docId = docSelect.value;
    const partId = this.value;
    unitSelect.innerHTML = '<option value="">-- Chọn Bài (Unit) --</option>';
    
    if (!docId || !partId) return;
    
    const doc = documentsData.find(d => d.id == docId);
    if (doc && doc.parts) {
      const part = doc.parts.find(p => p.id == partId);
      if (part && part.units) {
        part.units.forEach(unit => {
          const opt = document.createElement('option');
          opt.value = unit.id;
          opt.textContent = unit.name;
          unitSelect.appendChild(opt);
        });
      }
    }
  });

  // 2. Quản lý danh sách Track âm thanh
  let tracks = []; // Array of { id, name, type: 'local'|'system', data: File|URL, duration }
  let mergedAudioBlob = null;
  let mergedAudioDuration = 0;

  const trackListEl = document.getElementById('track-list');
  const emptyTracksState = document.getElementById('empty-tracks-state');
  const trackCountEl = document.getElementById('track-count');

  // Khởi tạo kéo thả Sortable cho danh sách track
  new Sortable(trackListEl, {
    handle: '.track-drag-handle',
    animation: 150,
    onEnd: function() {
      // Sắp xếp lại mảng tracks tương ứng với giao diện mới
      const newOrder = Array.from(trackListEl.children).map(el => el.getAttribute('data-track-id'));
      const sortedTracks = [];
      newOrder.forEach(id => {
        const t = tracks.find(track => track.id === id);
        if (t) sortedTracks.push(t);
      });
      tracks = sortedTracks;
      updateUI();
    }
  });

  function updateUI() {
    trackListEl.innerHTML = '';
    if (tracks.length === 0) {
      emptyTracksState.style.display = 'block';
      trackCountEl.textContent = '0 tracks';
      trackCountEl.className = 'badge badge-secondary';
      return;
    }

    emptyTracksState.style.display = 'none';
    trackCountEl.textContent = `${tracks.length} track${tracks.length > 1 ? 's' : ''}`;
    trackCountEl.className = 'badge badge-info';

    tracks.forEach((track, index) => {
      const card = document.createElement('div');
      card.className = 'track-card';
      card.setAttribute('data-track-id', track.id);
      
      const durationStr = track.duration ? `${track.duration}s` : 'Đang tải...';
      const sourceBadge = track.type === 'local' 
        ? '<span class="badge badge-pill badge-primary">Máy tính</span>' 
        : '<span class="badge badge-pill badge-info">Hệ thống</span>';

      card.innerHTML = `
        <div class="track-info">
          <span class="track-drag-handle">☰</span>
          <span class="font-weight-bold text-muted">${index + 1}.</span>
          <div class="track-details">
            <div class="track-title text-white" title="${track.name}">${track.name}</div>
            <div class="track-meta">
              ${sourceBadge} • ⏱️ ${durationStr}
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-link text-danger p-0 btn-remove-track" data-id="${track.id}">
          ❌
        </button>
      `;
      trackListEl.appendChild(card);
    });

    // Reset preview player if tracks change
    document.getElementById('preview-container').style.display = 'none';
    document.getElementById('btn-save-merged').disabled = true;
    mergedAudioBlob = null;
  }

  // Thêm track từ máy tính
  const fileInput = document.getElementById('file-input');
  const dropZone = document.getElementById('drop-zone');

  fileInput.addEventListener('change', function() {
    handleFiles(this.files);
  });

  // Drag and drop events
  dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.borderColor = '#0dcaf0';
    this.style.backgroundColor = 'rgba(13, 202, 240, 0.05)';
  });

  dropZone.addEventListener('dragleave', function() {
    this.style.borderColor = '#6c757d';
    this.style.backgroundColor = 'rgba(255, 255, 255, 0.02)';
  });

  dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = '#6c757d';
    this.style.backgroundColor = 'rgba(255, 255, 255, 0.02)';
    handleFiles(e.dataTransfer.files);
  });

  async function handleFiles(files) {
    for (let file of files) {
      if (!file.type.startsWith('audio/')) {
        alert(`Tệp "${file.name}" không phải là file âm thanh.`);
        continue;
      }

      const id = 'local_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
      const track = {
        id: id,
        name: file.name,
        type: 'local',
        data: file,
        duration: 0
      };

      tracks.push(track);
      updateUI();

      // Đọc thời lượng bằng AudioContext tạm thời
      try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const arrayBuffer = await file.arrayBuffer();
        const audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
        track.duration = Math.round(audioBuffer.duration);
        audioCtx.close();
        updateUI();
      } catch (err) {
        console.error('Không thể đọc thời lượng file:', err);
      }
    }
  }

  // Thêm track từ Hệ Thống
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-add-system-audio')) {
      const btn = e.target;
      const id = 'sys_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
      const track = {
        id: id,
        name: btn.getAttribute('data-title'),
        type: 'system',
        data: btn.getAttribute('data-link'),
        duration: parseInt(btn.getAttribute('data-duration')) || 0
      };

      tracks.push(track);
      updateUI();
      
      // Hiệu ứng đổi màu nút tạm thời để báo hiệu đã thêm
      btn.textContent = '✅ Đã thêm';
      btn.className = 'btn btn-xs btn-success';
      setTimeout(() => {
        btn.textContent = '➕ Thêm';
        btn.className = 'btn btn-xs btn-outline-info';
      }, 800);
    }
  });

  // Xóa track khỏi danh sách
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-remove-track')) {
      const id = e.target.getAttribute('data-id');
      tracks = tracks.filter(t => t.id !== id);
      updateUI();
    }
  });

  // Tìm kiếm file hệ thống
  const searchInput = document.getElementById('search-system-audio');
  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll('.audio-row').forEach(row => {
      const title = row.getAttribute('data-title');
      if (title.includes(query)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  // 3. LOGIC GHÉP FILE NGHE (WEB AUDIO API ENGINE)
  const btnMerge = document.getElementById('btn-merge-audio');
  
  btnMerge.addEventListener('click', async function() {
    if (tracks.length === 0) {
      alert('Vui lòng chọn ít nhất 1 file nghe để ghép!');
      return;
    }

    btnMerge.disabled = true;
    btnMerge.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ⏳ Đang giải mã & ghép file...';

    try {
      const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      const silenceDuration = parseFloat(document.getElementById('silence-gap').value) || 0;
      const audioBuffers = [];

      // Giải mã tuần tự tất cả các track
      for (let i = 0; i < tracks.length; i++) {
        const track = tracks[i];
        btnMerge.innerHTML = `⏳ Đang giải mã track ${i + 1}/${tracks.length}...`;
        
        try {
          let arrayBuffer;
          if (track.type === 'local') {
            arrayBuffer = await track.data.arrayBuffer();
          } else {
            // Tải file từ hệ thống
            const response = await fetch(track.data);
            if (!response.ok) throw new Error('Không thể tải file nghe hệ thống.');
            arrayBuffer = await response.arrayBuffer();
          }
          const audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
          audioBuffers.push(audioBuffer);
        } catch (err) {
          console.error(`Lỗi giải mã track ${track.name}:`, err);
          alert(`Không thể giải mã tệp tin: ${track.name}. File có thể bị lỗi hoặc định dạng không được hỗ trợ.`);
          btnMerge.disabled = false;
          btnMerge.innerHTML = '⚡ Bắt đầu ghép file';
          return;
        }
      }

      btnMerge.innerHTML = '⏳ Đang trộn & xuất buffer...';

      // Lấy thông số chung
      const sampleRate = audioBuffers[0].sampleRate;
      const numberOfChannels = Math.max(...audioBuffers.map(b => b.numberOfChannels));
      
      // Tính toán độ dài tổng (bao gồm cả khoảng lặng)
      let totalLength = 0;
      for (let i = 0; i < audioBuffers.length; i++) {
        totalLength += audioBuffers[i].length;
        if (i < audioBuffers.length - 1) {
          totalLength += silenceDuration * sampleRate;
        }
      }

      // Tạo buffer đích
      const mergedBuffer = audioCtx.createBuffer(numberOfChannels, totalLength, sampleRate);

      // Trộn dữ liệu của từng channel
      for (let channel = 0; channel < numberOfChannels; channel++) {
        const channelData = mergedBuffer.getChannelData(channel);
        let offset = 0;

        for (let i = 0; i < audioBuffers.length; i++) {
          const buffer = audioBuffers[i];
          // Tránh lỗi lệch số lượng kênh của các file khác nhau
          const sourceChannelData = buffer.numberOfChannels > channel 
            ? buffer.getChannelData(channel) 
            : buffer.getChannelData(0);

          channelData.set(sourceChannelData, offset);
          offset += buffer.length;

          if (i < audioBuffers.length - 1) {
            offset += silenceDuration * sampleRate;
          }
        }
      }

      const format = document.getElementById('output-format').value;
      let finalBlob;
      
      if (format === 'mp3') {
        btnMerge.innerHTML = '⏳ Đang nén sang định dạng MP3...';
        // Đợi 50ms để giao diện kịp cập nhật chữ hiển thị trước khi chạy đồng thì
        await new Promise(resolve => setTimeout(resolve, 50));
        finalBlob = bufferToMp3(mergedBuffer);
      } else {
        btnMerge.innerHTML = '⏳ Đang đóng gói định dạng WAV...';
        await new Promise(resolve => setTimeout(resolve, 50));
        finalBlob = bufferToWav(mergedBuffer);
      }

      mergedAudioBlob = finalBlob;
      mergedAudioDuration = Math.round(mergedBuffer.duration);

      // Hiển thị trình phát
      const audioUrl = URL.createObjectURL(finalBlob);
      const previewPlayer = document.getElementById('preview-player');
      previewPlayer.src = audioUrl;
      
      document.getElementById('preview-duration').textContent = formatSeconds(mergedAudioDuration);
      document.getElementById('btn-download-audio').textContent = `💾 Tải file ${format.toUpperCase()} về máy`;
      document.getElementById('preview-container').style.display = 'block';
      document.getElementById('btn-save-merged').disabled = false;

      audioCtx.close();
      
      alert('⚡ Ghép file thành công! Bạn có thể nghe thử ở khung phát nhạc bên dưới.');
    } catch (err) {
      console.error('Lỗi ghép nhạc:', err);
      alert('Có lỗi xảy ra trong quá trình ghép âm thanh.');
    } finally {
      btnMerge.disabled = false;
      btnMerge.innerHTML = '⚡ Bắt đầu ghép file';
    }
  });

  // Tải file về máy tính cục bộ
  document.getElementById('btn-download-audio').addEventListener('click', function() {
    if (!mergedAudioBlob) return;
    const url = URL.createObjectURL(mergedAudioBlob);
    const a = document.createElement('a');
    a.href = url;
    
    // Tạo tên file tải về tiện dụng
    let safeTitle = document.getElementById('title').value.trim().toLowerCase()
      .replace(/[^a-z0-9]+/g, '_')
      .replace(/(^_+|_+$)/g, '');
    if (!safeTitle) safeTitle = 'merged_audio';

    const format = document.getElementById('output-format').value;
    a.download = `${safeTitle}.${format}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  });

  // 4. GỬI LÊN SERVER ĐỂ LƯU
  const saveForm = document.getElementById('merge-save-form');
  saveForm.addEventListener('submit', function(e) {
    e.preventDefault();

    if (!mergedAudioBlob) {
      alert('Vui lòng ghép file nghe thử trước khi lưu!');
      return;
    }

    const btnSave = document.getElementById('btn-save-merged');
    btnSave.disabled = true;
    btnSave.textContent = '⏳ Đang lưu bài học...';

    const format = document.getElementById('output-format').value;

    const formData = new FormData();
    formData.append('title', document.getElementById('title').value.trim());
    formData.append('level', document.getElementById('level').value);
    formData.append('score_level', document.getElementById('score_level').value);
    formData.append('topic_id', document.getElementById('topic_id').value);
    formData.append('document_id', document.getElementById('document_id').value);
    formData.append('chapter_id', document.getElementById('chapter_id').value);
    formData.append('unit_id', document.getElementById('unit_id').value);
    formData.append('transcript', document.getElementById('transcript').value.trim());
    formData.append('translation', document.getElementById('translation').value.trim());
    formData.append('duration', mergedAudioDuration);
    
    // Đính kèm file nhị phân (MP3 hoặc WAV)
    formData.append('audio_data', mergedAudioBlob, `merged.${format}`);

    fetch("{{ route('listening.merger.store') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        window.location.href = data.redirect_url;
      } else {
        alert('Lỗi: ' + data.message);
        btnSave.disabled = false;
        btnSave.textContent = '💾 Lưu lên hệ thống';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Có lỗi kết nối máy chủ khi lưu bài nghe.');
      btnSave.disabled = false;
      btnSave.textContent = '💾 Lưu lên hệ thống';
    });
  });

  // HÀM BỔ TRỢ: Đóng gói MP3 từ AudioBuffer sử dụng lamejs
  function bufferToMp3(buffer, bitrate = 128) {
    const numOfChan = buffer.numberOfChannels;
    const sampleRate = buffer.sampleRate;
    const mp3encoder = new lamejs.Mp3Encoder(numOfChan, sampleRate, bitrate);
    const mp3Data = [];
    const sampleBlockSize = 1152;

    const left = buffer.getChannelData(0);
    const right = numOfChan > 1 ? buffer.getChannelData(1) : null;

    // Chuyển Float32 sang Int16 cho lamejs
    const convertFloat32ToInt16 = (arr) => {
      const l = arr.length;
      const buf = new Int16Array(l);
      for (let i = 0; i < l; i++) {
        let s = Math.max(-1, Math.min(1, arr[i]));
        buf[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
      }
      return buf;
    };

    const leftInt16 = convertFloat32ToInt16(left);
    const rightInt16 = right ? convertFloat32ToInt16(right) : null;

    for (let i = 0; i < leftInt16.length; i += sampleBlockSize) {
      const leftChunk = leftInt16.subarray(i, i + sampleBlockSize);
      let mp3buf;
      if (rightInt16) {
        const rightChunk = rightInt16.subarray(i, i + sampleBlockSize);
        mp3buf = mp3encoder.encodeBuffer(leftChunk, rightChunk);
      } else {
        mp3buf = mp3encoder.encodeBuffer(leftChunk);
      }
      if (mp3buf.length > 0) {
        mp3Data.push(mp3buf);
      }
    }

    const mp3buf = mp3encoder.flush();
    if (mp3buf.length > 0) {
      mp3Data.push(mp3buf);
    }

    return new Blob(mp3Data, { type: 'audio/mp3' });
  }

  // HÀM BỔ TRỢ: Đóng gói WAV từ AudioBuffer
  function bufferToWav(buffer) {
    let numOfChan = buffer.numberOfChannels,
        btwLength = buffer.length * numOfChan * 2 + 44,
        btwBuffer = new ArrayBuffer(btwLength),
        btwView = new DataView(btwBuffer),
        btwChannels = [],
        btwIndex = 0,
        btwSample = 0,
        btwOffset = 0;

    function writeString(view, offset, string) {
      for (let i = 0; i < string.length; i++) {
        view.setUint8(offset + i, string.charCodeAt(i));
      }
    }

    /* RIFF identifier */
    writeString(btwView, 0, 'RIFF');
    /* file length */
    btwView.setUint32(4, btwLength - 8, true);
    /* RIFF type */
    writeString(btwView, 8, 'WAVE');
    /* format chunk identifier */
    writeString(btwView, 12, 'fmt ');
    /* format chunk length */
    btwView.setUint32(16, 16, true);
    /* sample format (raw) */
    btwView.setUint16(20, 1, true);
    /* channel count */
    btwView.setUint16(22, numOfChan, true);
    /* sample rate */
    btwView.setUint32(24, buffer.sampleRate, true);
    /* byte rate (sample rate * block align) */
    btwView.setUint32(28, buffer.sampleRate * numOfChan * 2, true);
    /* block align (channel count * bytes per sample) */
    btwView.setUint16(32, numOfChan * 2, true);
    /* bits per sample */
    btwView.setUint16(34, 16, true);
    /* data chunk identifier */
    writeString(btwView, 36, 'data');
    /* data chunk length */
    btwView.setUint32(40, btwLength - 44, true);

    for (let i = 0; i < numOfChan; i++) {
      btwChannels.push(buffer.getChannelData(i));
    }

    btwOffset = 44;
    while (btwIndex < buffer.length) {
      for (let i = 0; i < numOfChan; i++) {
        btwSample = Math.max(-1, Math.min(1, btwChannels[i][btwIndex]));
        btwSample = btwSample < 0 ? btwSample * 0x8000 : btwSample * 0x7FFF;
        btwView.setInt16(btwOffset, btwSample, true);
        btwOffset += 2;
      }
      btwIndex++;
    }

    return new Blob([btwBuffer], { type: 'audio/wav' });
  }

  // Định dạng giây thành phút:giây
  function formatSeconds(secs) {
    const m = Math.floor(secs / 60);
    const s = secs % 60;
    return `${m}m ${s}s`;
  }

});
</script>
@endsection
