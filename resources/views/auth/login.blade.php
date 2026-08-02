<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập — EnglishHub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1e293b;
            overflow-x: hidden;
            position: relative;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            z-index: 1;
            position: relative;
            transition: all 0.3s ease;
        }

        .auth-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08);
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 24px;
            text-align: center;
            color: #0f172a;
        }

        .brand-logo span {
            color: #2563eb;
        }

        .form-control {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            color: #0f172a;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 500;
            color: #475569;
            margin-bottom: 8px;
        }

        .btn-submit {
            background: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .switch-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .switch-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            transition: all 0.2s;
        }

        .back-link:hover {
            color: #475569;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Brand Logo -->
        <div class="brand-logo">
            🇬🇧 English<span>Hub</span>
        </div>

        <h3 class="fw-bold mb-1 text-center" style="font-size: 22px;">Chào mừng trở lại!</h3>
        <p class="text-muted small text-center mb-4">Đăng nhập tài khoản để đồng bộ lộ trình học tập cá nhân</p>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success border-0 small mb-4 text-start" style="border-radius: 8px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 small mb-4 text-start" style="border-radius: 8px; background: #fee2e2; border: 1px solid #fecaca; color: #991b1b;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 small mb-4 text-start" style="border-radius: 8px; background: #fee2e2; border: 1px solid #fecaca; color: #991b1b;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="text-start">
            @csrf

            <div class="mb-3">
                <label class="form-label">Địa chỉ Email:</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0">Mật khẩu:</label>
                </div>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="background-color: rgba(15, 23, 42, 0.6); border-color: rgba(255, 255, 255, 0.2);">
                    <label class="form-check-label text-muted" for="remember">
                        Duy trì đăng nhập
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 mb-3">🔓 Đăng Nhập</button>

            <div class="text-center small mt-4 text-muted">
                Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="switch-link">Đăng ký ngay</a>
            </div>
        </form>

        <div class="text-center">
            <a href="/" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại Bảng điều khiển (Guest Mode)
            </a>
        </div>
    </div>

</body>
</html>
