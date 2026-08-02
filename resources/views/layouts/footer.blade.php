<footer>

  <!-- ========= All Javascript files linkup ======== -->
  <!-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> -->
  <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/js/dynamic-pie-chart.js') }}"></script>
  <script src="{{ asset('assets/js/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
  <script src="{{ asset('assets/js/jvectormap.min.js') }}"></script>
  <script src="{{ asset('assets/js/world-merc.js') }}"></script>
  <script src="{{ asset('assets/js/polyfill.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Tab JS -->
  <script>
    //   function openTab(tabId) {
    //     // Ẩn tất cả
    //     document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    //     document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    //     // Hiện tab được chọn
    //     document.getElementById(tabId).classList.add('active');
    //     event.target.classList.add('active');
    //   }
    // </script>

  <script>
    (function() {
      // Sample data for charts
      const sessionLabels = ['Buổi 1', 'Buổi 2', 'Buổi 3', 'Buổi 4', 'Buổi 5', 'Buổi 6', 'Buổi 7', 'Buổi 8'];
      const sessionData = [95, 45, 110, 80, 100, 105, 98, 92];

      const ctx1 = document.getElementById('sessionChart');
      if (ctx1) {
        new Chart(ctx1, {
          type: 'bar',
          data: { labels: sessionLabels, datasets: [{ label: 'Số từ đạt được', data: sessionData, backgroundColor: 'rgba(37,99,235,0.6)' }] },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
      }

      // Weekly average example
      const weekCtx = document.getElementById('weekChart');
      if (weekCtx) {
        new Chart(weekCtx, {
          type: 'line',
          data: { labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'], datasets: [{ label: 'Hiệu suất TB (từ/phút)', data: [1.0, 0.8, 1.2, 0.9], borderColor: 'rgba(37,99,235,0.8)', fill: false, tension: 0.3 }] },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
      }
    })();
  </script>

  <!-- Phần 1. Biểu đồ Đánh giá -->
  <script>
    (function() {
      // 1. Gantt (Timeline) – dùng Horizontal Bar
      const ganttEl = document.getElementById('ganttChart');
      if (ganttEl) {
        new Chart(ganttEl, {
          type: 'bar',
          data: {
            labels: ['Buổi 1', 'Buổi 2', 'Buổi 3'],
            datasets: [{
              label: 'Thời lượng (phút)',
              data: [60, 65, 70],
              backgroundColor: ['#10b981', '#0ea5e9', '#f59e0b']
            }]
          },
          options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } },
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // 2. Bar Chart – số lượng từ
      const barEl = document.getElementById('barChart');
      if (barEl) {
        new Chart(barEl, {
          type: 'bar',
          data: {
            labels: ['Buổi 1', 'Buổi 2', 'Buổi 3'],
            datasets: [{
              label: 'Số từ vựng',
              data: [500, 600, 700],
              backgroundColor: ['#6366f1', '#14b8a6', '#f43f5e']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
          }
        });
      }

      // 3. Line Chart – điểm số
      const lineEl = document.getElementById('lineChart');
      if (lineEl) {
        new Chart(lineEl, {
          type: 'line',
          data: {
            labels: ['Buổi 1', 'Buổi 2', 'Buổi 3'],
            datasets: [{
              label: 'Điểm số (%)',
              data: [70, 80, 85],
              borderColor: '#f97316',
              backgroundColor: '#f97316',
              fill: false,
              tension: 0.3
            }]
          },
          options: { 
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // 4. Pie Chart – phân bổ thời gian
      const pieEl = document.getElementById('pieChart');
      if (pieEl) {
        new Chart(pieEl, {
          type: 'pie',
          data: {
            labels: ['Buổi 1 (60p)', 'Buổi 2 (65p)', 'Buổi 3 (70p)'],
            datasets: [{
              data: [60, 65, 70],
              backgroundColor: ['#4f46e5', '#10b981', '#f59e0b']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // 5. Radar Chart – giả định so sánh kỹ năng
      const radarEl = document.getElementById('radarChart');
      if (radarEl) {
        new Chart(radarEl, {
          type: 'radar',
          data: {
            labels: ['Từ vựng', 'Ngữ pháp', 'Nghe', 'Viết', 'Đọc'],
            datasets: [
              {
                label: 'Buổi 1',
                data: [70, 60, 50, 55, 65],
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)'
              },
              {
                label: 'Buổi 2',
                data: [80, 70, 60, 65, 75],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)'
              },
              {
                label: 'Buổi 3',
                data: [85, 75, 65, 70, 80],
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)'
              }
            ]
          },
          options: { 
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }
    })();
  </script>

  <!-- Phần 2. Biểu đồ Ôn tập -->
  <script>
    (function() {
      // === 1. Biểu đồ Gantt (Timeline mô phỏng) ===
      const reviewGanttEl = document.getElementById('reviewGanttChart');
      if (reviewGanttEl) {
        const ctxReviewGantt = reviewGanttEl.getContext('2d');
        new Chart(ctxReviewGantt, {
          type: 'bar',
          data: {
            labels: ['Ca 1', 'Ôn tập sai', 'Xem lại'],
            datasets: [{
              label: 'Thời lượng (phút)',
              data: [50, 10, 5],
              backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9']
            }]
          },
          options: {
            indexAxis: 'y',
            plugins: { title: { display: true, text: 'Timeline Buổi Ôn Tập' } },
            scales: { x: { stacked: true }, y: { stacked: true } },
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // === 2. Biểu đồ Cột ===
      const reviewBarEl = document.getElementById('reviewBarChart');
      if (reviewBarEl) {
        const ctxReviewBar = reviewBarEl.getContext('2d');
        new Chart(ctxReviewBar, {
          type: 'bar',
          data: {
            labels: ['Từ 500-600', '600-700', '700-800', '800-900', '900-1000'],
            datasets: [{
              label: 'Số từ đã ôn',
              data: [80, 95, 70, 60, 50],
              backgroundColor: '#3b82f6'
            }]
          },
          options: {
            plugins: { title: { display: true, text: 'Số từ đã ôn theo nhóm' } },
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // === 3. Biểu đồ Đường ===
      const reviewLineEl = document.getElementById('reviewLineChart');
      if (reviewLineEl) {
        const ctxReviewLine = reviewLineEl.getContext('2d');
        new Chart(ctxReviewLine, {
          type: 'line',
          data: {
            labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
            datasets: [{
              label: 'Hiệu suất (%)',
              data: [60, 72, 78, 85],
              borderColor: '#ec4899',
              backgroundColor: 'rgba(236,72,153,0.1)',
              fill: true,
              tension: 0.3
            }]
          },
          options: {
            plugins: { title: { display: true, text: 'Hiệu suất ôn tập theo tuần' } },
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // === 4. Biểu đồ Tròn ===
      const reviewPieEl = document.getElementById('reviewPieChart');
      if (reviewPieEl) {
        const ctxReviewPie = reviewPieEl.getContext('2d');
        new Chart(ctxReviewPie, {
          type: 'pie',
          data: {
            labels: ['Đúng', 'Sai', 'Bỏ qua'],
            datasets: [{
              data: [350, 100, 50],
              backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
            }]
          },
          options: {
            plugins: { title: { display: true, text: 'Tỉ lệ kết quả ôn tập' } },
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // === 5. Biểu đồ Radar ===
      const reviewRadarEl = document.getElementById('reviewRadarChart');
      if (reviewRadarEl) {
        const ctxReviewRadar = reviewRadarEl.getContext('2d');
        new Chart(ctxReviewRadar, {
          type: 'radar',
          data: {
            labels: ['Từ vựng', 'Ngữ pháp', 'Nghe', 'Nói', 'Đọc', 'Viết'],
            datasets: [{
              label: 'Mức độ hoàn thành (%)',
              data: [80, 70, 50, 60, 75, 65],
              borderColor: '#8b5cf6',
              backgroundColor: 'rgba(139,92,246,0.1)'
            }]
          },
          options: {
            plugins: { title: { display: true, text: 'Kỹ năng được ôn tập' } },
            responsive: true,
            maintainAspectRatio: false,
            scales: { r: { suggestedMin: 0, suggestedMax: 100 } }
          }
        });
      }
    })();
  </script>

  <!-- Phần 3. Biểu đồ Thi thử -->
  <script>
    (function() {
      // Biểu đồ tròn: phân bố câu hỏi
      const examPieEl = document.getElementById('examPieChart');
      if (examPieEl) {
        new Chart(examPieEl, {
          type: 'pie',
          data: {
            labels: ['Từ vựng', 'Ngữ pháp', 'Nghe', 'Viết'],
            datasets: [{
              data: [40, 20, 10, 1],
              backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // Biểu đồ radar: kỹ năng
      const examRadarEl = document.getElementById('examRadarChart');
      if (examRadarEl) {
        new Chart(examRadarEl, {
          type: 'radar',
          data: {
            labels: ['Listening', 'Reading', 'Vocabulary', 'Grammar', 'Writing'],
            datasets: [{
              label: 'Điểm kỹ năng (%)',
              data: [70, 65, 80, 75, 60],
              backgroundColor: 'rgba(244,63,94,0.1)',
              borderColor: 'rgba(244,63,94,1)'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }

      // Biểu đồ cột: so sánh tất cả các buổi thi
      const allExamsEl = document.getElementById('allExamsChart');
      if (allExamsEl) {
        new Chart(allExamsEl, {
          type: 'bar',
          data: {
            labels: ['Thi 1', 'Thi 2', 'Thi 3', 'Thi 4'],
            datasets: [
              { label: 'Listening', data: [70, 75, 80, 85], backgroundColor: '#10b981' },
              { label: 'Reading', data: [65, 70, 72, 78], backgroundColor: '#3b82f6' },
              { label: 'Vocabulary', data: [80, 82, 85, 88], backgroundColor: '#f59e0b' },
              { label: 'Grammar', data: [75, 78, 80, 82], backgroundColor: '#8b5cf6' },
              { label: 'Writing', data: [60, 65, 70, 75], backgroundColor: '#ef4444' }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, max: 100 } }
          }
        });
      }
    })();
  </script>

  {{-- cuối body --}}
  @stack('scripts')
  @stack('script')

  <!-- Slide - Slick -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <script>
    $(document).ready(function () {
      $('.finished-slider').slick({
        slidesToShow: 3,   // 3 cột
        slidesToScroll: 3,
        rows: 2,           // 2 dòng
        arrows: true,
        dots: true,
        infinite: false,
        responsive: [
          {
            breakpoint: 768, // mobile
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              rows: 1
            }
          }
        ]
      });
    });
  </script>


</footer>

</html>