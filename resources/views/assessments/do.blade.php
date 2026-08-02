@extends('assessments.app')

@section('title', 'Summary')

@section('content')

  <style>
    .card__question {
      position: sticky;
      top: 20px;
      /* cách mép trên 20px, có thể chỉnh */
      max-height: calc(100vh - 40px);
      /* không tràn màn hình */
      overflow-y: auto;
      /* cuộn nếu danh sách dài */
    }

    .card__main {
      /* position: fixed; */
      z-index: 1000;
      /* position: sticky; */
    }

    .card__main--question {
      position: relative;
    }

    /*  */
    #questionBar {
      display: flex;
      gap: 8px;
    }

    /*  */
    .card__main--question {
      position: relative;
      /* để con absolute bám vào */
    }

    .card__timer {
      position: fixed;
      top: 80px;
      /* mặc định đặt ở gần bên trái trên */
      left: 20px;
      cursor: move;
      background: #fff;
      padding: 8px 12px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      /* đảm bảo nổi trên tất cả */
    }

    /*  */
    .card__timer {
      width: 120px;
      /* hình vuông */
      height: 120px;
      display: flex;
      flex-direction: column;
      /* để sắp xếp text dọc */
      align-items: center;
      justify-content: center;

      background: #fff;
      /* nền trắng */
      border: 2px solid #007bff;
      /* viền xanh (Bootstrap primary) */
      border-radius: 16px;
      /* bo góc */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

      font-size: 1.4rem;
      /* chữ lớn hơn */
      font-weight: bold;
      color: #007bff;

      cursor: move;
      /* có icon kéo */
      z-index: 2000;
    }
  </style>

  <!-- Main Content -->
  <div class="container">
    <!-- Start Section 1 - Thông tin bào đánh giá -->
    <section class="section__1">
      <div class="card card__main">
        <div class="row">
          <div class="col-lg-4">
            <div class="card">
              <!--  -->
              <div class="card">
                <h1>{{ $assessment->title }}</h1>
                <p><strong>Chủ đề đánh giá:</strong> {{ $assessment->type }}</p>
                <p><strong>Khối lượng kiến thức:</strong> {{ $assessment->quantity }} Câu</p>
                <p><strong>Thời gian:</strong> {{ $assessment->duration }} phút</p>
                <p><strong>Thời gian kết thúc:</strong> {{ $endTime }} </p>
                <!--  -->
                <p><strong>Phương thức đánh giá:</strong> {{ $assessment->method }} </p>
                <p><strong>Mã buổi đánh giá:</strong> {{ $assessment->id }} </p>
              </div>

              <!--  -->
              <div class="card">
                <h1> Yêu cầu buổi đánh giá </h1>
                <p><strong>Điểm số tối thiểu:</strong> {{ $assessment->min_score }} phút</p>
                <p><strong>Hiệu suất tối thiểu:</strong> {{ $assessment->min_performance }} Câu</p>
                <p><strong>Độ khó bài đánh giá:</strong> {{ $assessment->id }} </p>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <!-- Vị trí Timmer ban đầu -->

          </div>
        </div>
      </div>
    </section>
    <!-- End Section 1 - -->

    <!-- Start Section 2 - Bài đánh giá - Question List -->
    <section class="section__2">
      <div class="card__main--question">
        <div class="row">
          <div class="col-lg-4">
            <div class="card card__question">
              <!-- Thanh câu hỏi -->
              <div class="question-bar mb-4 d-flex flex-wrap" id="questionBar">
                @foreach($assessment->details as $index => $question)
                  <div class="question-item rounded border text-center" data-question-index="{{ $index }}">
                    {{ $index + 1 }}
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <!-- Bài làm -->
            <form class="m-0" id="examForm" action="{{ route('assessments.submit', $assessment->id) }}" method="POST">
              @csrf
              <div class="row">
                @foreach($assessment->details as $index => $question)
                  <div class="col-lg-4">
                    <div class="mb-4">
                      <label class="form-label">
                        Câu {{ $index + 1 }}: {{ $question->question }}
                      </label>
                      <input type="text" name="answers[{{ $question->id }}]" class="form-control"
                        placeholder="Nhập câu trả lời của bạn">
                    </div>
                  </div>
                @endforeach
              </div>
              <button type="submit" class="btn btn-primary">Nộp bài</button>
            </form>
          </div>
        </div>
      </div>
      <!--  -->

      <!-- Section 2 - Category Main -->
      <div class="card mt-4 mb-4">
        <h1 class="text-center mt-4 mb-4">Chủ đề chính của Tiếng Anh</h1>

        <div class="row g-4 justify-content-center">
          <!-- Từ vựng -->
          <div class="col-md-4 col-sm-6">
            <div class="card vocabulary">
              <div class="card-header">📚 Từ vựng</div>
              <div class="card-body">
                <p>Tra cứu, học và ghi nhớ các từ vựng theo chủ đề, ví dụ minh họa và phát âm.</p>
              </div>
            </div>
          </div>

          <!-- Ngữ pháp -->
          <div class="col-md-4 col-sm-6">
            <div class="card grammar">
              <div class="card-header">✏️ Ngữ pháp</div>
              <div class="card-body">
                <p>Tìm hiểu các cấu trúc, thì, và quy tắc ngữ pháp trong tiếng Anh một cách dễ hiểu.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Kỹ năng -->
        <div class="col-12">
          <div class="card skills">
            <div class="card-header">🎯 Kỹ năng</div>
            <div class="card-body">
              <p class="mb-4">Luyện tập 4 kỹ năng nền tảng giúp bạn sử dụng tiếng Anh linh hoạt.</p>
              <div class="row g-3">
                <div class="col-md-3 col-6">
                  <div class="skill__card">
                    <div class="skill-icon">🎧</div>
                    <div class="skill-title">Nghe</div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="skill__card">
                    <div class="skill-icon">🗣️</div>
                    <div class="skill-title">Nói</div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="skill__card">
                    <div class="skill-icon">📖</div>
                    <div class="skill-title">Đọc</div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="skill__card">
                    <div class="skill-icon">✍️</div>
                    <div class="skill-title">Viết</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Section 2 - Category Main -->

      <!-- Section 3 - Timeline -->
      <div class="card mt-4 mb-4">
        <!--  -->
        <h1 class="text-center mt-4 mb-4">Giai đoạn phát triển của Tiếng Anh</h1>
        <!--  -->
        <div class="section__timeline--content">
          <div class="timeline">
            <!-- Giai đoạn 1 -->
            <div class="timeline__stage">
              <h2>Giai đoạn 1<br><small>(Nền tảng)</small></h2>
              <div class="timeline__category">
                <h3>Grammar</h3>
                <div class="items">
                  <div class="item new">Hiện tại đơn</div>
                  <div class="item new">Hiện tại tiếp diễn</div>
                  <div class="item new">So – such, Too – enough</div>
                  <div class="item new">Trực tiếp – gián tiếp</div>
                  <div class="item new">So sánh</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Vocabulary</h3>
                <div class="items">
                  <div class="item new">Gia đình</div>
                  <div class="item new">Trường học</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Skills</h3>
                <div class="items">
                  <div class="item new">Listening: cơ bản</div>
                  <div class="item new">Speaking: tự giới thiệu</div>
                  <div class="item new">Reading: đoạn ngắn</div>
                  <div class="item new">Writing: câu đơn</div>
                </div>
              </div>
            </div>

            <!-- Giai đoạn 2 -->
            <div class="timeline__stage">
              <h2>Giai đoạn 2<br><small>(Phát triển)</small></h2>
              <div class="timeline__category">
                <h3>Grammar</h3>
                <div class="items">
                  <div class="item old">Hiện tại đơn</div>
                  <div class="item old">Hiện tại tiếp diễn</div>
                  <div class="item old">Trực tiếp – gián tiếp</div>
                  <div class="item old">So sánh</div>
                  <div class="item new">Quá khứ đơn</div>
                  <div class="item new">Tương lai đơn</div>
                  <div class="item new">Điều kiện loại 1</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Vocabulary</h3>
                <div class="items">
                  <div class="item old">Gia đình</div>
                  <div class="item new">Sở thích</div>
                  <div class="item new">Ngày tháng & Thời gian</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Skills</h3>
                <div class="items">
                  <div class="item old">Listening: cơ bản</div>
                  <div class="item new">Listening: câu ngắn</div>
                  <div class="item new">Speaking: hỏi đáp</div>
                  <div class="item new">Writing: đoạn ngắn</div>
                </div>
              </div>
            </div>

            <!-- Giai đoạn 3 -->
            <div class="timeline__stage">
              <h2>Giai đoạn 3<br><small>(Nâng cao)</small></h2>
              <div class="timeline__category">
                <h3>Grammar</h3>
                <div class="items">
                  <div class="item old">Quá khứ đơn</div>
                  <div class="item old">Tương lai đơn</div>
                  <div class="item new">Thì hoàn thành</div>
                  <div class="item new">Điều kiện loại 2 & 3</div>
                  <div class="item new">Bị động</div>
                  <div class="item new">Wish</div>
                  <div class="item new">Đại từ & từ nối</div>
                  <div class="item new">Liên từ nhượng bộ</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Vocabulary</h3>
                <div class="items">
                  <div class="item old">Sở thích</div>
                  <div class="item new">Đồ vật xung quanh</div>
                </div>
              </div>
              <div class="timeline__category">
                <h3>Skills</h3>
                <div class="items">
                  <div class="item old">Listening: câu ngắn</div>
                  <div class="item new">Reading: Yes/No</div>
                  <div class="item new">Writing: đoạn nâng cao</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Section 3 - Timeline -->

      <!-- Start Section 4 - Methods -->
      <div class="section__phuongphapdanhgia mt-4 mb-4">
        <div class="card">
          <h1>BẢNG PHƯƠNG PHÁP ĐÁNH GIÁ</h1>
          <div class="row">
            <div class="col-lg-3">
              <!-- Listening -->
              <section class="skill-card" id="listening">
                <h3>Listening (Nghe)</h3>
                <div class="desc">Đánh giá khả năng thu nhận thông tin từ audio / video.</div>

                <div class="level" data-level="1">
                  <h4>Mức 1 – Dễ</h4>
                  <ul>
                    <li>Nghe từ/cụm từ và chọn hình ảnh tương ứng.</li>
                    <li>Nghe – điền 1 từ còn thiếu.</li>
                    <li>Nghe và chọn True/False.</li>
                  </ul>
                </div>


                <div class="level" data-level="2">
                  <h4>Mức 2 – Trung bình</h4>
                  <ul>
                    <li>Nghe đoạn hội thoại ngắn (2–3 câu) và trả lời WH-questions.</li>
                    <li>Nghe – sắp xếp câu theo thứ tự.</li>
                  </ul>
                </div>


                <div class="level" data-level="3">
                  <h4>Mức 3 – Khó</h4>
                  <ul>
                    <li>Nghe hội thoại dài (1–2 phút), nhiều dạng câu hỏi (MCQ, gap-fill).</li>
                    <li>Nghe thông tin chi tiết (số liệu, thời gian).</li>
                  </ul>
                </div>


                <div class="level" data-level="4">
                  <h4>Mức 4 – Nâng cao</h4>
                  <ul>
                    <li>Nghe bài giảng / podcast 5 phút trở lên.</li>
                    <li>Ghi chú ý chính & tóm tắt nội dung bằng lời hoặc viết.</li>
                  </ul>
                </div>
              </section>
            </div>
            <div class="col-lg-3">
              <!-- Speaking -->
              <section class="skill-card" id="speaking">
                <h3>Speaking (Nói)</h3>
                <div class="desc">Đánh giá phản xạ, phát âm, mạch lạc khi nói.</div>


                <div class="level" data-level="1">
                  <h4>Mức 1 – Dễ</h4>
                  <ul>
                    <li>Đọc to từ vựng theo mẫu; trả lời Yes/No; giới thiệu bản thân theo mẫu.</li>
                  </ul>
                </div>


                <div class="level" data-level="2">
                  <h4>Mức 2 – Trung bình</h4>
                  <ul>
                    <li>Trả lời câu hỏi ngắn; nói về chủ đề quen thuộc (gia đình, trường học).</li>
                    <li>Luyện phát âm câu hoàn chỉnh.</li>
                  </ul>
                </div>


                <div class="level" data-level="3">
                  <h4>Mức 3 – Khó</h4>
                  <ul>
                    <li>Thảo luận nhóm; mô tả tranh/biểu đồ; nói liên tục 1–2 phút.</li>
                  </ul>
                </div>

                <div class="level" data-level="4">
                  <h4>Mức 4 – Nâng cao</h4>
                  <ul>
                    <li>Thuyết trình 3–5 phút; tham gia tranh luận; dùng idioms & collocations tự nhiên.</li>
                  </ul>
                </div>
              </section>
            </div>
            <div class="col-lg-3">
              <!-- Reading -->
              <section class="skill-card" id="reading">
                <h3>Reading (Đọc)</h3>
                <div class="desc">Đánh giá hiểu văn bản, tóm tắt và suy luận.</div>


                <div class="level" data-level="1">
                  <h4>Mức 1 – Dễ</h4>
                  <ul>
                    <li>Đọc đoạn ngắn (3–4 câu) và trả lời MCQ; ghép câu với hình ảnh.</li>
                  </ul>
                </div>


                <div class="level" data-level="2">
                  <h4>Mức 2 – Trung bình</h4>
                  <ul>
                    <li>Đọc đoạn 100–150 từ; trả lời comprehension; tìm từ đồng nghĩa/trái nghĩa.</li>
                  </ul>
                </div>


                <div class="level" data-level="3">
                  <h4>Mức 3 – Khó</h4>
                  <ul>
                    <li>Đọc bài báo 250–300 từ; tóm tắt; suy luận ý nghĩa theo ngữ cảnh.</li>
                  </ul>
                </div>


                <div class="level" data-level="4">
                  <h4>Mức 4 – Nâng cao</h4>
                  <ul>
                    <li>Đọc bài học thuật 500+ từ; phân tích quan điểm tác giả; đưa nhận định cá nhân.</li>
                  </ul>
                </div>
              </section>
            </div>
            <div class="col-lg-3">
              <!-- Writing -->
              <section class="skill-card" id="writing">
                <h3>Writing (Viết)</h3>
                <div class="desc">Đánh giá ngữ pháp, mạch lạc, lập luận khi viết.</div>

                <div class="level" data-level="1">
                  <h4>Mức 1 – Dễ</h4>
                  <ul>
                    <li>Viết câu đơn; điền từ; viết 2–3 câu về bản thân.</li>
                  </ul>
                </div>


                <div class="level" data-level="2">
                  <h4>Mức 2 – Trung bình</h4>
                  <ul>
                    <li>Viết đoạn 4–5 câu; email đơn giản; dùng linking words cơ bản.</li>
                  </ul>
                </div>


                <div class="level" data-level="3">
                  <h4>Mức 3 – Khó</h4>
                  <ul>
                    <li>Viết đoạn học thuật 150 từ; viết báo cáo ngắn; dùng câu ghép/phức.</li>
                  </ul>
                </div>


                <div class="level" data-level="4">
                  <h4>Mức 4 – Nâng cao</h4>
                  <ul>
                    <li>Viết essay 250+ từ (IELTS style); luận so sánh; sử dụng dẫn chứng & lập luận.</li>
                  </ul>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
      <!-- End Section 4 - Methods -->

      <!-- Start Skill English -->
      @include("assessments/documents/skills")
      <!-- End Skill English -->


      <!--  -->
    </section>
    <!-- End Section 2 - Question List -->

    <!--  -->
    <div class="card__timer--1">
      <div class="card__timer">
        <!--  -->
        <div id="timer" data-duration="{{ $assessment->duration }}"></div>
        <div id="timer" data-end="{{ $endTime }}" style="display: none;"></div>
        <p id="timer" class="fw-bold text-danger" style="display: none;"></p>
      </div>
    </div>
    <!--  -->
    <div class="wrap">

    </div>
@endsection

  <!-- JS -->
  @push('scripts')

    <!--  -->
    <script>
      document.getElementById("examForm").addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
          event.preventDefault(); // chặn submit
        }
      });
    </script>

    <!--  -->
    <script>
      let duration = {{ $remainingSeconds }}; // còn lại bao nhiêu giây

      const timerEl = document.getElementById('timer');
      const form = document.getElementById('examForm');

      const interval = setInterval(() => {
        let minutes = Math.floor(duration / 60);
        let seconds = duration % 60;

        timerEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        if (duration <= 0) {
          clearInterval(interval);
          // alert("Hết giờ, bài làm sẽ được nộp!");
          // form.submit();         
        }

        duration--;
      }, 1000);
    </script>

    <!--  -->
    <script>
      const inputs = document.querySelectorAll('#examForm input[name^="answers"]');
      const questionItems = document.querySelectorAll('.question-item');

      questionItems.forEach((item, idx) => {
        item.addEventListener('click', () => {
          const isActive = item.classList.contains('active');

          // Xoá highlight cũ cho tất cả
          inputs.forEach(input => input.classList.remove('active-input'));
          questionItems.forEach(q => q.classList.remove('active'));

          if (!isActive) {
            // Nếu trước đó không active → bật highlight
            inputs[idx].classList.add('active-input');
            item.classList.add('active');
            inputs[idx].focus();
          }
          // Nếu trước đó active → không làm gì (toggle off)
        });
      });

      // Cập nhật thanh câu hỏi khi nhập xong
      inputs.forEach((input, idx) => {
        input.addEventListener('input', () => {
          if (input.value.trim() !== '') {
            questionItems[idx].classList.add('completed');
          } else {
            questionItems[idx].classList.remove('completed');
          }
        });
      });             
    </script>

    <script>
      const timer = document.querySelector(".card__timer");
      let isDragging = false, offsetX, offsetY;

      timer.addEventListener("mousedown", (e) => {
        isDragging = true;
        offsetX = e.clientX - timer.getBoundingClientRect().left;
        offsetY = e.clientY - timer.getBoundingClientRect().top;
        timer.style.transition = "none"; // tắt animation khi kéo
      });

      document.addEventListener("mousemove", (e) => {
        if (isDragging) {
          timer.style.left = e.clientX - offsetX + "px";
          timer.style.top = e.clientY - offsetY + "px";
        }
      });

      document.addEventListener("mouseup", () => {
        isDragging = false;
      });
    </script>

    <!--  -->

    <script>
      // Sample data and simple interactions
      const contentEl = document.getElementById('content');
      const wordCountEl = document.getElementById('wordCount');
      const metaLen = document.getElementById('metaLen');
      const estTime = document.getElementById('estTime');

      // keywords to highlight (sample)
      const keywords = ['mountains', 'train', 'small town', 'fresh', 'cool', 'friendly', 'café', 'stars', 'happy'];

      function computeStats() {
        const text = contentEl.textContent.trim();
        const words = text.split(/\s+/).filter(w => w.length);
        const wc = words.length;
        wordCountEl.textContent = wc;
        metaLen.textContent = wc;
        // reading speed estimate: 200 wpm => time in seconds
        const timeSec = Math.max(20, Math.round((wc / 200) * 60));
        estTime.textContent = `${Math.round(timeSec / 60)}m ${timeSec % 60}s`;
      }

      function highlightKeywords() {
        // naive highlight by replacing words (case-insensitive)
        let html = contentEl.textContent;
        // Sort keywords by length desc to avoid partial overlaps
        const keys = [...keywords].sort((a, b) => b.length - a.length);
        keys.forEach(k => {
          const re = new RegExp('\\b' + k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'ig');
          html = html.replace(re, function (m) { return '<span class="highlight">' + m + '</span>'; });
        });
        contentEl.innerHTML = html;
      }

      function removeHighlights() {
        // reset to raw text
        const raw = contentEl.textContent;
        contentEl.textContent = raw;
      }

      // Question checking
      document.getElementById('btnCheck').addEventListener('click', () => {
        let score = 0, total = 3;

        // q1: MCQ correct = B
        const q1 = document.querySelector('input[name="q1"]:checked');
        if (q1 && q1.value === 'B') score++;

        // q2: short answer, accept contains 'happy'
        const q2 = document.getElementById('q2').value.trim().toLowerCase();
        if (q2.includes('happy')) score++;

        // q3: True/False correct = B
        const q3 = document.querySelector('input[name="q3"]:checked');
        if (q3 && q3.value === 'B') score++;

        // show score
        const scoreBox = document.getElementById('scoreBox');
        scoreBox.textContent = `Điểm: ${score}/${total}`;
        scoreBox.style.color = (score === total) ? '#059669' : '#d97706';
      });

      document.getElementById('btnReset').addEventListener('click', () => {
        document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
        document.getElementById('q2').value = '';
        const scoreBox = document.getElementById('scoreBox');
        scoreBox.textContent = '';
      });

      // Highlight toggle
      let highlighted = false;
      document.getElementById('btnToggleHighlight').addEventListener('click', () => {
        highlighted = !highlighted;
        if (highlighted) {
          highlightKeywords();
          document.getElementById('btnToggleHighlight').textContent = 'Bỏ highlight';
        } else {
          removeHighlights();
          document.getElementById('btnToggleHighlight').textContent = 'Highlight keywords';
        }
      });

      // Download as txt
      document.getElementById('btnDownload').addEventListener('click', () => {
        const text = contentEl.textContent.trim();
        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = (document.getElementById('title').textContent || 'reading').replace(/\s+/g, '_') + '.txt';
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
      });

      // init
      computeStats();
      // if want initial highlight: highlightKeywords();
    </script>
    <!--  -->



    <!--  -->
    <script>
      // --- Sample data (mimic DB rows) ---
      const docs = {
        1: {
          id: 1,
          title: "Describe Your Hometown",
          description: "Talk about your hometown in 2 minutes. Include location, weather, and one famous place.",
          level: "TACB 1",
          type: "Monologue",
          topic: "Daily Life",
          time_limit: 120,
          resources: [
            "Hint: Talk about the location, weather, famous places.",
            "Structure: Intro → Details → Example → Conclusion."
          ],
          files: [] // no media file for doc 1
        },
        2: {
          id: 2,
          title: "Shadowing Practice - Airport Conversation",
          description: "Repeat after the audio to practice pronunciation and rhythm.",
          level: "A2",
          type: "Shadowing",
          topic: "Travel",
          time_limit: 180,
          resources: [
            "Shadowing tips: focus on stress and intonation.",
            "Repeat phrase-by-phrase before full run."
          ],
          files: [
            { path: "uploads/speaking/airport_conversation.mp3", type: "audio", duration: 95, primary: true },
            // could include video: { path: 'uploads/speaking/airport_vid.mp4', type:'video', duration:120, primary:false }
          ]
        }
      };

      // --- DOM references ---
      const docSelect = document.getElementById('docSelect');
      const docTitle = document.getElementById('docTitle');
      const docDesc = document.getElementById('docDesc');
      const docLevel = document.getElementById('docLevel');
      const docType = document.getElementById('docType');
      const docTopic = document.getElementById('docTopic');
      const docTime = document.getElementById('docTime');
      const docId = document.getElementById('docId');

      const resourcesList = document.getElementById('resourcesList');
      const mediaArea = document.getElementById('mediaArea');
      const metaPrimary = document.getElementById('metaPrimary');
      const metaId = document.getElementById('metaId');
      const metaTitle = document.getElementById('metaTitle');
      const metaLevel = document.getElementById('metaLevel');
      const metaType = document.getElementById('metaType');
      const metaTopic = document.getElementById('metaTopic');
      const metaTime = document.getElementById('metaTime');

      // recorder refs
      const btnStart = document.getElementById('btnStart');
      const btnStop = document.getElementById('btnStop');
      const btnPlayRec = document.getElementById('btnPlayRec');
      const btnDownloadRec = document.getElementById('btnDownloadRec');
      const btnResetRec = document.getElementById('btnResetRec');
      const recStatus = document.getElementById('recStatus');
      const playbackArea = document.getElementById('playbackArea');
      const playback = document.getElementById('playback');
      const evalArea = document.getElementById('evalArea');
      const evalDuration = document.getElementById('evalDuration');
      const evalWords = document.getElementById('evalWords');
      const evalFluency = document.getElementById('evalFluency');
      const evalPron = document.getElementById('evalPron');
      const btnReEval = document.getElementById('btnReEval');

      let currentDoc = docs[1];
      let mediaBlob = null;
      let mediaUrl = null;

      // MediaRecorder state
      let mediaRecorder = null;
      let recordedChunks = [];
      let recordingStartTs = 0;

      // Initialize view with a doc
      function renderDoc(id) {
        currentDoc = docs[id];
        docTitle.textContent = currentDoc.title;
        docDesc.textContent = currentDoc.description;
        docLevel.textContent = currentDoc.level;
        docType.textContent = currentDoc.type;
        docTopic.textContent = currentDoc.topic;
        docTime.textContent = currentDoc.time_limit + 's';
        docId.textContent = currentDoc.id;

        metaId.textContent = currentDoc.id;
        metaTitle.textContent = currentDoc.title;
        metaLevel.textContent = currentDoc.level;
        metaType.textContent = currentDoc.type;
        metaTopic.textContent = currentDoc.topic;
        metaTime.textContent = currentDoc.time_limit + 's';

        // resources
        resourcesList.innerHTML = '';
        currentDoc.resources.forEach(r => {
          const li = document.createElement('li');
          li.textContent = r;
          resourcesList.appendChild(li);
        });

        // media
        mediaArea.innerHTML = '';
        if (currentDoc.files && currentDoc.files.length) {
          currentDoc.files.forEach(f => {
            if (f.type === 'audio') {
              const wrap = document.createElement('div');
              wrap.innerHTML = `<label class="small">Primary audio:</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                              <audio controls preload="metadata" src="${f.path}"></audio>`;
              mediaArea.appendChild(wrap);
              metaPrimary.textContent = f.path + ' (' + f.duration + 's)';
            } else if (f.type === 'video') {
              const wrap = document.createElement('div');
              wrap.innerHTML = `<label class="small">Primary video:</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                              <video controls preload="metadata" src="${f.path}"></video>`;
              mediaArea.appendChild(wrap);
              metaPrimary.textContent = f.path + ' (' + f.duration + 's)';
            }
          });
        } else {
          mediaArea.innerHTML = '<div class="small" style="color:var(--muted)">Không có file media cho tài liệu này.</div>';
          metaPrimary.textContent = '—';
        }

        // reset recording UI
        resetRecordingUI();
      }

      docSelect.addEventListener('change', (e) => {
        renderDoc(e.target.value);
      });

      // Recorder: start
      btnStart.addEventListener('click', async () => {
        // request microphone
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          mediaRecorder = new MediaRecorder(stream);
          recordedChunks = [];
          mediaRecorder.ondataavailable = (evt) => {
            if (evt.data && evt.data.size > 0) recordedChunks.push(evt.data);
          };
          mediaRecorder.onstop = () => {
            mediaBlob = new Blob(recordedChunks, { type: 'audio/webm' });
            if (mediaUrl) URL.revokeObjectURL(mediaUrl);
            mediaUrl = URL.createObjectURL(mediaBlob);
            playback.src = mediaUrl;
            playbackArea.style.display = 'block';
            btnPlayRec.disabled = false;
            btnDownloadRec.disabled = false;
            btnResetRec.disabled = false;
            evaluateRecording(); // run evaluation after stop as well, but can also do here
          };
          mediaRecorder.start();
          recordingStartTs = Date.now();
          recStatus.textContent = 'Trạng thái: Đang ghi âm...';
          btnStart.disabled = true;
          btnStop.disabled = false;
          btnPlayRec.disabled = true;
          btnDownloadRec.disabled = true;
          btnResetRec.disabled = true;
          evalArea.style.display = 'none';
        } catch (err) {
          console.error(err);
          alert('Không thể truy cập micro. Hãy cho phép quyền micro trong trình duyệt.');
        }
      });

      // Recorder: stop
      btnStop.addEventListener('click', () => {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
          mediaRecorder.stop();
          recStatus.textContent = 'Trạng thái: Ghi âm đã dừng.';
          btnStart.disabled = false;
          btnStop.disabled = true;
        }
      });

      // Play recorded
      btnPlayRec.addEventListener('click', () => {
        if (mediaUrl) {
          playback.play();
        }
      });

      // Download recorded
      btnDownloadRec.addEventListener('click', () => {
        if (!mediaBlob) return;
        const a = document.createElement('a');
        a.href = mediaUrl;
        const fname = `${currentDoc.title.replace(/\s+/g, '_')}_recording.webm`;
        a.download = fname;
        document.body.appendChild(a);
        a.click();
        a.remove();
      });

      // Reset recording
      btnResetRec.addEventListener('click', () => {
        if (mediaUrl) URL.revokeObjectURL(mediaUrl);
        mediaBlob = null;
        mediaUrl = null;
        playback.src = '';
        playbackArea.style.display = 'none';
        btnPlayRec.disabled = true;
        btnDownloadRec.disabled = true;
        btnResetRec.disabled = true;
        recStatus.textContent = 'Trạng thái: Chưa ghi âm';
        evalArea.style.display = 'none';
      });

      function resetRecordingUI() {
        if (mediaUrl) URL.revokeObjectURL(mediaUrl);
        mediaBlob = null;
        mediaUrl = null;
        playback.src = '';
        playbackArea.style.display = 'none';
        btnStart.disabled = false;
        btnStop.disabled = true;
        btnPlayRec.disabled = true;
        btnDownloadRec.disabled = true;
        btnResetRec.disabled = true;
        recStatus.textContent = 'Trạng thái: Chưa ghi âm';
        evalArea.style.display = 'none';
      }

      // Simple evaluation stub
      function evaluateRecording() {
        if (!mediaBlob) return;
        // For demo: estimate duration and word count heuristically
        // Play the blob silently in an AudioContext to get duration is complex; instead use recordedChunks length and timestamps
        const durationMs = Date.now() - recordingStartTs;
        const durationSec = Math.max(1, Math.round(durationMs / 1000));
        evalDuration.textContent = durationSec + 's';

        // Heuristic: transcribe? We can't transcribe offline. We'll approximate word count by file size (very rough).
        // This is only a demo metric — real system should call a speech-to-text engine.
        const sizeKB = Math.round(mediaBlob.size / 1024);
        const approxWords = Math.max(5, Math.round(sizeKB / 2.5)); // arbitrary scale
        evalWords.textContent = approxWords;

        // Fluency & Pronunciation mock scoring (0-100)
        const fluencyScore = Math.min(100, Math.round(Math.max(20, (approxWords / (durationSec || 1)) * 15 + 50)));
        const pronScore = Math.min(100, Math.round(70 + (Math.random() * 15 - 7))); // random-ish near 70

        evalFluency.textContent = fluencyScore + '/100';
        evalPron.textContent = pronScore + '/100';
        evalArea.style.display = 'block';
      }

      btnReEval.addEventListener('click', () => {
        evaluateRecording();
      });

      // Initialize first doc
      renderDoc(1);
    </script>

    <!--  -->

  @endpush