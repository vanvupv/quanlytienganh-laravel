@extends('skills.listening.index')

@section('title', 'Skill - Listening')

@section('content_listening')
  <!-- Main Content -->
  <div class="container-fluid">
    <!-- Section 5 - -->
    <!--  -->
    <section class="skill__english">
      <div class="card">
        <!-- Start Skills - Listening -->
        <div class="container py-4">
          <h2 class="fw-bold mb-4">📘 {{ $document->name ?? $document->title }}</h2>

          {{-- CSS nhỏ để loại bullet nếu cần --}}
          <style>
            .list-unstyled {
              list-style: none;
              padding-left: 0;
              margin-left: 0;
            }

            .tree-part {
              padding: .5rem 0;
              border-bottom: 1px dashed #ececec;
            }

            .tree-unit {
              margin-left: 1rem;
              padding: .25rem 0;
            }

            .tree-file {
              margin-left: 2rem;
            }

            .toggle-btn {
              cursor: pointer;
              user-select: none;
            }
          </style>
        </div>

        <div class="section__skills--listening">
          <!-- Tiêu đề -->
          <h2>Kỹ năng Nghe -- Listening</h2>
          <!-- Bảng lưu trữ tài liệu -->
          <h2>📖 Bảng lưu trữ Tài liệu Listening</h2>
          <div class="table-responsive-custom">
            <table class="skills__ table table-striped table-bordered align-middle">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Tiêu đề</th>
                  <th>Đường dẫn</th>
                  <th>Cấp độ</th>
                  <th>Chủ đề</th>
                  <th>Loại Reading</th>
                  <th>Độ dài (từ)</th>
                  <th>Thời gian (phút)</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>A Short Story about Travel</td>
                  <td><a href="reading/unit1.pdf">unit1.pdf</a></td>
                  <td>A2</td>
                  <td>Travel</td>
                  <td>Story</td>
                  <td>350</td>
                  <td>15</td>
                  <td>Một câu chuyện ngắn về chuyến đi.</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Education Article</td>
                  <td><a href="reading/unit2.pdf">unit2.pdf</a></td>
                  <td>B1</td>
                  <td>Education</td>
                  <td>Article</td>
                  <td>500</td>
                  <td>20</td>
                  <td>Bài báo về hệ thống giáo dục.</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Reading Comprehension Questions</td>
                  <td><a href="reading/unit3.pdf">unit3.pdf</a></td>
                  <td>B2</td>
                  <td>Environment</td>
                  <td>Question-based</td>
                  <td>250</td>
                  <td>10</td>
                  <td>Bài đọc ngắn kèm theo bộ câu hỏi trắc nghiệm.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--  -->

          <!--  -->
          <!--  Cây Kỹ năng Nghe -->
          <div class="container mt-4">
            <h3 class="mb-3">🎧 Tiến trình phát triển kỹ năng Nghe</h3>
            <div class="row">
              <!-- Giai đoạn 1 -->
              <div class="col-md-4">
                <div class="skill-stage stage-1">
                  <h5>Giai đoạn 1<br><small>(Nhận diện âm & từ)</small></h5>

                  <div class="field">
                    <b>Mục tiêu:</b>
                    <ul class="tree">
                      <li><span class="new">Làm quen với phát âm tiếng Anh</span></li>
                      <li><span class="new">Nhận biết các từ quen thuộc qua nghe</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Hoạt động:</b>
                    <ul class="tree">
                      <li><span class="new">Nghe các từ đơn lẻ, số, màu, đồ vật</span></li>
                      <li><span class="new">Phân biệt âm cuối /s/, /z/, /t/, /d/</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Tài liệu:</b>
                    <span class="new">BBC Learning English - Sounds of English</span>
                  </div>
                </div>
              </div>

              <!-- Giai đoạn 2 -->
              <div class="col-md-4">
                <div class="skill-stage stage-2">
                  <h5>Giai đoạn 2<br><small>(Nghe hiểu câu & hội thoại ngắn)</small></h5>

                  <div class="field">
                    <b>Mục tiêu:</b>
                    <ul class="tree">
                      <li><span class="old">Nghe hiểu từ vựng quen thuộc</span></li>
                      <li><span class="new">Nghe được cấu trúc câu ngắn, thông tin chính</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Hoạt động:</b>
                    <ul class="tree">
                      <li><span class="new">Nghe hội thoại ngắn theo chủ đề (Travel, School...)</span></li>
                      <li><span class="new">Điền vào chỗ trống, chọn đáp án đúng</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Tài liệu:</b>
                    <span class="new">Oxford Listening Practice A2–B1</span>
                  </div>
                </div>
              </div>

              <!-- Giai đoạn 3 -->
              <div class="col-md-4">
                <div class="skill-stage stage-3">
                  <h5>Giai đoạn 3<br><small>(Nghe hiểu nâng cao & thực tế)</small></h5>

                  <div class="field">
                    <b>Mục tiêu:</b>
                    <ul class="tree">
                      <li><span class="old">Nghe hiểu hội thoại ngắn</span></li>
                      <li><span class="new">Nắm ý chính và chi tiết trong đoạn hội thoại dài</span></li>
                      <li><span class="new">Hiểu nội dung qua giọng khác nhau</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Hoạt động:</b>
                    <ul class="tree">
                      <li><span class="new">Nghe podcast, tin tức, bài giảng thực tế</span></li>
                      <li><span class="new">Tóm tắt nội dung sau khi nghe</span></li>
                    </ul>
                  </div>

                  <div class="field">
                    <b>Tài liệu:</b>
                    <span class="new">TED-Ed Listening • VOA Learning English</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Cây Kỹ năng Nghe -->

          <!-- Chi tiết tài liệu (Document Detail) -->


          <!-- Left: nội dung chính -->
          <div class="col-12">
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
                  <h4>3. Fill the blank: "I ___ fine, thanks." <span class="small"
                      style="margin-left:8px">(Fill-blank)</span></h4>
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
        </div>
        <!-- End Skills - Listening -->
      </div>
    </section>
    <!-- End Section 5 - -->
  </div>
@endsection

@push('script')
  <script>
    // Basic interactive logic
    const audio = document.getElementById('audioPlayer');
    const btnPlay = document.getElementById('btn-play');
    const btnDownload = document.getElementById('btn-download');
    const toggleTranscript = document.getElementById('toggleTranscript');
    const transcriptContainer = document.getElementById('transcriptContainer');
    const durationLabel = document.getElementById('durationLabel');
    const durationSecs = document.getElementById('durationSecs');
    const scoreBox = document.getElementById('scoreBox');

    // Play / Pause button
    btnPlay.addEventListener('click', () => {
      if (audio.paused) { audio.play(); btnPlay.textContent = 'Tạm dừng'; }
      else { audio.pause(); btnPlay.textContent = 'Phát'; }
    });

    // Download button (simple link)
    btnDownload.addEventListener('click', () => {
      const url = 'Headway5e_Elem_SB_1.8.mp3';
      const a = document.createElement('a');
      a.href = url;
      a.download = url.split('/').pop();
      document.body.appendChild(a);
      a.click();
      a.remove();
    });

    toggleTranscript.addEventListener('click', () => {
      transcriptContainer.style.display = transcriptContainer.style.display === 'none' ? 'block' : 'none';
    });

    // When metadata loaded, update duration (if available)
    audio.addEventListener('loadedmetadata', () => {
      const s = Math.floor(audio.duration || 0);
      durationSecs.textContent = s;
      // format mm:ss
      const mm = Math.floor(s / 60), ss = s % 60;
      durationLabel.textContent = `${mm}:${ss.toString().padStart(2, '0')}`;
    });

    // Check answers (very simple)
    document.getElementById('btn-check').addEventListener('click', () => {
      let score = 0, total = 3;
      // q1
      const q1 = document.querySelector('input[name="q1"]:checked');
      if (q1 && q1.value === 'A') score++;
      // q2
      const q2 = document.querySelector('input[name="q2"]:checked');
      if (q2 && q2.value === 'A') score++;
      // q3
      const q3 = document.getElementById('q3').value.trim().toLowerCase();
      if (q3 === 'am') score++;
      scoreBox.textContent = `Điểm: ${score}/${total}`;
      scoreBox.style.color = (score === total) ? '#059669' : '#d97706';
    });

    document.getElementById('btn-reset').addEventListener('click', () => {
      document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
      document.getElementById('q3').value = '';
      scoreBox.textContent = '';
    });
  </script>
@endpush