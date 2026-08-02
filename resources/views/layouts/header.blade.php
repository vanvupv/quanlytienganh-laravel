<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Ứng dụng Quản lý Tiếng Anh — Mục tiêu Từ vựng (Full)</title>
  <!--  -->
  <style>
    :root {
      --bg: #f5f7fb;
      --card: #ffffff;
      --muted: #6b7280;
      --accent: #0ea5e9;
      --accent-2: #2563eb
    }

    * {
      box-sizing: border-box
    }

    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px
    }

    h1 {
      font-size: 20px;
      margin: 0
    }

    p.lead {
      color: var(--muted);
      margin: 6px 0 0
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 320px;
      gap: 16px;
      align-items: start;
      margin-bottom: 18px
    }

    .card {
      background: var(--card);
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
      border: 1px solid rgba(2, 6, 23, 0.05)
    }

    .card h3 {
      margin: 0 0 8px;
      font-size: 16px
    }

    .meta {
      color: var(--muted);
      font-size: 13px;
      margin-bottom: 10px
    }

    .list {
      padding-left: 16px;
      margin: 8px 0
    }

    .list li {
      margin-bottom: 6px
    }

    .small {
      font-size: 13px;
      color: var(--muted)
    }

    .info-col {
      background: #f0f4fa;
      padding: 12px;
      border-radius: 12px;
      font-size: 13px;
      color: #374151
    }

    .info-col h4 {
      margin: 0 0 8px;
      font-size: 14px;
      color: #111827
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      background: white;
      border-radius: 8px;
      overflow: hidden
    }

    table th,
    table td {
      padding: 10px;
      border: 1px solid #e6eef8;
      text-align: left;
      font-size: 14px
    }

    table th {
      background: #fafcff;
      color: #111827
    }

    .actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      margin-top: 20px
    }

    .btn {
      background: var(--accent-2);
      color: white;
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      cursor: pointer
    }

    .btn.ghost {
      background: white;
      border: 1px solid #e6eef8;
      color: var(--accent-2)
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 12px;
      margin-top: 12px
    }

    .session-card {
      background: #fff;
      border-radius: 10px;
      padding: 12px;
      border: 1px solid #eef3ff
    }

    canvas {
      margin-top: 12px;
      max-width: 100%;
      background: white;
      padding: 10px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04)
    }

    footer {
      margin-top: 28px;
      color: var(--muted);
      font-size: 13px
    }

    @media (max-width:880px) {
      .grid {
        grid-template-columns: 1fr
      }
    }
  </style>

  <!--  -->
  <style>
    .sidebar {
      width: 220px;
      background: #2c3e50;
      color: #ecf0f1;
      padding: 20px 0;
    }

    .tab {
      padding: 12px 20px;
      cursor: pointer;
    }

    .tab:hover,
    .tab.active {
      background: #34495e;
    }

    .content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .section {
      display: none;
    }

    .section.active {
      display: block;
    }
  </style>

  <!-- Buổi học Style -->
  <style>
    .d-flex {
      display: flex;
    }

    .column {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .left {
      background: #f7f7f7;
      border-right: 1px solid #ddd;
    }

    .right {
      background: #fff;
    }

    .card {
      background: #fff;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
      margin-top: 0;
      font-size: 18px;
      color: #2c3e50;
    }

    .sub-section {
      margin-left: 15px;
      margin-bottom: 8px;
    }

    .highlight {
      font-weight: bold;
      color: #e67e22;
    }

    .time {
      color: #16a085;
    }

    /*  */
    h2 {
      margin-top: 0;
      font-size: 18px;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
    }

    .toc {
      list-style: none;
      padding-left: 0;
    }

    .toc li {
      margin: 10px 0;
      padding: 6px 10px;
      border-left: 3px solid #007BFF;
      background: #f0f8ff;
      font-weight: bold;
    }

    .toc li span {
      font-weight: normal;
      display: block;
      font-size: 14px;
      margin-top: 4px;
      color: #444;
    }
  </style>

  <!--  -->
  <style>
    .list-group-item {
      background: #fff;
      border-radius: 10px;
      padding: 18px;
      margin-bottom: 15px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .question {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 10px;
      color: #2c3e50;
    }


    .result {
      margin-top: 12px;
      font-weight: 600;
    }


    strong {
      color: #34495e;
    }

    .text-success {
      color: #27ae60;
    }

    .text-danger {
      color: #e74c3c;
    }
  </style>

  <!--  -->
  <style>
    /* Container chính */
    #examForm {
      max-width: 700px;
      margin: 30px auto;
      padding: 25px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      font-family: Arial, sans-serif;
    }

    /* Thông tin tiêu đề */
    #examForm h1,
    #examForm h2,
    #examForm h3 {
      text-align: center;
      margin-bottom: 15px;
    }

    /* Câu hỏi */
    #examForm .form-label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #333;
    }

    /* Ô input */
    #examForm input[type="text"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      transition: border-color 0.2s;
    }

    #examForm input[type="text"]:focus {
      border-color: #0d6efd;
      outline: none;
      box-shadow: 0 0 4px rgba(13, 110, 253, 0.3);
    }

    /* Câu hỏi từng block */
    #examForm .mb-4 {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 8px;
      background: #f9f9f9;
    }

    /* Nút nộp bài */
    #examForm button {
      display: block;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: background 0.2s;
    }

    #examForm button.btn-primary {
      background: #0d6efd;
      color: white;
    }

    #examForm button.btn-primary:hover {
      background: #0b5ed7;
    }

    /* Bộ đếm thời gian */
    #timer {
      font-size: 18px;
      font-weight: bold;
      color: #d63384;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>

  <!--  -->
  <style>
    /* Section 1 */
    .stat-card {
      color: #fff;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card .stat-icon {
      font-size: 2rem;
    }

    .stat-card .stat-number {
      font-size: 2rem;
      font-weight: bold;
      margin: 0;
    }

    .stat-card .stat-label {
      font-size: 1rem;
      margin: 0;
      opacity: 0.9;
    }

    .stat-card.upcoming {
      background: linear-gradient(135deg, #f9a825, #ffca28);
    }

    .stat-card.ongoing {
      background: linear-gradient(135deg, #0288d1, #26c6da);
    }

    .stat-card.finished {
      background: linear-gradient(135deg, #2e7d32, #66bb6a);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }


    .stats {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .stat-card {
      flex: 1;
      padding: 20px;
      border-radius: 8px;
      color: #fff;
      text-align: center;
    }

    .stat-card h3 {
      margin-bottom: 10px;
    }

    .stat-card.upcoming {
      background-color: #f0ad4e;
    }

    .stat-card.ongoing {
      background-color: #5bc0de;
    }

    .stat-card.finished {
      background-color: #5cb85c;
    }

    .table-assessments {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .table-assessments th,
    .table-assessments td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    .table-assessments th {
      background-color: #f7f7f7;
    }

    .status.upcoming {
      color: #f0ad4e;
      font-weight: bold;
    }

    .status.ongoing {
      color: #5bc0de;
      font-weight: bold;
    }

    .status.finished {
      color: #5cb85c;
      font-weight: bold;
    }

    .btn-small {
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      margin: 0 2px;
      font-size: 14px;
      background-color: #007bff;
      color: #fff;
    }

    .btn-small.edit {
      background-color: #ffc107;
    }

    .btn-small.delete {
      background-color: #dc3545;
    }
  </style>

  <!-- Style -->
  <style>
    /* Thanh câu hỏi */
    #examForm {
      max-width: none !important;
      margin: 30px auto;
      padding: 25px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      /* font-family: Arial, sans-serif; */
    }

    /*  */
    .question-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      /* khoảng cách giữa các ô */
    }

    .question-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 12px;
      border: 2px solid #ddd;
      /* viền ngoài */
      border-radius: 12px;
      /* bo góc */
      background-color: #f9f9f9;
      /* nền nhạt */
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      /* đổ bóng nhẹ */
    }

    .question-item {
      width: 40px;
      height: 40px;
      line-height: 40px;
      border: 2px solid #007bff;
      /* viền xanh */
      border-radius: 8px;
      /* bo góc */
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
      background-color: #fff;
      color: #007bff;
    }

    .question-item:hover {
      background-color: #007bff;
      color: #fff;
    }

    .question-item.active {
      background-color: #28a745;
      /* xanh lá khi chọn */
      border-color: #28a745;
      color: #fff;
    }

    .question-item.answered {
      background-color: #ffc107;
      /* vàng nếu đã trả lời */
      border-color: #ffc107;
      color: #fff;
    }

    /*  */
    .question-bar .question-item {
      width: 35px;
      height: 35px;
      line-height: 35px;
      border: 1px solid #ccc;
      cursor: pointer;
      background-color: #fff;
      /* chưa làm */
      transition: all 0.3s;
    }

    .question-bar .question-item.completed {
      background-color: #28a745;
      color: #fff;
      border-color: #28a745;
    }

    .question-bar .question-item.active {
      border-color: #dc3545;
      /* đỏ nhạt */
      background-color: #f8d7da;
      color: #dc3545;
    }

    /* Input khi được focus bằng click từ thanh câu hỏi */
    #examForm input.active-input {
      border-color: #dc3545;
      background-color: #f8d7da;
      transition: all 0.3s;
    }

    #timer {
      display: inline-block;
      font-size: 2rem;
      font-weight: bold;
      font-family: 'Courier New', monospace;
      background: #222;
      color: #00ff88;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      text-align: center;
      letter-spacing: 2px;
      min-width: 120px;
    }
  </style>

  <!--  -->
  <!-- CSS -->
  <style>
    .question-item {
      width: 36px;
      height: 36px;
      line-height: 36px;
      cursor: pointer;
      font-weight: bold;
    }

    .question-correct {
      background-color: #d4edda;
      /* xanh nhạt */
      border: 1px solid #28a745;
      color: #155724;
    }

    .question-wrong {
      background-color: #f8d7da;
      /* đỏ nhạt */
      border: 1px solid #dc3545;
      color: #721c24;
    }

    .question-blank {
      background-color: #f0f0f0;
      border: 1px solid #6c757d;
      color: #6c757d;
    }

    /* Thanh câu hỏi */
    .question-item {
      width: 45px;
      height: 45px;
      line-height: 45px;
      cursor: pointer;
      user-select: none;
      border: 2px solid #ccc;
      transition: all 0.2s ease-in-out;
    }

    .question-item:hover {
      background-color: #ffe0e0;
    }

    .question-item.active {
      background-color: #ff4d4d;
      /* đỏ mạnh */
      color: white;
      border-color: #cc0000;
      font-weight: bold;
    }

    .question-item.answered {
      background-color: #28a745;
      /* xanh khi đã làm */
      color: white;
      border-color: #1e7e34;
    }

    /* Câu hỏi trong list-group */
    .list-group-item.active-question {
      background-color: #ffe6e6 !important;
      /* nền đỏ nhạt */
      border-left: 5px solid #ff4d4d;
    }
  </style>

  <!--  -->


  <!-- Biểu đồ -->
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap JS & Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Slick CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Slick JS -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <!-- ========== All CSS files linkup ========= -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />

  <!--  -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Clean Theme 2020s Override Style -->
  <link rel="stylesheet" href="{{ asset('assets/css/clean-theme.css') }}" />

  <!--  -->

</head>