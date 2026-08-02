<!-- Header -->
@include("layouts/header")

@include("layouts/sidebar")

<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper" style="margin-left: 300px;">
  
  <!-- Glassmorphism Dashboard Container -->
  <div class="dashboard-container p-4 p-lg-5" style="background: #f8fafc; min-height: 100vh; font-family: 'Outfit', 'Inter', sans-serif;">
    
    <!-- Welcome Header Banner -->
    <div class="welcome-banner mb-4 p-4 p-lg-5 rounded-4 text-dark position-relative overflow-hidden" 
         style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
      <div class="position-relative z-3">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="fs-6 fw-bold text-primary text-uppercase tracking-wider" style="color: #2563eb !important;">Học Viện Anh Ngữ EnglishHub</span>
        </div>
        <h1 class="display-6 fw-bold mb-3" style="color: #0f172a !important;">Xin chào học viên! 👋</h1>
        <p class="lead text-secondary fs-5 mb-0" style="max-width: 700px; color: #475569 !important;">
          Chào mừng bạn quay trở lại với bảng kế hoạch học tập cá nhân hóa. Hãy theo dõi mục tiêu, kiểm tra lịch trình buổi học và tham gia các kỳ ôn tập, đánh giá định kỳ để bứt phá năng lực tiếng Anh nhé!
        </p>
      </div>
    </div>

    <!-- Quick Stats Hub -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="p-4 rounded-4 bg-white border border-light shadow-sm d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 text-primary d-flex align-items-center justify-content-center" style="background: rgba(14, 165, 233, 0.1); width: 56px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
          </div>
          <div>
            <span class="text-muted small fw-semibold block">Mục tiêu từ vựng</span>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 22px;">1,000 từ</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 rounded-4 bg-white border border-light shadow-sm d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 text-success d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.1); width: 56px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
          </div>
          <div>
            <span class="text-muted small fw-semibold block">Kế hoạch chi tiết</span>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 22px;">16 buổi học</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 rounded-4 bg-white border border-light shadow-sm d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 text-warning d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.1); width: 56px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div>
            <span class="text-muted small fw-semibold block">Phân bổ ôn tập</span>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 22px;">10-30 phút/ngày</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 rounded-4 bg-white border border-light shadow-sm d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 text-danger d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.1); width: 56px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div>
            <span class="text-muted small fw-semibold block">Tiêu chí đạt</span>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 22px;">Retention &ge; 70%</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs (Glassmorphism layout) -->
    <div class="nav-tabs-premium mb-4 d-flex align-items-center gap-2 p-2 rounded-4" 
         style="background: rgba(241, 245, 249, 0.8); backdrop-filter: blur(8px); border: 1px solid rgba(226, 232, 240, 0.8); overflow-x: auto;">
      <button class="tab-btn-premium active" onclick="openTab('part1')">
        📊 Phần 1: Mục Tiêu & Lộ Trình
      </button>
      <button class="tab-btn-premium" onclick="openTab('part2')">
        📖 Phần 2: Nhật Ký Buổi Học
      </button>
      <button class="tab-btn-premium" onclick="openTab('part3')">
        📝 Phần 3: Buổi Đánh Giá
      </button>
      <button class="tab-btn-premium" onclick="openTab('part4')">
        🔄 Phần 4: Buổi Ôn Tập
      </button>
      <button class="tab-btn-premium" onclick="openTab('part5')">
        ⚡ Phần 5: Kì Thi Thử
      </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: PHẦN 1 - MỤC TIÊU & LỘ TRÌNH -->
    <!-- ========================================== -->
    <div id="part1" class="section active tab-content-fade">
      <div class="row g-4">
        <!-- Chương 1: Chi tiết mục tiêu -->
        <div class="col-12">
          <div class="card-premium">
            <div class="card-premium-title">
              <span class="fs-4">🎯</span> Chương 1: Mục Tiêu Học Tập & Phân Phối Kiến Thức
            </div>
            
            <div class="row g-4">
              <!-- Card 1: Khối lượng kiến thức -->
              <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100 border border-light">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-5">📚</span>
                    <h5 class="fw-bold mb-0 text-dark">Khối lượng kiến thức</h5>
                  </div>
                  <ul class="list-unstyled d-flex flex-column gap-2.5 small text-secondary">
                    <li>📌 Chủ đề chính: <strong class="text-dark">Từ vựng Tiếng Anh</strong></li>
                    <li>📊 Tổng số từ mục tiêu: <strong class="text-dark">1,000 từ</strong></li>
                    <li>🔥 Số từ/ngày cần đạt: <strong class="text-dark">20 từ</strong></li>
                    <li>🌐 Phạm vi ứng dụng: <span class="badge bg-secondary text-white">IELTS Academic</span> <span class="badge bg-secondary text-white">Daily</span> <span class="badge bg-secondary text-white">IT</span></li>
                    <li>⚡ Phân chia độ khó: <strong class="text-dark">Cơ bản / Trung cấp / Nâng cao</strong></li>
                  </ul>
                </div>
              </div>

              <!-- Card 2: Phân bổ thời gian -->
              <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100 border border-light">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-5">⏱️</span>
                    <h5 class="fw-bold mb-0 text-dark">Thời gian mục tiêu</h5>
                  </div>
                  <ul class="list-unstyled d-flex flex-column gap-2.5 small text-secondary">
                    <li>📅 Tổng thời gian kỳ vọng: <strong class="text-dark">3 tháng</strong></li>
                    <li>🟢 Ngày bắt đầu: <strong class="text-dark">2025-09-15</strong></li>
                    <li>🔴 Ngày hoàn thành dự kiến: <strong class="text-dark">2025-12-15</strong></li>
                    <li>🔄 Phân bổ hằng ngày: <strong class="text-dark">20 từ/ngày, ôn tập 10-30 phút</strong></li>
                    <li>🏆 Mốc quan trọng: <strong class="text-dark">30d &rarr; 600 từ, 60d &rarr; 800 từ, 90d &rarr; 1000 từ</strong></li>
                  </ul>
                </div>
              </div>

              <!-- Card 3: Phương pháp & Tiêu chí đạt -->
              <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100 border border-light">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-5">💡</span>
                    <h5 class="fw-bold mb-0 text-dark">Phương pháp & Đánh giá</h5>
                  </div>
                  <ul class="list-unstyled d-flex flex-column gap-2.5 small text-secondary">
                    <li>🧠 Kỹ thuật cốt lõi: <strong class="text-dark">Flashcard (SRS) & Luyện nghe audio</strong></li>
                    <li>📝 Tiêu chí ngắn hạn: <strong class="text-dark">Hoàn thành &ge; 80% chỉ tiêu hằng tuần</strong></li>
                    <li>📈 Tiêu chí dài hạn: <strong class="text-dark">Tỉ lệ nhớ dài hạn (Retention) &ge; 70%</strong></li>
                    <li>🏃‍♀️ Thói quen duy trì: <strong class="text-dark">Duy trì streak liên tục &ge; 20 ngày</strong></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chương 2: Hậu cần -->
        <div class="col-12">
          <div class="card-premium">
            <div class="card-premium-title">
              <span class="fs-4">📦</span> Chương 2: Bảng Đối Chiếu Hậu Cần Ôn Tập
            </div>
            
            <div class="row g-4">
              <div class="col-lg-6">
                <h5 class="fw-bold text-primary mb-3">🗳️ Hậu cần dự kiến</h5>
                <div class="table-responsive rounded-3 border">
                  <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>Trình độ</th>
                        <th>Số lần ôn</th>
                        <th>Vở viết</th>
                        <th>Bút sử dụng</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><span class="badge bg-success-subtle text-success">Cơ bản</span></td>
                        <td>3 lần (2000 từ/lần)</td>
                        <td>Vở 120 trang (1 quyển)</td>
                        <td>10 ngòi đen</td>
                      </tr>
                      <tr>
                        <td><span class="badge bg-primary-subtle text-primary">Trung cấp</span></td>
                        <td>2 lần</td>
                        <td>Vở 120 trang (1 quyển)</td>
                        <td>10 ngòi xanh</td>
                      </tr>
                      <tr>
                        <td><span class="badge bg-danger-subtle text-danger">Nâng cao</span></td>
                        <td>1 lần</td>
                        <td>Vở 120 trang (1 quyển)</td>
                        <td>10 ngòi đỏ</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-lg-6">
                <h5 class="fw-bold text-success mb-3">📊 Hậu cần thực tế</h5>
                <div class="table-responsive rounded-3 border">
                  <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>Trình độ</th>
                        <th>Số lần ôn</th>
                        <th>Vở viết</th>
                        <th>Bút sử dụng</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><span class="badge bg-success-subtle text-success">Cơ bản</span></td>
                        <td><span class="text-muted">Chưa ghi nhận</span></td>
                        <td><span class="text-muted">-</span></td>
                        <td><span class="text-muted">-</span></td>
                      </tr>
                      <tr>
                        <td><span class="badge bg-primary-subtle text-primary">Trung cấp</span></td>
                        <td><span class="text-muted">Chưa ghi nhận</span></td>
                        <td><span class="text-muted">-</span></td>
                        <td><span class="text-muted">-</span></td>
                      </tr>
                      <tr>
                        <td><span class="badge bg-danger-subtle text-danger">Nâng cao</span></td>
                        <td><span class="text-muted">Chưa ghi nhận</span></td>
                        <td><span class="text-muted">-</span></td>
                        <td><span class="text-muted">-</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chương 3: Hiệu suất & Biểu đồ -->
        <div class="col-12">
          <div class="card-premium">
            <div class="card-premium-title">
              <span class="fs-4">📈</span> Chương 3: Phân Tích Hiệu Suất Học Tập Thực Tế
            </div>
            
            <div class="row g-4 mb-4">
              <!-- Bảng 1: Hiệu suất -->
              <div class="col-lg-4">
                <div class="p-4 rounded-4 bg-light h-100 border">
                  <h6 class="fw-bold mb-3">Hiệu suất / Phương pháp học</h6>
                  <div class="table-responsive rounded-3">
                    <table class="table table-sm table-bordered bg-white mb-0">
                      <thead class="table-dark">
                        <tr>
                          <th>Phương pháp</th>
                          <th>Tốc độ (từ/phút)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Từ vựng + Nghĩa</td>
                          <td><strong class="text-success">&approx; 1.11</strong></td>
                        </tr>
                        <tr>
                          <td>Từ vựng + Loại + Nghĩa</td>
                          <td><strong class="text-primary">&approx; 0.56</strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <p class="small text-muted mt-3 mb-0">
                    * Phương pháp học kết hợp loại từ và ví dụ thường yêu cầu nhiều thời gian suy ngẫm hơn nhưng mang lại độ lưu trữ trí nhớ vượt trội.
                  </p>
                </div>
              </div>

              <!-- Bảng 2: Kì vọng -->
              <div class="col-lg-4">
                <div class="p-4 rounded-4 bg-light h-100 border">
                  <h6 class="fw-bold mb-3">Đánh giá hiệu suất dự kiến</h6>
                  <div class="table-responsive rounded-3">
                    <table class="table table-sm table-bordered bg-white mb-0">
                      <thead class="table-dark">
                        <tr>
                          <th>Trình độ</th>
                          <th>Tốc độ kỳ vọng</th>
                          <th>Khối lượng/buổi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Cơ bản</td>
                          <td>&ge; 1 từ/phút</td>
                          <td>100 từ/buổi</td>
                        </tr>
                        <tr>
                          <td>Trung cấp</td>
                          <td>&ge; 0.5 từ/phút</td>
                          <td>50 từ/buổi</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Bảng 3: Thực tế đã qua -->
              <div class="col-lg-4">
                <div class="p-4 rounded-4 bg-light h-100 border">
                  <h6 class="fw-bold mb-3">Đánh giá hiệu suất thực tế</h6>
                  <div class="table-responsive rounded-3">
                    <table class="table table-sm table-bordered bg-white mb-0">
                      <thead class="table-dark">
                        <tr>
                          <th>Ngày học</th>
                          <th>Đã đạt</th>
                          <th>So sánh</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>2025-09-15</td>
                          <td>95 từ / 90p</td>
                          <td><span class="badge bg-success">Đạt</span></td>
                        </tr>
                        <tr>
                          <td>2025-09-16</td>
                          <td>45 từ / 90p</td>
                          <td><span class="badge bg-warning text-dark">Tạm đạt</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Charts Container -->
            <div class="row g-4">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-4 border">
                  <h6 class="fw-bold mb-2 text-center text-secondary">📊 Phân tích số từ đạt được qua các buổi</h6>
                  <div style="position: relative; height: 260px;">
                    <canvas id="sessionChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-4 border">
                  <h6 class="fw-bold mb-2 text-center text-secondary">📈 Xu hướng hiệu suất trung bình theo tuần</h6>
                  <div style="position: relative; height: 260px;">
                    <canvas id="weekChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chương 4: Kế hoạch & Chi phí -->
        <div class="col-12">
          <div class="card-premium">
            <div class="card-premium-title">
              <span class="fs-4">💸</span> Chương 4: Tổng Hợp Kế Hoạch Chi Tiết & Chi Phí
            </div>
            
            <div class="row g-4">
              <div class="col-lg-4">
                <div class="p-4 rounded-4 bg-light h-100 border">
                  <h5 class="fw-bold text-dark mb-3">Tổng hợp lộ trình dự kiến</h5>
                  <ul class="list-unstyled d-flex flex-column gap-3 small text-secondary mb-0">
                    <li class="d-flex justify-content-between border-bottom pb-2">
                      <span>Tổng số từ cần học:</span>
                      <strong class="text-dark">800 từ</strong>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                      <span>Số từ phân bổ mỗi buổi:</span>
                      <strong class="text-dark">100 từ</strong>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                      <span>Tổng số buổi học:</span>
                      <strong class="text-dark">8 buổi học + 8 ôn tập = 16 buổi</strong>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                      <span>Thời gian phân bổ:</span>
                      <strong class="text-dark">8 tuần (2 buổi/tuần)</strong>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                      <span>Chi phí giả định/buổi:</span>
                      <strong class="text-dark">200.000 VND</strong>
                    </li>
                    <li class="d-flex justify-content-between pt-1">
                      <span class="fw-bold text-primary">TỔNG CHI PHÍ DỰ KIẾN:</span>
                      <strong class="text-primary font-monospace" style="font-size: 16px;">3.200.000 VND</strong>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="col-lg-8">
                <h5 class="fw-bold text-dark mb-3">Lịch trình chi tiết từng buổi</h5>
                <div class="scroll-container" style="max-height: 270px; overflow-y: auto; padding-right: 8px;">
                  <div class="row g-3">
                    <!-- 8 buổi học mới -->
                    @for($i = 1; $i <= 8; $i++)
                      <div class="col-md-6 col-xl-4">
                        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm" style="border-left: 4px solid #0ea5e9 !important;">
                          <h6 class="fw-bold text-primary mb-1">Buổi {{ $i }}</h6>
                          <div class="small text-muted mb-1">Loại: <span class="badge bg-primary-subtle text-primary">Học mới</span></div>
                          <div class="small">Khối lượng: <strong>100 từ</strong></div>
                          <div class="small">Thời lượng: <strong>90 phút</strong></div>
                          <div class="small text-secondary">Chi phí: 200k VND</div>
                        </div>
                      </div>
                    @endfor
                    <!-- 8 buổi ôn tập -->
                    @for($i = 9; $i <= 16; $i++)
                      <div class="col-md-6 col-xl-4">
                        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                          <h6 class="fw-bold text-success mb-1">Buổi {{ $i }}</h6>
                          <div class="small text-muted mb-1">Loại: <span class="badge bg-success-subtle text-success">Ôn tập</span></div>
                          <div class="small">Nội dung: <strong>Ôn 100 từ buổi {{ $i-8 }}</strong></div>
                          <div class="small">Thời lượng: <strong>90 phút</strong></div>
                          <div class="small text-secondary">Chi phí: 200k VND</div>
                        </div>
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2.5 mt-4 pt-3 border-top">
              <button class="btn btn-primary px-4 fw-bold shadow-sm" style="border-radius: 8px;">💾 Lưu Cấu Hình</button>
              <button class="btn btn-outline-secondary px-4 fw-bold text-dark" style="border-radius: 8px;">📄 Xuất Bản Kế Hoạch (PDF)</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: PHẦN 2 - NHẬT KÝ BUỔI HỌC -->
    <!-- ========================================== -->
    <div id="part2" class="section tab-content-fade">
      <div class="row g-4">
        <!-- Cột trái: Cấu trúc mẫu -->
        <div class="col-lg-5">
          <div class="card-premium h-100">
            <div class="card-premium-title">
              <span class="fs-4">📋</span> Cấu Trúc Khung Mẫu Một Buổi Học Chuẩn
            </div>
            
            <!-- Timeline vertical step by step -->
            <div class="timeline-vertical mt-4">
              <div class="timeline-item-vertical">
                <div class="timeline-dot-vertical"></div>
                <h6 class="fw-bold text-dark mb-1">📝 01. Ôn tập trước buổi học</h6>
                <p class="small text-muted mb-0">Thời lượng: 15-20 phút. Xem lại từ vựng hoặc điểm lý thuyết ngữ pháp đã học ở buổi trước.</p>
              </div>

              <div class="timeline-item-vertical">
                <div class="timeline-dot-vertical" style="background: #e67e22;"></div>
                <h6 class="fw-bold text-dark mb-1">🔥 02. Ca học thứ nhất (Ca 1)</h6>
                <p class="small text-muted mb-0">Thời gian: 20:00 – 21:00. Học tập trung 50 từ vựng mới hoặc các cấu trúc cơ bản.</p>
              </div>

              <div class="timeline-item-vertical">
                <div class="timeline-dot-vertical" style="background: #95a5a6;"></div>
                <h6 class="fw-bold text-dark mb-1">☕ 03. Ca giải lao ngắn</h6>
                <p class="small text-muted mb-0">Thời gian: 21:00 – 21:15. Nghỉ ngơi hoàn toàn, uống nước để não bộ tái tạo khả năng tập trung.</p>
              </div>

              <div class="timeline-item-vertical">
                <div class="timeline-dot-vertical" style="background: #e67e22;"></div>
                <h6 class="fw-bold text-dark mb-1">⚡ 04. Ca học thứ hai (Ca 2)</h6>
                <p class="small text-muted mb-0">Thời gian: 21:15 – 23:00. Bài tập ngữ pháp, luyện nghe và ghi chép ví dụ ứng dụng.</p>
              </div>

              <div class="timeline-item-vertical">
                <div class="timeline-dot-vertical" style="background: #2ecc71;"></div>
                <h6 class="fw-bold text-dark mb-1">🎓 05. Thảo luận &amp; Tổng kết</h6>
                <p class="small text-muted mb-0">Thời gian: 23:00 – 23:30. Viết tóm tắt buổi học, lưu vết các từ vựng sai hoặc khó nhớ.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Cột phải: Nhật ký thực tế -->
        <div class="col-lg-7">
          <div class="card-premium h-100">
            <div class="card-premium-title">
              <span class="fs-4">📜</span> Nhật Ký Học Tập Thực Tế Của Bạn
            </div>
            
            <div class="scroll-container mt-3" style="max-height: 480px; overflow-y: auto; padding-right: 8px;">
              <div class="d-flex flex-column gap-3">
                
                <div class="p-3.5 bg-light rounded-4 border position-relative">
                  <div class="position-absolute top-0 end-0 m-3 small text-muted">01/10</div>
                  <h6 class="fw-bold text-dark mb-2">🌟 Buổi 1 — Nhập môn &amp; Thì Hiện Tại Đơn</h6>
                  <div class="row g-2 small text-secondary mb-2">
                    <div class="col-md-6">⏱️ Lịch trình: <strong>20:00 – 23:30</strong></div>
                    <div class="col-md-6">📖 Nội dung: <strong>50 từ (Gia đình) + Ngữ pháp</strong></div>
                  </div>
                  <p class="small mb-1 text-dark"><strong>• Ca 1:</strong> Ôn tập từ vựng qua flashcard.</p>
                  <p class="small mb-1 text-dark"><strong>• Ca 2:</strong> Học lý thuyết và làm bài tập Thì hiện tại đơn.</p>
                  <div class="small text-success fw-semibold mt-2">&check; Tổng kết: Đã nắm vững 50 từ và cách chia động từ ngôi thứ ba.</div>
                </div>

                @for($b = 2; $b <= 6; $b++)
                  <div class="p-3.5 bg-light rounded-4 border position-relative">
                    <div class="position-absolute top-0 end-0 m-3 small text-muted">04/10</div>
                    <h6 class="fw-bold text-dark mb-2">🌟 Buổi {{ $b }} — Từ Vựng Nghề Nghiệp &amp; Thì Quá Khứ</h6>
                    <div class="row g-2 small text-secondary mb-2">
                      <div class="col-md-6">⏱️ Lịch trình: <strong>20:00 – 23:30</strong></div>
                      <div class="col-md-6">📖 Nội dung: <strong>50 từ (Công việc) + Luyện nghe</strong></div>
                    </div>
                    <p class="small mb-1 text-dark"><strong>• Ca 1:</strong> Thực hành viết nghĩa và loại từ.</p>
                    <p class="small mb-1 text-dark"><strong>• Ca 2:</strong> Nghe bài đọc hội thoại và trả lời câu hỏi.</p>
                    <div class="small text-success fw-semibold mt-2">&check; Tổng kết: Đã ôn tập hoàn thành, vượt điểm chuẩn kiểm tra.</div>
                  </div>
                @endfor

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: PHẦN 3 - BUỔI ĐÁNH GIÁ -->
    <!-- ========================================== -->
    <div id="part3" class="section tab-content-fade">
      
      <!-- Hàng 1: Admin Create (nếu có) -->
      <div class="mb-4">
        @include("assessments/create")
      </div>

      <div class="row g-4">
        <!-- Cột 1: Mẫu & Danh sách -->
        <div class="col-lg-4">
          <div class="d-flex flex-column gap-4 h-100">
            <!-- Mẫu buổi đánh giá -->
            <div class="card-premium">
              <div class="card-premium-title">
                <span class="fs-4">📝</span> Mẫu Buổi Đánh Giá Định Kỳ
              </div>
              <ul class="list-unstyled d-flex flex-column gap-3 small text-secondary mb-0">
                <li>🟢 <strong>Thời gian:</strong> 20:00 – 22:00 (120 phút)</li>
                <li>📘 <strong>Nội dung:</strong> Từ vựng (Gia đình) – Trắc nghiệm + Viết</li>
                <li>🎯 <strong>Chi tiết:</strong> 50 từ vựng và 5 cấu trúc ngữ pháp trọng tâm</li>
                <li>⚡ <strong>Lịch trình:</strong> 45p trắc nghiệm, 15p nghỉ, 60p viết tự luận</li>
                <li>🏆 <strong>Kết thúc:</strong> Giáo viên chấm điểm và trả nhận xét năng lực</li>
              </ul>
            </div>

            <!-- Danh sách buổi đánh giá -->
            <div class="card-premium">
              <div class="card-premium-title">
                <span class="fs-4">📜</span> Danh Sách Buổi Đánh Giá
              </div>
              <div class="scroll-container" style="max-height: 250px; overflow-y: auto; padding-right: 8px;">
                <div class="d-flex flex-column gap-2">
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Đánh Giá 1</h6>
                    <div class="small text-muted">Thời gian: 20:00 - 22:00</div>
                    <div class="small">Chủ đề: <strong>Từ vựng Gia đình (Trắc nghiệm)</strong></div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Đánh Giá 2</h6>
                    <div class="small text-muted">Thời gian: 20:00 - 22:00</div>
                    <div class="small">Chủ đề: <strong>Từ vựng Công việc (Viết tự luận)</strong></div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Đánh Giá 3</h6>
                    <div class="small text-muted">Thời gian: 19:30 - 21:30</div>
                    <div class="small">Chủ đề: <strong>Ngữ pháp Thì Hiện Tại Đơn</strong></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cột 2: Chi tiết buổi đánh giá -->
        <div class="col-lg-8">
          <div class="card-premium h-100">
            <div class="card-premium-title d-flex justify-content-between align-items-center">
              <div><span class="fs-4">🔍</span> Chi Tiết Buổi Đánh Giá Cận Cảnh</div>
              <span class="badge bg-primary px-3 py-2 rounded-pill">Địa điểm: Hà Nội</span>
            </div>
            
            <div class="p-3.5 bg-light rounded-4 border mb-4">
              <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h5 class="fw-bold text-dark mb-0">Đánh Giá Tuần (Từ vựng 500-1000)</h5>
                <span class="small text-secondary">⏱️ Thứ Tư, Ngày 06/08/2025</span>
              </div>
              
              <div class="row g-3 small">
                <div class="col-md-6 border-end">
                  <h6 class="fw-bold text-primary mb-2">(1) Phân bổ thời gian</h6>
                  <p class="mb-1">• Giờ bắt đầu: <strong>23H30 PM</strong></p>
                  <p class="mb-1">• Giờ kết thúc: <strong>24H30 PM</strong></p>
                  <p class="mb-0">• Tổng thời lượng: <strong class="text-dark">60 phút</strong></p>
                </div>
                <div class="col-md-6">
                  <h6 class="fw-bold text-success mb-2">(2) Nội dung &amp; Phương pháp</h6>
                  <p class="mb-1">• Nội dung: <strong>Từ vựng mã 500 - 1000</strong></p>
                  <p class="mb-1">• Phương pháp: <strong>Ghi nghĩa tiếng Việt của từ tương ứng</strong></p>
                  <p class="mb-0">• Tốc độ kỳ vọng: <strong class="text-dark">500 từ / 60 phút</strong></p>
                </div>
              </div>
            </div>

            <!-- Phân tích trực quan bằng biểu đồ -->
            <h6 class="fw-bold text-dark mb-3">📊 Biểu đồ Phân Tích Kỹ Năng Đánh Giá Định Kỳ</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">1. Phân bổ thời gian ôn (Gantt)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="ganttChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">2. Khối lượng từ đạt (Cột)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="barChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">3. Tiến trình điểm số (Đường)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="lineChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">4. Phân bổ kỹ năng (Radar)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="radarChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: PHẦN 4 - BUỔI ÔN TẬP -->
    <!-- ========================================== -->
    <div id="part4" class="section tab-content-fade">
      <div class="row g-4">
        <!-- Cột 1: Mẫu & Danh sách -->
        <div class="col-lg-4">
          <div class="d-flex flex-column gap-4 h-100">
            <!-- Mẫu buổi ôn tập -->
            <div class="card-premium">
              <div class="card-premium-title">
                <span class="fs-4">🔄</span> Mẫu Buổi Ôn Tập Củng Cố
              </div>
              <ul class="list-unstyled d-flex flex-column gap-3 small text-secondary mb-0">
                <li>🟢 <strong>Thời gian:</strong> 21:00 – 22:00 (60 phút)</li>
                <li>📘 <strong>Nội dung:</strong> Ôn tập Từ vựng + Hệ thống ngữ pháp</li>
                <li>🎯 <strong>Chi tiết:</strong> 200 từ vựng và 10 cấu trúc câu</li>
                <li>⚡ <strong>Lịch trình:</strong> 45p ôn từ vựng SRS, 15p luyện bài tập</li>
                <li>🏆 <strong>Kết thúc:</strong> Đánh giá mức độ thuộc nghĩa và ghi chú lỗi</li>
              </ul>
            </div>

            <!-- Danh sách buổi ôn tập -->
            <div class="card-premium">
              <div class="card-premium-title">
                <span class="fs-4">📜</span> Danh Sách Lịch Sử Ôn Tập
              </div>
              <div class="scroll-container" style="max-height: 250px; overflow-y: auto; padding-right: 8px;">
                <div class="d-flex flex-column gap-2">
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Ôn Tập 1</h6>
                    <div class="small text-muted">Từ vựng 500-700 (Viết nghĩa)</div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Ôn Tập 2</h6>
                    <div class="small text-muted">Cấu trúc câu (Điền từ + Viết)</div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Ôn Tập 3</h6>
                    <div class="small text-muted">Tổng hợp Ngữ pháp + Từ vựng</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cột 2: Chi tiết buổi ôn tập -->
        <div class="col-lg-8">
          <div class="card-premium h-100">
            <div class="card-premium-title d-flex justify-content-between align-items-center">
              <div><span class="fs-4">🔍</span> Chi Tiết Nội Dung Ôn Tập Tuần</div>
              <span class="badge bg-success px-3 py-2 rounded-pill">Trạng thái: Hoàn thành</span>
            </div>
            
            <div class="p-3.5 bg-light rounded-4 border mb-4">
              <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h5 class="fw-bold text-dark mb-0">Buổi Ôn Tập Củng Cố Tuần 1</h5>
                <span class="small text-secondary">⏱️ Thứ Tư, Ngày 06/08/2025</span>
              </div>
              
              <div class="row g-3 small">
                <div class="col-md-6 border-end">
                  <h6 class="fw-bold text-primary mb-2">(1) Phân bổ thời gian</h6>
                  <p class="mb-1">• Giờ bắt đầu: <strong>23H30 PM</strong></p>
                  <p class="mb-1">• Giờ kết thúc: <strong>24H30 PM</strong></p>
                  <p class="mb-0">• Tổng thời lượng: <strong class="text-dark">60 phút</strong></p>
                </div>
                <div class="col-md-6">
                  <h6 class="fw-bold text-success mb-2">(2) Nội dung &amp; Mục tiêu</h6>
                  <p class="mb-1">• Nội dung: <strong>Ôn từ vựng mã 500 - 1000</strong></p>
                  <p class="mb-1">• Phương pháp: <strong>Viết nghĩa và kiểm tra tỉ lệ SRS</strong></p>
                  <p class="mb-0">• Lịch trình: <strong>50p ôn từ, 10p làm bài kiểm tra nhanh</strong></p>
                </div>
              </div>
            </div>

            <!-- Biểu đồ ôn tập -->
            <h6 class="fw-bold text-dark mb-3">📊 Phân Tích Chỉ Số Buổi Ôn Tập Của Học Viên</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">1. Tốc độ &amp; Nhóm từ ôn (Cột)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="reviewBarChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">2. Xu hướng tiến độ (%) (Đường)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="reviewLineChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">3. Tỉ lệ kết quả đúng/sai (Tròn)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="reviewPieChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">4. Phân bổ các kỹ năng (Radar)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="reviewRadarChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 5: PHẦN 5 - KÌ THI THỬ -->
    <!-- ========================================== -->
    <div id="part5" class="section tab-content-fade">
      <div class="row g-4">
        <!-- Cột trái: Mẫu vé thi & Danh sách kì thi -->
        <div class="col-lg-5">
          <div class="d-flex flex-column gap-4 h-100">
            <!-- Vé thi thử (Exam ticket design) -->
            <div class="card-premium text-white" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-white text-primary fw-bold">🎫 EXAM TICKET</span>
                <span class="small opacity-75">Hà Nội — 10/08/2025</span>
              </div>
              <h5 class="fw-bold mb-3">KÌ THI THỬ NĂNG LỰC TIẾNG ANH ĐỊNH KỲ</h5>
              <div class="d-flex flex-column gap-2 small mb-4">
                <div class="d-flex justify-content-between border-bottom border-white-subtle pb-1">
                  <span>Thời lượng thi:</span>
                  <strong>120 phút (08:00 - 10:00)</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom border-white-subtle pb-1">
                  <span>Nội dung thi:</span>
                  <strong>Tổng hợp A1 - A2</strong>
                </div>
                <div class="d-flex justify-content-between pb-1">
                  <span>Cấu trúc đề:</span>
                  <strong>40 Vocab + 20 Grammar + 10 Listen + 1 Writing</strong>
                </div>
              </div>
              <div class="p-3.5 rounded-3 bg-white-subtle text-white" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                <div class="small fw-semibold">&check; Lịch trình chi tiết phòng thi:</div>
                <div class="small mt-1.5 opacity-90">• 08:00 – 08:40: Trắc nghiệm Từ vựng</div>
                <div class="small opacity-90">• 08:40 – 09:10: Trắc nghiệm Ngữ pháp</div>
                <div class="small opacity-90">• 09:10 – 09:30: Phần thi nghe (Audio)</div>
                <div class="small opacity-90">• 09:30 – 10:00: Bài viết luận ngắn</div>
              </div>
            </div>

            <!-- Danh sách kì thi thử -->
            <div class="card-premium">
              <div class="card-premium-title">
                <span class="fs-4">📜</span> Kế Hoạch Kì Thi Thử Đã Qua
              </div>
              <div class="scroll-container" style="max-height: 220px; overflow-y: auto; padding-right: 8px;">
                <div class="d-flex flex-column gap-2">
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Thi 1 (10/08/2025)</h6>
                    <div class="small">Từ vựng &amp; Ngữ pháp A1 - A2</div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Thi 2 (20/08/2025)</h6>
                    <div class="small">Kỹ năng Nghe &amp; Viết luận</div>
                  </div>
                  <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold mb-1">Buổi Thi 3 (01/09/2025)</h6>
                    <div class="small">Đề thi tổng hợp cuối lộ trình</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cột phải: Biểu đồ & Chỉ số chi tiết -->
        <div class="col-lg-7">
          <div class="card-premium h-100">
            <div class="card-premium-title">
              <span class="fs-4">📊</span> Phân Tích &amp; Tổng Kết Điểm Kì Thi Thử
            </div>
            
            <div class="p-3.5 bg-light rounded-4 border mb-4">
              <h6 class="fw-bold text-dark mb-3">🎯 Phân bổ tỉ lệ các kỹ năng trong đề thi</h6>
              <div class="row g-3">
                <div class="col-6 col-md-3 text-center border-end">
                  <div class="text-primary fw-bold fs-5">40 câu</div>
                  <span class="small text-muted">Từ vựng (40%)</span>
                </div>
                <div class="col-6 col-md-3 text-center border-end">
                  <div class="text-success fw-bold fs-5">20 câu</div>
                  <span class="small text-muted">Ngữ pháp (20%)</span>
                </div>
                <div class="col-6 col-md-3 text-center border-end">
                  <div class="text-warning fw-bold fs-5">10 câu</div>
                  <span class="small text-muted">Nghe hiểu (10%)</span>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <div class="text-danger fw-bold fs-5">1 bài</div>
                  <span class="small text-muted">Viết luận (30%)</span>
                </div>
              </div>
            </div>

            <!-- Charts kì thi -->
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">1. Phân bổ câu hỏi (Tròn)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="examPieChart"></canvas></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">2. Điểm các kĩ năng (%) (Radar)</h6>
                  <div style="position: relative; height: 160px;"><canvas id="examRadarChart"></canvas></div>
                </div>
              </div>
              <div class="col-12">
                <div class="p-3 bg-light rounded-3 border">
                  <h6 class="small fw-bold text-center mb-2">3. Điểm thi qua 4 đợt khảo sát gần nhất (Cột)</h6>
                  <div style="position: relative; height: 180px;"><canvas id="allExamsChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Interactive CSS for transitions, tabs & glassmorphism -->
    <style>
      .tab-content-fade {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .tab-content-fade.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
      }
      
      .nav-tabs-premium::-webkit-scrollbar {
        height: 0px;
      }
      
      .tab-btn-premium {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14.5px;
        color: #475569;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
      }
      
      .tab-btn-premium:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
      }
      
      .tab-btn-premium.active {
        color: #ffffff;
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.25);
      }
      
      .card-premium {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.03);
        padding: 24px;
        transition: all 0.3s ease;
        height: 100%;
        text-align: left;
      }
      
      .card-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border-color: rgba(14, 165, 233, 0.2);
      }
      
      .card-premium-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-align: left;
      }
      
      /* Vertical timeline */
      .timeline-vertical {
        position: relative;
        padding-left: 28px;
        border-left: 2px solid #f1f5f9;
        text-align: left;
      }
      
      .timeline-item-vertical {
        position: relative;
        padding-bottom: 28px;
      }
      
      .timeline-item-vertical:last-child {
        padding-bottom: 0;
      }
      
      .timeline-dot-vertical {
        position: absolute;
        left: -36px;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #0ea5e9;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
      }
      
      /* Custom scrollbar */
      .scroll-container::-webkit-scrollbar {
        width: 6px;
      }
      .scroll-container::-webkit-scrollbar-track {
        background: transparent;
      }
      .scroll-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
      }
      .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
    </style>

    <!-- Tab switcher JS -->
    <script>
      function openTab(tabId) {
        // Hide all sections with transition fade out
        const allSections = document.querySelectorAll('.section');
        allSections.forEach(sec => {
          sec.classList.remove('active');
        });
        
        // Remove active class from buttons
        const allButtons = document.querySelectorAll('.tab-btn-premium');
        allButtons.forEach(btn => {
          btn.classList.remove('active');
        });

        // Get target elements
        const targetSection = document.getElementById(tabId);
        const clickedButton = Array.from(allButtons).find(btn => btn.getAttribute('onclick').includes(tabId));

        if (targetSection && clickedButton) {
          // Show active section
          targetSection.classList.add('active');
          clickedButton.classList.add('active');
          
          // Save selected tab in localStorage
          localStorage.setItem('activeWelcomeTab', tabId);
        }
      }

      // Restore active tab on load
      document.addEventListener('DOMContentLoaded', () => {
        const savedTab = localStorage.getItem('activeWelcomeTab');
        if (savedTab && document.getElementById(savedTab)) {
          openTab(savedTab);
        }
      });
    </script>
    
  </div>
  
</main>
<!-- ======== main-wrapper end =========== -->

<!-- Footer -->
@include("layouts/footer")