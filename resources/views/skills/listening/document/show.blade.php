@extends('skills.listening.index')

@section('title', 'Tài liệu — ' . $document->name)

@section('content_listening')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@php
  // 1. Thu thập toàn bộ bài nghe thuộc tài liệu này
  $allListenings = collect();
  foreach ($document->parts as $part) {
      $allListenings = $allListenings->merge($part->listenings);
      foreach ($part->units as $unit) {
          $allListenings = $allListenings->merge($unit->listenings);
      }
  }
  $allListenings = $allListenings->merge($document->listenings)->unique('id');

  $totalListenings = $allListenings->count();
  $totalDuration = $allListenings->sum('duration');
  
  $totalHours = floor($totalDuration / 3600);
  $totalMinutes = floor(($totalDuration % 3600) / 60);
  $totalSeconds = $totalDuration % 60;
  
  $totalDurationFormatted = $totalHours > 0 
      ? "{$totalHours} giờ {$totalMinutes} phút" 
      : "{$totalMinutes} phút {$totalSeconds} giây";

  $totalUnits = $document->parts->sum(fn($p) => $p->units->count());
  
  // 2. Tính phần trăm hoàn thành (Số bài nghe có ít nhất 1 lần đạt / Tổng số bài)
  $completedCount = $allListenings->filter(function($l) {
      return $l->sessions->where('is_passed', true)->isNotEmpty();
  })->count();
  $progressPercent = $totalListenings > 0 ? round(($completedCount / $totalListenings) * 100) : 0;
  
  // 3. Định nghĩa các URL bắt đầu / học tiếp
  $firstListening = $allListenings->sortBy('id')->first();
  $firstListeningUrl = $firstListening ? route('listening.show', $firstListening->id) : '#';
  
  $nextListening = $allListenings->filter(function($l) {
      return $l->sessions->where('is_passed', true)->isEmpty();
  })->sortBy('id')->first() ?? $firstListening;
  $nextListeningUrl = $nextListening ? route('listening.show', $nextListening->id) : '#';

  // 4. Thống kê cột phải
  $totalActualDuration = $allListenings->flatMap->sessions->sum('duration_seconds');
  $actualHours = floor($totalActualDuration / 3600);
  $actualMinutes = floor(($totalActualDuration % 3600) / 60);
  
  $actualDurationFormatted = $actualHours > 0 
      ? "{$actualHours} giờ {$actualMinutes} phút" 
      : "{$actualMinutes} phút";

  $diffSeconds = $totalActualDuration - $totalDuration;
  $diffFormatted = '';
  if ($diffSeconds > 0) {
      $diffH = floor($diffSeconds / 3600);
      $diffM = floor(($diffSeconds % 3600) / 60);
      $diffFormatted = "+ " . ($diffH > 0 ? "{$diffH} giờ {$diffM} phút" : "{$diffM} phút");
  } else {
      $diffH = floor(abs($diffSeconds) / 3600);
      $diffM = floor((abs($diffSeconds) % 3600) / 60);
      $diffFormatted = "- " . ($diffH > 0 ? "{$diffH} giờ {$diffM} phút" : "{$diffM} phút");
  }

  $totalListens = $allListenings->flatMap->sessions->count();
  $passedListens = $allListenings->flatMap->sessions->where('is_passed', true)->count();
  
  $avgSpeed = '1.0x'; // Chuẩn mặc định
  $avgEfficiency = round($allListenings->flatMap->sessions->avg('efficiency_score') ?? 0);

  // Bảng màu chủ đề cho các Giai đoạn
  $themes = [
      [
          'color' => '#10b981', // Xanh lá
          'bg' => 'rgba(16, 185, 129, 0.04)',
          'border' => 'rgba(16, 185, 129, 0.15)',
          'badge' => 'bg-success-subtle text-success'
      ],
      [
          'color' => '#f59e0b', // Cam
          'bg' => 'rgba(245, 158, 11, 0.04)',
          'border' => 'rgba(245, 158, 11, 0.15)',
          'badge' => 'bg-warning-subtle text-warning'
      ],
      [
          'color' => '#3b82f6', // Xanh dương
          'bg' => 'rgba(59, 130, 246, 0.04)',
          'border' => 'rgba(59, 130, 246, 0.15)',
          'badge' => 'bg-primary-subtle text-primary'
      ],
      [
          'color' => '#8b5cf6', // Tím
          'bg' => 'rgba(139, 92, 246, 0.04)',
          'border' => 'rgba(139, 92, 246, 0.15)',
          'badge' => 'bg-purple-subtle text-purple'
      ]
  ];
@endphp

<div class="doc-detail py-4 px-3" style="font-family: 'Inter', sans-serif;">

  {{-- Breadcrumb --}}
  <nav class="breadcrumb-custom mb-3 small d-flex align-items-center gap-2 text-muted">
    <a href="{{ route('listening.index') }}" class="text-primary text-decoration-none fw-semibold">🎧 Luyện Nghe</a>
    <span class="sep">›</span>
    <a href="{{ route('document.index') }}" class="text-primary text-decoration-none fw-semibold">Tài liệu</a>
    <span class="sep">›</span>
    <span>{{ $document->name }}</span>
  </nav>

  {{-- Hero Header Card --}}
  <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4" style="font-family: 'Outfit', sans-serif;">
    <div class="row align-items-center">
      <!-- Cover Image -->
      <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
        @if($document->cover_image)
          <img src="{{ asset('storage/' . $document->cover_image) }}" alt="{{ $document->name }}" class="img-fluid rounded-3 shadow-sm border" style="max-height: 160px; object-fit: cover;">
        @else
          <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-3 shadow-sm mx-auto border" style="width: 110px; height: 160px; font-size: 3rem; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);">📘</div>
        @endif
      </div>
      
      <!-- Title & Description -->
      <div class="col-md-7 mb-3 mb-md-0">
        <div class="d-flex align-items-center gap-2.5 mb-2 flex-wrap justify-content-center justify-content-md-start">
          <h2 class="fw-bold text-dark mb-0 fs-3">{{ $document->name }}</h2>
          @php
            $journeyStatus = $document->journey_status ?? 'not_started';
            $statusLabel = $journeyStatus === 'completed' ? 'Hoàn thành' : ($journeyStatus === 'in_progress' ? 'Đang học' : 'Chưa học');
            $statusClass = $journeyStatus === 'completed' ? 'bg-success-subtle text-success' : ($journeyStatus === 'in_progress' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary');
          @endphp
          <span class="badge {{ $statusClass }} px-2.5 py-1 rounded-pill small fw-semibold">{{ $statusLabel }}</span>
        </div>
        <p class="text-secondary small mb-4 text-center text-md-start">{{ $document->description ?? 'Tài liệu luyện nghe tiếng Anh dành cho học viên.' }}</p>
        
        <!-- Metrics -->
        <div class="d-flex align-items-center gap-4 justify-content-center justify-content-md-start flex-wrap text-secondary">
          <div class="d-flex align-items-center gap-2">
            <span class="fs-5">📖</span>
            <div>
              <div class="fw-bold text-dark" style="line-height: 1.1; font-size: 15px;">{{ $totalUnits }}</div>
              <span class="text-muted" style="font-size: 11px;">Units</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="fs-5">🎧</span>
            <div>
              <div class="fw-bold text-dark" style="line-height: 1.1; font-size: 15px;">{{ $totalListenings }}</div>
              <span class="text-muted" style="font-size: 11px;">Bài nghe</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="fs-5">⏱️</span>
            <div>
              <div class="fw-bold text-dark" style="line-height: 1.1; font-size: 15px;">{{ $totalDurationFormatted }}</div>
              <span class="text-muted" style="font-size: 11px;">Tổng thời lượng</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Progress & Actions -->
      <div class="col-md-3 d-flex flex-column align-items-center gap-3 border-start-md">
        <!-- Progress Circle -->
        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
          <svg width="100" height="100" viewBox="0 0 100 100" class="position-absolute">
            <circle cx="50" cy="50" r="40" stroke="#f1f5f9" stroke-width="8" fill="transparent" />
            <circle cx="50" cy="50" r="40" stroke="#10b981" stroke-width="8" fill="transparent"
                    stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $progressPercent) / 100 }}"
                    stroke-linecap="round" style="transition: stroke-dashoffset 0.5s ease-in-out; transform: rotate(-90deg); transform-origin: 50% 50%;" />
          </svg>
          <div class="text-center" style="z-index: 1;">
            <div class="fs-4 fw-bold text-dark" style="line-height: 1;">{{ $progressPercent }}%</div>
            <div class="text-muted" style="font-size: 9px; font-weight: 500; margin-top: 2px;">Hoàn thành</div>
          </div>
        </div>
        
        <!-- Actions -->
        <div class="d-flex gap-2 w-100 justify-content-center">
          <a href="{{ $nextListeningUrl }}" class="btn btn-sm btn-outline-primary px-3 py-2 fw-bold d-flex align-items-center gap-1.5" style="border-radius: 10px; font-size: 13px;">
            <span>▶</span> Tiếp tục học
          </a>
          <a href="{{ $firstListeningUrl }}" class="btn btn-sm btn-primary px-3 py-2 fw-bold d-flex align-items-center gap-1.5" style="border-radius: 10px; font-size: 13px; background-color: #2563eb !important; border-color: #2563eb !important;">
            <span>🎧</span> Học từ đầu
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Thống kê nhanh (Quick Stats Row) -->
  <div class="row g-3 mb-4" style="font-family: 'Outfit', sans-serif;">
    <div class="col-md-3 col-6">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
        <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 20px;">📁</div>
        <div>
          <span class="text-muted small d-block" style="font-size: 11px;">Tổng số giai đoạn</span>
          <strong class="text-dark fs-5">{{ $document->parts->count() }}</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 20px; background-color: rgba(139, 92, 246, 0.1); color: rgb(139, 92, 246);">🥞</div>
        <div>
          <span class="text-muted small d-block" style="font-size: 11px;">Tổng số units</span>
          <strong class="text-dark fs-5">{{ $totalUnits }}</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
        <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 20px;">📄</div>
        <div>
          <span class="text-muted small d-block" style="font-size: 11px;">Tổng số bài nghe</span>
          <strong class="text-dark fs-5">{{ $totalListenings }}</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
        <div class="bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 20px;">⏱️</div>
        <div>
          <span class="text-muted small d-block" style="font-size: 11px;">Thời lượng chuẩn</span>
          <strong class="text-dark fs-5">{{ $totalDurationFormatted }}</strong>
        </div>
      </div>
    </div>
  </div>

  {{-- Layout chính 2 cột --}}
  <div class="row">
    
    {{-- Cột trái: Nội dung mục lục tài liệu --}}
    <div class="col-lg-8 mb-4">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h4 class="fw-bold text-dark mb-0" style="font-family: 'Outfit', sans-serif;">📖 Nội dung tài liệu</h4>
          <button type="button" class="btn btn-sm btn-light border fw-semibold" id="btn-toggle-all">Mở rộng tất cả</button>
        </div>

        <div class="stages-container">
          @forelse($document->parts as $partIndex => $part)
            @php
              // Tính toán nhanh số lượng bài nghe & tổng thời lượng của giai đoạn này
              $partListenings = collect();
              foreach ($part->units as $unit) {
                  $partListenings = $partListenings->merge($unit->listenings);
              }
              $partListenings = $partListenings->merge($part->listenings)->unique('id');
              $partListeningsCount = $partListenings->count();
              
              $partDuration = $partListenings->sum('duration');
              $partHours = floor($partDuration / 3600);
              $partMinutes = floor(($partDuration % 3600) / 60);
              $partSeconds = $partDuration % 60;
              
              $partDurationFormatted = $partHours > 0 
                  ? "{$partHours} giờ {$partMinutes} phút" 
                  : "{$partMinutes} phút {$partSeconds} giây";

              $theme = $themes[$partIndex % count($themes)];
            @endphp

            <div class="stage-wrapper mb-3">
              
              {{-- Header Giai đoạn --}}
              <div class="stage-header d-flex align-items-center justify-content-between p-3 rounded-3 cursor-pointer transition-all" 
                   style="background-color: {{ $theme['bg'] }}; border: 1px solid {{ $theme['border'] }};"
                   data-target="#stage-body-{{ $part->id }}">
                <div class="d-flex align-items-center gap-3">
                  <span class="fs-4" style="color: {{ $theme['color'] }}; line-height: 1;">📁</span>
                  <h6 class="fw-bold text-dark mb-0" style="font-family: 'Outfit', sans-serif;">{{ $part->name }}</h6>
                </div>
                <div class="d-flex align-items-center gap-3 text-secondary small">
                  <span class="d-none d-sm-inline">{{ $part->units->count() }} units • {{ $partListeningsCount }} bài nghe • {{ $partDurationFormatted }}</span>
                  <span class="d-inline d-sm-none">{{ $partListeningsCount }} bài</span>
                  <span class="collapse-icon fw-bold" style="color: {{ $theme['color'] }};">{{ $partIndex === 0 ? '▲' : '▼' }}</span>
                </div>
              </div>

              {{-- Body Giai đoạn (Danh sách Unit) --}}
              <div class="stage-body {{ $partIndex === 0 ? '' : 'd-none' }} mt-2" id="stage-body-{{ $part->id }}">
                <div class="table-responsive bg-white rounded-3 border border-light shadow-2xs">
                  <table class="table table-hover mb-0 align-middle">
                    <thead>
                      <tr class="table-light text-muted small" style="font-size: 11px; letter-spacing: 0.5px;">
                        <th style="width: 45%; padding-left: 1.5rem; border-bottom: 1px solid #f1f5f9;">UNIT</th>
                        <th style="width: 25%; border-bottom: 1px solid #f1f5f9;" class="text-center">SỐ BÀI</th>
                        <th style="width: 25%; border-bottom: 1px solid #f1f5f9;" class="text-center">THỜI LƯỢNG (CHUẨN)</th>
                        <th style="width: 5%; border-bottom: 1px solid #f1f5f9;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($part->units as $unitIndex => $unit)
                        @php
                          $unitListeningsCount = $unit->listenings->count();
                          $unitDuration = $unit->listenings->sum('duration');
                          $unitMin = floor($unitDuration / 60);
                          $unitSec = $unitDuration % 60;
                          $unitDurationFormatted = "{$unitMin} phút {$unitSec} giây";
                        @endphp
                        <tr class="unit-row cursor-pointer" data-target="#unit-listenings-{{ $unit->id }}">
                          <td style="padding-left: 1.5rem; border-bottom: 1px solid #f8fafc;">
                            <div class="d-flex align-items-center gap-3">
                              <span class="rounded-circle bg-light text-dark fw-bold d-flex align-items-center justify-content-center shadow-2xs border" style="width: 32px; height: 32px; font-size: 13px; flex-shrink: 0;">
                                {{ sprintf('%02d', $unitIndex + 1) }}
                              </span>
                              <span class="fw-bold text-dark" style="font-size: 14px;">{{ $unit->name }}</span>
                            </div>
                          </td>
                          <td class="text-center text-secondary small fw-medium" style="border-bottom: 1px solid #f8fafc;">{{ $unitListeningsCount }} bài nghe</td>
                          <td class="text-center text-secondary small" style="border-bottom: 1px solid #f8fafc;">{{ $unitDurationFormatted }}</td>
                          <td class="text-end text-muted font-weight-bold" style="padding-right: 1.5rem; border-bottom: 1px solid #f8fafc;">
                            <span class="unit-arrow-icon d-inline-block transition-all" style="font-size: 14px; font-family: monospace; transform-origin: center;">▶</span>
                          </td>
                        </tr>
                        
                        <!-- Sub-row chứa danh sách bài nghe của Unit (Ẩn mặc định) -->
                        <tr id="unit-listenings-{{ $unit->id }}" class="d-none bg-light">
                          <td colspan="4" class="p-0">
                            <div class="px-4 py-3 border-top border-bottom border-light-subtle">
                              @if($unit->listenings->count())
                                <div class="d-flex flex-column gap-2">
                                  @foreach($unit->listenings as $ls)
                                    @php
                                      $isCompleted = $ls->sessions->where('is_passed', true)->isNotEmpty();
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-3 border border-light-subtle shadow-2xs hover-bg-light transition-all">
                                      <div class="d-flex align-items-center gap-2.5 min-w-0">
                                        @if($isCompleted)
                                          <span class="text-success" title="Đã hoàn thành" style="font-size: 14px;">✅</span>
                                        @else
                                          <span class="text-muted" title="Chưa hoàn thành" style="font-size: 14px;">🎧</span>
                                        @endif
                                        <span class="fw-semibold text-dark text-truncate small">{{ $ls->title }}</span>
                                      </div>
                                      <div class="d-flex align-items-center gap-3 text-secondary small flex-shrink-0">
                                        <span>⏱️ {{ gmdate('i:s', $ls->duration) }}</span>
                                        <a href="{{ route('listening.show', $ls->id) }}" class="btn btn-xs btn-primary font-weight-bold px-3 py-1 text-decoration-none d-inline-flex align-items-center justify-content-center" style="background-color: #2563eb !important; border-color: #2563eb !important; border-radius: 6px;">▶ Luyện nghe</a>
                                      </div>
                                    </div>
                                  @endforeach
                                </div>
                              @else
                                <div class="text-center text-muted py-2 small">Chưa có bài nghe nào trong Unit này.</div>
                              @endif
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="4" class="text-center text-muted py-3 small">Không có Unit nào trong giai đoạn này.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          @empty
            <div class="text-center text-muted py-5 card border-0 bg-light rounded-4">
              <span class="fs-1 mb-2">📂</span>
              <h6>Chưa có giai đoạn nào được thiết lập cho tài liệu này.</h6>
            </div>
          @endforelse
        </div>

      </div>
    </div>

    {{-- Cột phải: Tổng kết toàn bộ tài liệu --}}
    <div class="col-lg-4 mb-4">
      <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
        
        <h5 class="fw-bold text-dark mb-4" style="font-family: 'Outfit', sans-serif;">📊 Tổng kết toàn bộ tài liệu</h5>

        <div class="d-flex flex-column gap-3">
          
          {{-- 1. Tổng số bài nghe --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(37, 99, 235, 0.08) !important;">📋</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Tổng số bài nghe</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $totalListenings }} bài</strong>
              </div>
            </div>
          </div>

          {{-- 2. Tổng thời lượng chuẩn --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(245, 158, 11, 0.08) !important;">⏱️</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Tổng thời lượng chuẩn</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $totalDurationFormatted }}</strong>
                <span class="text-muted small d-block">({{ floor($totalDuration / 60) }} phút)</span>
              </div>
            </div>
          </div>

          {{-- 3. Tổng thời gian thực tế --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(16, 185, 129, 0.08) !important;">⏱️</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Tổng thời gian thực tế</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $actualDurationFormatted }}</strong>
                <span class="text-muted small d-block">({{ floor($totalActualDuration / 60) }} phút)</span>
              </div>
            </div>
          </div>

          {{-- 4. Chênh lệch --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(239, 68, 68, 0.08) !important;">📈</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Chênh lệch</span>
                <strong class="{{ $diffSeconds > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 15px;">
                  {{ $diffFormatted }}
                </strong>
                <span class="text-muted small d-block">({{ $diffSeconds > 0 ? '+' : '' }}{{ floor($diffSeconds / 60) }} phút)</span>
              </div>
            </div>
          </div>

          {{-- 5. Tổng lượt nghe --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-purple-subtle text-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(139, 92, 246, 0.08) !important; color: #8b5cf6 !important;">🎧</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Tổng lượt nghe</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $totalListens }} lượt</strong>
              </div>
            </div>
          </div>

          {{-- 6. Nghe để hiểu --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(6, 182, 212, 0.08) !important; color: #06b6d4 !important;">🧠</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Nghe để hiểu</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $passedListens }} lần</strong>
              </div>
            </div>
          </div>

          {{-- 7. Tốc độ trung bình --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(236, 72, 153, 0.08) !important; color: #ec4899 !important;">⚡</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Tốc độ trung bình</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $avgSpeed }}</strong>
              </div>
            </div>
          </div>

          {{-- 8. Hiệu suất học --}}
          <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light shadow-2xs">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px; background-color: rgba(244, 63, 94, 0.08) !important; color: #f43f5e !important;">🎯</div>
              <div>
                <span class="text-muted d-block" style="font-size: 12px; font-weight: 500;">Hiệu suất học</span>
                <strong class="text-dark" style="font-size: 15px;">{{ $avgEfficiency }}%</strong>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>

  {{-- Hướng dẫn học --}}
  <div class="card bg-light border-0 rounded-4 p-4 mt-2 shadow-2xs" style="background-color: rgba(37, 99, 235, 0.03) !important; border: 1px solid rgba(37, 99, 235, 0.08) !important;">
    <h6 class="fw-bold text-primary mb-2 d-flex align-items-center gap-2">
      ℹ️ Hướng dẫn học
    </h6>
    <ul class="text-secondary small mb-0 pl-3">
      <li class="mb-1">Mỗi Unit có nhiều bài nghe. Bạn có thể nhấn vào từng Unit để xem danh sách bài nghe chi tiết.</li>
      <li>Thời lượng chuẩn là tổng thời gian của tất cả cả bài nghe trong Unit.</li>
    </ul>
  </div>

</div>

{{-- Custom CSS cho giao diện --}}
<style>
  .border-start-md {
    border-left: 1px solid #f1f5f9;
  }
  @media (max-width: 768px) {
    .border-start-md {
      border-left: 0;
      border-top: 1px solid #f1f5f9;
      padding-top: 1.5rem;
    }
  }
  .shadow-2xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
  }
  .cursor-pointer {
    cursor: pointer;
  }
  .transition-all {
    transition: all 0.25s ease-in-out;
  }
  .unit-row:hover {
    background-color: #fafafa !important;
  }
</style>

{{-- Custom JS cho Accordion --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // 1. Xử lý mở rộng / thu gọn Giai đoạn (Stage)
  document.querySelectorAll('.stage-header').forEach(header => {
    header.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const target = document.querySelector(targetId);
      const icon = this.querySelector('.collapse-icon');
      
      if (target.classList.contains('d-none')) {
        target.classList.remove('d-none');
        icon.textContent = '▲';
      } else {
        target.classList.add('d-none');
        icon.textContent = '▼';
      }
    });
  });

  // 2. Xử lý mở rộng / thu gọn Unit
  document.querySelectorAll('.unit-row').forEach(row => {
    row.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const target = document.querySelector(targetId);
      const icon = this.querySelector('.unit-arrow-icon');
      
      if (target.classList.contains('d-none')) {
        target.classList.remove('d-none');
        icon.style.transform = 'rotate(90deg)';
      } else {
        target.classList.add('d-none');
        icon.style.transform = 'rotate(0deg)';
      }
    });
  });

  // 3. Xử lý nút Mở rộng / Thu gọn tất cả các Giai đoạn
  const btnToggleAll = document.getElementById('btn-toggle-all');
  let allExpanded = false; // Mặc định chỉ mở giai đoạn 1, nên coi như chưa mở tất cả
  
  btnToggleAll.addEventListener('click', function() {
    allExpanded = !allExpanded;
    
    document.querySelectorAll('.stage-body').forEach(body => {
      if (allExpanded) {
        body.classList.remove('d-none');
      } else {
        body.classList.add('d-none');
      }
    });
    
    document.querySelectorAll('.stage-header .collapse-icon').forEach(icon => {
      icon.textContent = allExpanded ? '▲' : '▼';
    });

    btnToggleAll.textContent = allExpanded ? 'Thu gọn tất cả' : 'Mở rộng tất cả';
  });

});
</script>
@endsection
