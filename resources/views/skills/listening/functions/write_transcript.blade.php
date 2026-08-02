<!-- Listening Task: Nghe và Viết lại Transcript -->
<div class="col-6">
  <div class="card">
    <h3 style="margin-bottom:10px;font-size:17px">✍️ Yêu cầu 1: Nghe và viết lại transcript</h3>
    <!-- Thông tin Tài liệu -->
    <!-- ===== Thông tin tài liệu (4 cột) ===== -->
    <div class="card p-3 mb-3">
      <div class="row g-3 align-items-start">

        <!-- Cột 1: Tên file -->
        <div class="col-12 col-md-3">
          <div class="small text-muted mb-1">Tên file</div>
          <div class="fw-semibold">Headway5e_Elem_SB_1.8.mp3</div>
        </div>

        <!-- Cột 2: Chủ đề -->
        <div class="col-12 col-md-3">
          <div class="small text-muted mb-1">Chủ đề</div>
          <div>Conversation</div>
        </div>

        <!-- Cột 3: Cấp độ -->
        <div class="col-12 col-md-3">
          <div class="small text-muted mb-1">Cấp độ</div>
          <div>TACB 1</div>
        </div>

        <!-- Cột 4: Tài liệu -->
        <div class="col-12 col-md-3">
          <div class="small text-muted mb-1">Tài liệu</div>
          <div>Unit 1 – TACB – DHXD</div>
        </div>
      </div>
    </div>

    <!-- Audio player -->
    <!-- ====== Audio player area ====== -->
    <div class="audio-area">
      <div class="">
        <audio id="audioPlayer" controls preload="metadata" style="width:100%">
          <source src="Headway5e_Elem_SB_1.8.mp3" type="audio/mpeg">
          Trình duyệt của bạn không hỗ trợ audio.
        </audio>
      </div>

      <!-- Nút phát / tạm dừng -->
      <div class="d-flex align-items-center flex-wrap gap-2">
        <button class="btn" id="btnPlay">▶️ Phát</button>
        <button class="btn secondary" id="btnPause">⏸ Tạm dừng</button>
      </div>
    </div>

    <!-- Transcript writing area -->
    <section style="margin-top:20px">
      <div class="small text-muted mb-2">
        Nghe file audio ở trên và gõ lại toàn bộ nội dung bạn nghe được.
        <br>Hệ thống sẽ lưu lại tiến trình của bạn tự động (hoặc sau khi nhấn Nộp bài).
      </div>

      <textarea id="transcriptInput" rows="10" placeholder="Nhập transcript bạn nghe được..."
        style="width:100%;padding:10px;border-radius:10px;border:1px solid #e1e5ea;font-family:monospace;font-size:15px;line-height:1.5;resize:vertical"></textarea>

      <div
        style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;flex-wrap:wrap;gap:10px">
        <div class="small text-muted">Bạn có thể nghe lại nhiều lần để hoàn thiện transcript.</div>

        <div style="display:flex;gap:8px">
          <button class="btn secondary" id="btn-clear">Xóa hết</button>
          <button class="btn" id="btn-save">💾 Lưu bản nháp</button>
          <button class="btn accent" id="btn-submit">✅ Nộp bài</button>
        </div>
      </div>

      <div id="saveStatus" style="margin-top:8px;font-size:14px;color:var(--accent)"></div>
    </section>
  </div>
</div>

<script>
  const audio = document.getElementById('audioPlayer');
  const playBtn = document.getElementById('btn-play');
  const transcriptInput = document.getElementById('transcriptInput');
  const saveStatus = document.getElementById('saveStatus');

  // Nút phát audio
  playBtn.addEventListener('click', () => {
    if (audio.paused) {
      audio.play();
      playBtn.textContent = '⏸ Tạm dừng';
    } else {
      audio.pause();
      playBtn.textContent = '▶️ Phát';
    }
  });

  // Xóa transcript
  document.getElementById('btn-clear').addEventListener('click', () => {
    if (confirm('Bạn có chắc muốn xóa toàn bộ nội dung?')) {
      transcriptInput.value = '';
      saveStatus.textContent = 'Đã xóa nội dung.';
    }
  });

  // Lưu bản nháp (demo)
  document.getElementById('btn-save').addEventListener('click', () => {
    localStorage.setItem('draftTranscript', transcriptInput.value);
    saveStatus.textContent = 'Đã lưu bản nháp tạm thời.';
  });

  // Tải lại bản nháp khi load trang
  window.addEventListener('load', () => {
    const saved = localStorage.getItem('draftTranscript');
    if (saved) transcriptInput.value = saved;
  });

  // Nộp bài
  document.getElementById('btn-submit').addEventListener('click', () => {
    const content = transcriptInput.value.trim();
    if (!content) {
      alert('Vui lòng nhập nội dung transcript trước khi nộp.');
      return;
    }
    // Gửi AJAX đến server
    saveStatus.textContent = 'Đang gửi dữ liệu...';
    fetch('/listening/submit-transcript', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        listening_id: 'LIST-0001',
        transcript: content
      })
    }).then(res => res.json())
      .then(data => saveStatus.textContent = '✅ Nộp bài thành công!')
      .catch(err => saveStatus.textContent = '❌ Lỗi khi nộp bài.');
  });
</script>