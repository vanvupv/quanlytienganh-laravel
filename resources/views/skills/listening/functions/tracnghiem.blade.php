<div class="col-6">
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
      <div>
        <h2 style="margin:0 0 6px;font-size:18px">Headway 5e Elementary – Unit 1.8</h2>
        <div class="small">File audio: <strong>Headway5e_Elem_SB_1.8.mp3</strong></div>
        <div class="meta" style="margin-top:10px">
          <div class="item"><strong>Loại:</strong> Conversation</div>
          <div class="item"><strong>Level:</strong> TACB 1</div>
          <div class="item"><strong>Duration:</strong> <span id="durationLabel">3:25</span></div>
          <div class="item"><strong>Tài liệu:</strong> Unit 1 - TACB - DHXD</div>
        </div>
      </div>

      <div style="min-width:180px;display:flex;flex-direction:column;align-items:flex-end;gap:8px">
        <div class="small">ID: <strong>LIST-0001</strong></div>
        <div class="controls">
          <button class="btn" id="btn-play">Phát</button>
          <button class="btn secondary" id="btn-download">Tải về</button>
        </div>
      </div>
    </div>

    <hr style="margin:14px 0;border:none;border-top:1px solid #eef2f6" />

    <div class="audio-area">
      <audio id="audioPlayer" controls preload="metadata">
        <source src="Headway5e_Elem_SB_1.8.mp3" type="audio/mpeg">
        Trình duyệt của bạn không hỗ trợ audio.
      </audio>

      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
        <button class="btn secondary" id="toggleTranscript">Hiển thị/Ẩn transcript</button>
        <div class="small">Transcript có thể được chỉnh sửa hoặc upload sau.</div>
      </div>

      <div id="transcriptContainer" style="margin-top:10px;display:none">
        <div class="transcript" id="transcriptText">
          A: Hi! How are you?
          B: I'm fine, thank you. And you?
          A: I'm good. Are you ready for the lesson?
          B: Yes, I am.
        </div>
      </div>
    </div>

    <section class="questions">
      <h3 style="margin:14px 0 8px">Câu hỏi mẫu</h3>

      <div class="q" data-qid="1">
        <h4>1. What does the man say first? <span class="small" style="margin-left:8px">(MCQ)</span>
        </h4>
        <div class="options">
          <label class="option"><input type="radio" name="q1" value="A"> A. Hello</label>
          <label class="option"><input type="radio" name="q1" value="B"> B. Goodbye</label>
          <label class="option"><input type="radio" name="q1" value="C"> C. Thanks</label>
        </div>
        <div class="small" style="margin-top:8px">Đáp án chuẩn: <span class="badge">A</span></div>
      </div>

      <div class="q" data-qid="2">
        <h4>2. How is the man? <span class="small" style="margin-left:8px">(MCQ)</span></h4>
        <div class="options">
          <label class="option"><input type="radio" name="q2" value="A"> A. Fine</label>
          <label class="option"><input type="radio" name="q2" value="B"> B. Sad</label>
          <label class="option"><input type="radio" name="q2" value="C"> C. Angry</label>
        </div>
        <div class="small" style="margin-top:8px">Đáp án chuẩn: <span class="badge">A</span></div>
      </div>

      <div class="q" data-qid="3">
        <h4>3. Fill the blank: "I ___ fine, thanks." <span class="small" style="margin-left:8px">(Fill-blank)</span>
        </h4>
        <div style="margin-top:6px">
          <input type="text" id="q3" placeholder="Nhập câu trả lời..."
            style="width:100%;padding:8px;border-radius:8px;border:1px solid #e6eef0">
        </div>
        <div class="small" style="margin-top:8px">Đáp án chuẩn: <span class="badge">am</span></div>
      </div>

      <div style="display:flex;gap:8px;margin-top:12px">
        <button class="btn" id="btn-check">Chấm thử</button>
        <button class="btn secondary" id="btn-reset">Xóa đáp án</button>
        <div id="scoreBox" style="margin-left:auto;font-weight:700;color:var(--accent);align-self:center">
        </div>
      </div>
    </section>
  </div>
</div>