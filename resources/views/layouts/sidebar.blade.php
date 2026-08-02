@php
  // Check if current path contains '-admin' or starts with 'admin'
  $isAdmin = request()->is('*-admin*') || request()->is('admin*');
@endphp

@if($isAdmin)
<!-- ======== sidebar-nav start (Admin Only) =========== -->
<aside class="sidebar-nav-wrapper" style="width: 300px; background: #ffffff; box-shadow: 2px 0 12px rgba(15, 23, 42, 0.04); border-right: 1px solid #e2e8f0; transition: all 0.3s ease;">
  <!-- Logo Section -->
  <div class="navbar-logo d-flex align-items-center justify-content-between px-4 py-4" style="border-bottom: 1px solid #e2e8f0;">
    <a href="/" class="text-decoration-none d-flex align-items-center gap-2">
      <span class="fs-4 fw-bold text-dark tracking-tight" style="letter-spacing: -0.5px;">🇬🇧 English<span class="text-primary" style="color: #2563eb !important;">Hub</span></span>
    </a>
  </div>

  <!-- Navigation Menu -->
  <nav class="sidebar-nav py-4 px-3">
    <ul class="nav-list d-flex flex-column gap-2" style="list-style: none; padding-left: 0; margin-bottom: 0;">
      
      <!-- Trang Chủ / Dashboard -->
      <li class="nav-item">
        <a href="/" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none text-gray-400 hover-nav-link" style="transition: all 0.2s; color: #475569;">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </span>
          <span class="text fw-semibold" style="font-size: 15px;">Quay lại Giao diện Học viên</span>
        </a>
      </li>

      <span class="text-uppercase tracking-wider fw-bold text-muted px-3 mt-3 mb-1 d-block" style="font-size: 11px; letter-spacing: 1px; color: #475569 !important;">QUẢN TRỊ HỆ THỐNG</span>

      <!-- Quản Lý Từ Vựng -->
      <li class="nav-item">
        <a href="{{ route('vocabulary.admin.index') }}" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none hover-nav-link {{ request()->is('vocabulary-admin*') ? 'active' : '' }}" style="transition: all 0.2s; color: #0f172a; {{ request()->is('vocabulary-admin*') ? 'background: #f1f5f9; font-weight: bold;' : '' }}">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">🌳</span>
          <span class="text fw-semibold" style="font-size: 15px;">Quản Lý Từ Vựng</span>
        </a>
      </li>

      <!-- Quản Lý Ngữ Pháp -->
      <li class="nav-item">
        <a href="{{ route('grammar.admin.index') }}" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none hover-nav-link {{ request()->is('grammar-admin*') ? 'active' : '' }}" style="transition: all 0.2s; color: #0f172a; {{ request()->is('grammar-admin*') ? 'background: #f1f5f9; font-weight: bold;' : '' }}">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">⚙️</span>
          <span class="text fw-semibold" style="font-size: 15px;">Quản Lý Ngữ Pháp</span>
        </a>
      </li>

      <!-- Quản Lý Đề Thi -->
      <li class="nav-item">
        <a href="{{ route('assessments.admin.index') }}" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none hover-nav-link {{ request()->is('assessments-admin*') ? 'active' : '' }}" style="transition: all 0.2s; color: #0f172a; {{ request()->is('assessments-admin*') ? 'background: #f1f5f9; font-weight: bold;' : '' }}">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">📝</span>
          <span class="text fw-semibold" style="font-size: 15px;">Quản Lý Đề Thi</span>
        </a>
      </li>

      <!-- Quản Lý Huy Hiệu -->
      <li class="nav-item">
        <a href="{{ route('achievements.admin.index') }}" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none hover-nav-link {{ request()->is('achievements-admin*') ? 'active' : '' }}" style="transition: all 0.2s; color: #0f172a; {{ request()->is('achievements-admin*') ? 'background: #f1f5f9; font-weight: bold;' : '' }}">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">🏆</span>
          <span class="text fw-semibold" style="font-size: 15px;">Quản Lý Huy Hiệu</span>
        </a>
      </li>

      <!-- Ghép File Nghe -->
      <li class="nav-item">
        <a href="{{ route('listening.merger.index') }}" class="nav-link py-3 px-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none hover-nav-link {{ request()->is('listening-merger*') ? 'active' : '' }}" style="transition: all 0.2s; color: #0f172a; {{ request()->is('listening-merger*') ? 'background: #f1f5f9; font-weight: bold;' : '' }}">
          <span class="icon d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">🧩</span>
          <span class="text fw-semibold" style="font-size: 15px;">Ghép File Nghe</span>
        </a>
      </li>

    </ul>
  </nav>
</aside>
<div class="overlay"></div>
@else
<!-- ======== New Top Navbar for Students =========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3 user-navbar" style="box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03); border-color: #e2e8f0 !important; z-index: 1000;">
  <div class="container-fluid px-4 px-lg-5">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark fs-4" href="/" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
      <span>🇬🇧 English<span class="text-primary" style="color: #2563eb !important;">Hub</span></span>
    </a>
    
    <!-- Mobile Toggler -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Collapsible Menu -->
    <div class="collapse navbar-collapse" id="userNavbar">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 gap-lg-3" style="font-family: 'Outfit', sans-serif;">
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('/') ? 'text-primary bg-light' : 'text-secondary' }}" href="/" style="transition: all 0.2s;">
            🏠 Trang Chủ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('vocabulary*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('vocabulary.index') }}" style="transition: all 0.2s;">
            🌳 Từ Vựng
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('grammar*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('grammar.index') }}" style="transition: all 0.2s;">
            ⏱️ Ngữ Pháp
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('listening*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('listening.index') }}" style="transition: all 0.2s;">
            🎧 Luyện Nghe
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('document*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('document.index') }}" style="transition: all 0.2s;">
            📚 Tài Liệu
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('reading-analyzer*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('reading.analyzer.index') }}" style="transition: all 0.2s;">
            🔎 Lọc File
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3 py-2 rounded-2 {{ request()->is('assessments*') ? 'text-primary bg-light' : 'text-secondary' }}" href="{{ route('assessments.index') }}" style="transition: all 0.2s;">
            📝 Đề Thi
          </a>
        </li>
      </ul>
      
      <!-- Student Profile / Auth status -->
      <div class="d-flex align-items-center" style="font-family: 'Outfit', sans-serif;">
        @if(auth()->check())
          @php
            $sidebarUser = auth()->user();
          @endphp
          <div class="dropdown" id="customUserDropdownWrapper">
            <a class="d-flex align-items-center gap-2.5 text-decoration-none dropdown-toggle text-dark p-1.5 rounded-3 hover-bg-light" href="#" role="button" id="userDropdown" aria-expanded="false" style="transition: all 0.2s;">
              <div class="position-relative">
                @if($sidebarUser->avatar)
                  <img src="{{ asset($sidebarUser->avatar) }}" alt="Avatar" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                @else
                  <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-primary" style="width: 38px; height: 38px; font-size: 14px; background-color: #2563eb !important;">
                    {{ strtoupper(substr($sidebarUser->name, 0, 1)) }}
                  </div>
                @endif
                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px; border-width: 1.5px !important;"></span>
              </div>
              <div class="d-none d-sm-block text-start" style="max-width: 150px;">
                <div class="fw-bold text-dark small text-truncate" style="line-height: 1.2;">{{ $sidebarUser->name }}</div>
                <span class="text-muted" style="font-size: 10px; font-weight: 500;">CEFR Level: {{ $sidebarUser->english_level }}</span>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-light py-2" aria-labelledby="userDropdown" id="customUserDropdownMenu" style="border-radius: 10px; min-width: 210px; margin-top: 10px; right: 0; left: auto; top: 100%; position: absolute;">
              <li class="px-3 py-2 border-bottom mb-1" style="border-color: #f1f5f9 !important;">
                <div class="fw-bold text-dark small">{{ $sidebarUser->name }}</div>
                <div class="text-muted" style="font-size: 11px;">{{ $sidebarUser->email }}</div>
              </li>
              <li>
                <a class="dropdown-item py-2 d-flex align-items-center gap-2 small text-secondary" href="{{ route('profile.index') }}">
                  <span>👤</span> Hồ sơ cá nhân
                </a>
              </li>
              <li><hr class="dropdown-divider" style="background-color: #f1f5f9;"></li>
              
              @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Shortcut to admin managers -->
                <li class="dropdown-header text-uppercase text-muted py-1" style="font-size: 9px; font-weight: 800; letter-spacing: 0.5px;">⚙️ Quản Trị (Admin)</li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('vocabulary.admin.index') }}">
                    <span>🌳</span> Quản lý Từ vựng
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('grammar.admin.index') }}">
                    <span>⚙️</span> Quản lý Ngữ pháp
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('listening.admin.index') }}">
                    <span>🎧</span> Quản lý Luyện nghe
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('document.admin.index') }}">
                    <span>📚</span> Quản lý Tài liệu
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('assessments.admin.index') }}">
                    <span>📝</span> Quản lý Đề thi
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('achievements.admin.index') }}">
                    <span>🏆</span> Quản lý Huy hiệu
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 small text-secondary" href="{{ route('listening.merger.index') }}">
                    <span>🧩</span> Ghép file nghe
                  </a>
                </li>
                <li><hr class="dropdown-divider" style="background-color: #f1f5f9;"></li>
              @endif
              
              <li><hr class="dropdown-divider" style="background-color: #f1f5f9;"></li>
              <li>
                <a class="dropdown-item py-2 d-flex align-items-center gap-2 small text-danger fw-bold" href="{{ route('logout') }}">
                  <span>🚪</span> Đăng xuất
                </a>
              </li>
            </ul>
          </div>
        @else
          <div class="d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-secondary px-3.5 py-2 small fw-bold text-dark" style="border-radius: 8px; font-size: 13.5px; border-color: #cbd5e1 !important;">Đăng Nhập</a>
            <a href="{{ route('register') }}" class="btn btn-primary px-3.5 py-2 small fw-bold" style="border-radius: 8px; font-size: 13.5px;">Đăng Ký</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</nav>

<!-- JavaScript to reset main-wrapper styles on student views & manage custom dropdown -->
<script>
  (function() {
    function resetLayout() {
      const mainWrapper = document.querySelector('.main-wrapper');
      if (mainWrapper) {
        mainWrapper.style.setProperty('margin-left', '0px', 'important');
        mainWrapper.style.setProperty('width', '100%', 'important');
        mainWrapper.style.setProperty('max-width', '100%', 'important');
        mainWrapper.style.setProperty('padding', '0px', 'important');
        mainWrapper.style.transition = 'none';
      }
    }
    // Run immediately to prevent layout shift
    resetLayout();
    // Run again on DOM ready to make sure
    document.addEventListener("DOMContentLoaded", resetLayout);
    window.addEventListener("load", resetLayout);
  })();

  // Custom User Dropdown Toggle Handler
  (function() {
    const trigger = document.getElementById('userDropdown');
    const menu = document.getElementById('customUserDropdownMenu');
    const wrapper = document.getElementById('customUserDropdownWrapper');
    
    if (trigger && menu) {
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isShown = menu.classList.contains('show');
        if (isShown) {
          menu.classList.remove('show');
          trigger.setAttribute('aria-expanded', 'false');
        } else {
          menu.classList.add('show');
          trigger.setAttribute('aria-expanded', 'true');
        }
      });
      
      document.addEventListener('click', function(e) {
        if (wrapper && !wrapper.contains(e.target)) {
          menu.classList.remove('show');
          trigger.setAttribute('aria-expanded', 'false');
        }
      });
    }
  })();
</script>

<style>
  .hover-bg-light:hover {
    background-color: #f8fafc;
  }
  .user-navbar .nav-link {
    color: #475569 !important;
  }
  .user-navbar .nav-link:hover {
    color: #2563eb !important;
    background-color: #f1f5f9 !important;
  }
  .user-navbar .nav-link.active {
    color: #2563eb !important;
    background-color: #f1f5f9 !important;
  }
  .dropdown-item:hover {
    background-color: #f8fafc !important;
    color: #2563eb !important;
  }
</style>
@endif