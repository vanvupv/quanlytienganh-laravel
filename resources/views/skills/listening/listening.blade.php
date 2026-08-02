@extends('skills.listening.index')

@section('title', 'Kỹ năng Nghe — Listening')

@section('content_listening')
<div class="listening-home py-4 px-3">

  {{-- ═══════════════════════════════
       HERO HEADER
       ═══════════════════════════════ --}}
  <div class="listening-hero mb-5">
    <div class="listening-hero__content">
      <div class="hero-icon">🎧</div>
      <div>
        <h1 class="listening-hero__title">Kỹ năng Nghe — Listening</h1>
        <p class="listening-hero__sub">Luyện tập nghe hiểu với phương pháp viết lại transcript hiệu quả</p>
      </div>
    </div>
    <div class="hero-actions">
      <a href="{{ route('document.index') }}" class="hero-btn hero-btn--primary">
        📚 Danh mục Tài liệu
      </a>
      @if(request()->is('*-admin*') || request()->is('admin*'))
        <a href="{{ route('listening.create') }}" class="hero-btn hero-btn--ghost">
          ➕ Thêm File Nghe
        </a>
      @endif
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ═══════════════════════════════
       STATS OVERVIEW
       ═══════════════════════════════ --}}
  <div class="section-header mb-3">
    <h3 class="section-title">📊 Kho lưu trữ học liệu</h3>
  </div>
  <div class="stats-overview mb-4">
    <div class="stat-card stat-card--blue">
      <div class="stat-card__icon">📚</div>
      <div class="stat-card__num">{{ $stats['total_docs'] }}</div>
      <div class="stat-card__lbl">Tài liệu</div>
    </div>
    <div class="stat-card stat-card--purple">
      <div class="stat-card__icon">🎵</div>
      <div class="stat-card__num">{{ $stats['total_files'] }}</div>
      <div class="stat-card__lbl">File Nghe</div>
    </div>
    <div class="stat-card stat-card--green">
      <div class="stat-card__icon">⏱️</div>
      @php
        $totalSecs = $stats['total_duration'];
        $h = floor($totalSecs / 3600);
        $m = floor(($totalSecs % 3600) / 60);
      @endphp
      <div class="stat-card__num">{{ $h > 0 ? $h.'g '.$m.'p' : $m.' phút' }}</div>
      <div class="stat-card__lbl">Tổng TG Nghe</div>
    </div>
    <div class="stat-card stat-card--orange">
      <div class="stat-card__icon">🏷️</div>
      <div class="stat-card__num">{{ $stats['levels']->count() }}</div>
      <div class="stat-card__lbl">Cấp độ</div>
    </div>
  </div>

  {{-- ═══════════════════════════════
       PERSONAL STUDY STATISTICS
       ═══════════════════════════════ --}}
  <div class="section-header mb-3">
    <h3 class="section-title">📈 Kết quả Luyện tập của Bạn</h3>
  </div>
  <div class="stats-overview mb-5">
    <div class="stat-card stat-card--dark-indigo">
      <div class="stat-card__icon">🎧</div>
      <div class="stat-card__num">{{ $stats['user_sessions'] }}</div>
      <div class="stat-card__lbl">Lượt học thực tế</div>
    </div>
    <div class="stat-card stat-card--dark-teal">
      <div class="stat-card__icon">🏆</div>
      <div class="stat-card__num">{{ $stats['user_passed'] }}</div>
      <div class="stat-card__lbl">Lượt học đạt (≥80%)</div>
    </div>
    <div class="stat-card stat-card--dark-rose">
      <div class="stat-card__icon">🎯</div>
      <div class="stat-card__num">{{ number_format($stats['avg_score'], 1) }}%</div>
      <div class="stat-card__lbl">Độ chính xác TB</div>
    </div>
    <div class="stat-card stat-card--dark-amber">
      <div class="stat-card__icon">⚡</div>
      <div class="stat-card__num">{{ number_format($stats['avg_efficiency'], 1) }}%</div>
      <div class="stat-card__lbl">Hiệu suất học TB</div>
    </div>
  </div>

  {{-- ═══════════════════════════════
       PHƯƠNG PHÁP ĐÁNH GIÁ
       ═══════════════════════════════ --}}
  <div class="method-box mb-5">
    <div class="method-box__title">
      📋 Phương pháp Đánh giá — Hiệu suất Nghe
    </div>
    <div class="method-grid">
      <div class="method-item method-item--gold">
        <div class="method-rank">🥇</div>
        <div class="method-desc">Nghe <strong>1 lần</strong>, viết lại đúng hết transcript</div>
        <div class="method-score">= 100%</div>
      </div>
      <div class="method-item method-item--silver">
        <div class="method-rank">🥈</div>
        <div class="method-desc">Nghe <strong>2 lần</strong> mới hiểu hết</div>
        <div class="method-score">≈ 50%</div>
      </div>
      <div class="method-item method-item--bronze">
        <div class="method-rank">🥉</div>
        <div class="method-desc">Nghe <strong>3+ lần</strong>, hiệu suất giảm dần</div>
        <div class="method-score">≤ 33%</div>
      </div>
      <div class="method-item method-item--info">
        <div class="method-rank">📐</div>
        <div class="method-desc">Công thức: <code>Hiệu suất = 100 / số_lần_nghe</code> (tối thiểu 10%)</div>
        <div class="method-score">min 10%</div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════
       TẦN SUẤT LUYỆN NGHE (TIMELINE)
       ═══════════════════════════════ --}}
  <div class="frequency-section mb-5">
    <div class="section-header mb-3">
      <h3 class="section-title">📊 Tần suất Luyện nghe</h3>
    </div>
    
    <div class="row">
      {{-- Biểu đồ Tuần --}}
      <div class="col-md-6 mb-4 mb-md-0">
        <div class="chart-card">
          <div class="chart-card__header">
            <h5>📅 Tần suất theo Tuần</h5>
          </div>
          <div class="chart-card__body">
            <canvas id="weeklyFrequencyChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>

      {{-- Biểu đồ Tháng --}}
      <div class="col-md-6">
        <div class="chart-card">
          <div class="chart-card__header">
            <h5>📆 Tần suất theo Tháng</h5>
          </div>
          <div class="chart-card__body">
            <canvas id="monthlyFrequencyChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════
       TÀI LIỆU THEO CẤP ĐỘ
       ═══════════════════════════════ --}}
  <div class="section-header mb-3">
    <h3 class="section-title">📁 Tài liệu theo Cấp độ</h3>
    <a href="{{ route('document.index') }}" class="link-all">Xem tất cả →</a>
  </div>

  @if($documents->isEmpty())
    <div class="empty-box mb-5">
      <div class="empty-box__icon">📭</div>
      <p>Chưa có tài liệu nào. @if(request()->is('*-admin*') || request()->is('admin*')) <a href="{{ route('document.create') }}">Thêm tài liệu đầu tiên</a>. @endif</p>
    </div>
  @else
    <div class="level-list mb-5">
      @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
        @if(isset($documents[$lvl]))
          <div class="level-section-row">
            <div class="level-col">
              <span class="lvl-pill lvl-{{ strtolower($lvl) }}">{{ $lvl }}</span>
            </div>
            <div class="doc-mini-list">
              @foreach($documents[$lvl] as $doc)
                <a href="{{ route('document.show', $doc->id) }}" class="doc-mini-card">
                  <div class="doc-mini-card__name">{{ $doc->name }}</div>
                  <div class="doc-mini-card__meta">
                    <span>🎵 {{ $doc->listenings_count ?? 0 }} file</span>
                    @if($doc->author)<span>· {{ $doc->author }}</span>@endif
                  </div>
                </a>
              @endforeach
              @if(request()->is('*-admin*') || request()->is('admin*'))
                <a href="{{ route('document.create') }}" class="doc-mini-card doc-mini-card--add">
                  + Thêm tài liệu {{ $lvl }}
                </a>
              @endif
            </div>
          </div>
        @endif
      @endforeach
    </div>
  @endif

  {{-- ═══════════════════════════════
       DANH SÁCH FILE NGHE GẦN ĐÂY
       ═══════════════════════════════ --}}
  <div class="section-header mb-3">
    <h3 class="section-title">🎵 File Nghe Gần đây</h3>
    @if(request()->is('*-admin*') || request()->is('admin*'))
      <a href="{{ route('listening.create') }}" class="link-all">+ Thêm file →</a>
    @endif
  </div>

  @if($doc_listening->isEmpty())
    <div class="empty-box">
      <div class="empty-box__icon">🎤</div>
      <p>Chưa có file nghe nào. @if(request()->is('*-admin*') || request()->is('admin*')) <a href="{{ route('listening.create') }}">Thêm file nghe đầu tiên</a>. @endif</p>
    </div>
  @else
    <div class="file-grid">
      @foreach($doc_listening->take(12) as $item)
        <div class="file-card">
          <div class="file-card__top">
            <div class="file-card__icon">🎵</div>
            <div class="file-card__info">
              <div class="file-card__title">{{ $item->title }}</div>
              @if($item->document)
                <div class="file-card__doc text-muted">{{ $item->document->name }}</div>
              @endif
            </div>
          </div>

          <div class="file-card__tags">
            @if($item->level)
              <span class="ftag ftag--level">{{ $item->level }}</span>
            @endif
            @if($item->topic)
              <span class="ftag ftag--topic">{{ $item->topic->name }}</span>
            @endif
            @if($item->chapter)
              <span class="ftag ftag--chapter">{{ $item->chapter->name }}</span>
            @endif
          </div>

          <div class="file-card__stats">
            <span>⏱ {{ gmdate('i:s', $item->duration ?? 0) }}</span>
            <span>🔁 {{ $item->listen_count }}x</span>
            @if($item->listen_count > 0)
              <span class="eff-txt" style="color:{{ $item->listen_count === 1 ? '#10b981' : ($item->listen_count <= 2 ? '#f59e0b' : '#ef4444') }}">
                ⚡ {{ $item->efficiency() }}%
              </span>
            @endif
          </div>

          <div class="file-card__actions">
            <a href="{{ route('listening.show', $item->id) }}" class="fcard-btn fcard-btn--play">
              ▶ Luyện nghe
            </a>
            @if(request()->is('*-admin*') || request()->is('admin*'))
              <a href="{{ route('listening.edit', $item->id) }}" class="fcard-btn fcard-btn--edit">✏️</a>
              <form action="{{ route('listening.destroy', $item->id) }}" method="POST" style="display:inline"
                    onsubmit="return confirm('Xóa file nghe này?')">
                @csrf @method('DELETE')
                <button class="fcard-btn fcard-btn--del">🗑️</button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>
    @if($doc_listening->count() > 12)
      <div class="text-center mt-3">
        <p class="text-muted small">Hiển thị 12/{{ $doc_listening->count() }} file nghe.</p>
      </div>
    @endif
  @endif


</div>
@endsection

<style>
/* ====================================================
   LISTENING HOME STYLES
   ==================================================== */
.listening-home { max-width: 1400px; }

/* Hero */
.listening-hero {
  background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #1e3a5f 100%);
  border-radius: 20px; padding: 32px; display: flex;
  justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;
  box-shadow: 0 12px 40px rgba(30,41,59,.25);
}
.listening-hero__content { display: flex; align-items: center; gap: 20px; }
.hero-icon { font-size: 3rem; }
.listening-hero__title { font-size: 1.75rem; font-weight: 900; color: #fff; margin: 0 0 6px; }
.listening-hero__sub { color: #94a3b8; font-size: .95rem; margin: 0; }
.hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.hero-btn {
  padding: 10px 22px; border-radius: 12px; font-weight: 700; font-size: .9rem;
  text-decoration: none; display: inline-block; transition: all .2s;
}
.hero-btn--primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
.hero-btn--primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,.4); color: #fff; }
.hero-btn--ghost { background: rgba(255,255,255,.1); color: #fff; border: 1px solid rgba(255,255,255,.2); }
.hero-btn--ghost:hover { background: rgba(255,255,255,.2); color: #fff; }

/* Stats */
.stats-overview { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.stat-card {
  border-radius: 16px; padding: 20px; text-align: center; color: #fff;
  box-shadow: 0 6px 20px rgba(0,0,0,.1); transition: transform .2s;
}
.stat-card:hover { transform: translateY(-4px); }
.stat-card--blue   { background: linear-gradient(135deg, #3b82f6, #6366f1); }
.stat-card--purple { background: linear-gradient(135deg, #8b5cf6, #a855f7); }
.stat-card--green  { background: linear-gradient(135deg, #10b981, #06b6d4); }
.stat-card--orange { background: linear-gradient(135deg, #f59e0b, #ef4444); }
.stat-card--dark-indigo { background: linear-gradient(135deg, #1e1b4b, #4338ca); }
.stat-card--dark-teal   { background: linear-gradient(135deg, #115e59, #0d9488); }
.stat-card--dark-rose   { background: linear-gradient(135deg, #9f1239, #e11d48); }
.stat-card--dark-amber  { background: linear-gradient(135deg, #78350f, #d97706); }
.stat-card__icon { font-size: 1.8rem; margin-bottom: 6px; }
.stat-card__num { font-size: 1.8rem; font-weight: 900; margin-bottom: 4px; }
.stat-card__lbl { font-size: .78rem; font-weight: 600; opacity: .85; }

/* Method Box */
.method-box {
  background: #fff; border-radius: 16px; overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,.06); border: 1px solid #f1f5f9;
}
.method-box__title {
  padding: 16px 24px; background: #f8fafc; font-size: .95rem; font-weight: 800;
  color: #1e293b; border-bottom: 1px solid #e2e8f0;
}
.method-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
.method-item {
  padding: 20px; border-right: 1px solid #f1f5f9; text-align: center;
  display: flex; flex-direction: column; gap: 8px; align-items: center;
}
.method-item:last-child { border-right: none; }
.method-item--gold   { background: linear-gradient(180deg, #fef9c3, #fff); }
.method-item--silver { background: linear-gradient(180deg, #f1f5f9, #fff); }
.method-item--bronze { background: linear-gradient(180deg, #fef3c7, #fff); }
.method-item--info   { background: linear-gradient(180deg, #e0f2fe, #fff); }
.method-rank { font-size: 2rem; }
.method-desc { font-size: .8rem; color: #475569; }
.method-score { font-size: 1.4rem; font-weight: 900; color: #1e293b; }

/* Section Header */
.section-header { display: flex; align-items: center; justify-content: space-between; }
.section-title { font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0; }
.link-all { font-size: .82rem; color: #6366f1; font-weight: 700; text-decoration: none; }
.link-all:hover { text-decoration: underline; }

/* Level List */
.level-list { display: flex; flex-direction: column; gap: 16px; }
.level-section-row { display: flex; align-items: flex-start; gap: 16px; }
.level-col { flex-shrink: 0; width: 50px; }
.lvl-pill {
  display: block; text-align: center; font-size: .75rem; font-weight: 800;
  padding: 5px 8px; border-radius: 10px; color: #fff;
}
.lvl-a1{background:#10b981} .lvl-a2{background:#06b6d4}
.lvl-b1{background:#3b82f6} .lvl-b2{background:#8b5cf6}
.lvl-c1{background:#f59e0b} .lvl-c2{background:#ef4444}

.doc-mini-list { display: flex; flex-wrap: wrap; gap: 10px; flex: 1; }
.doc-mini-card {
  padding: 10px 16px; background: #fff; border-radius: 12px;
  border: 1px solid #e2e8f0; text-decoration: none; color: #1e293b;
  transition: all .2s; min-width: 180px;
}
.doc-mini-card:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(99,102,241,.15); transform: translateY(-2px); color: #1e293b; }
.doc-mini-card__name { font-size: .85rem; font-weight: 700; margin-bottom: 2px; }
.doc-mini-card__meta { font-size: .72rem; color: #94a3b8; }
.doc-mini-card--add {
  border-style: dashed; color: #94a3b8 !important;
  display: flex; align-items: center; justify-content: center;
  font-size: .82rem; font-weight: 700;
}
.doc-mini-card--add:hover { color: #6366f1 !important; border-color: #6366f1; }

/* File Grid */
.file-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;
}
.file-card {
  background: #fff; border-radius: 16px; padding: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,.06); border: 1px solid #f1f5f9;
  transition: transform .2s, box-shadow .2s; display: flex; flex-direction: column; gap: 10px;
}
.file-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(0,0,0,.1); }

.file-card__top { display: flex; gap: 12px; align-items: flex-start; }
.file-card__icon { font-size: 1.8rem; flex-shrink: 0; }
.file-card__title { font-size: .9rem; font-weight: 700; color: #1e293b; }
.file-card__doc { font-size: .75rem; color: #94a3b8; }

.file-card__tags { display: flex; flex-wrap: wrap; gap: 6px; }
.ftag { font-size: .68rem; font-weight: 700; padding: 2px 9px; border-radius: 8px; }
.ftag--level   { background: #ddd6fe; color: #6d28d9; }
.ftag--topic   { background: #fef3c7; color: #d97706; }
.ftag--chapter { background: #e0f2fe; color: #0369a1; }

.file-card__stats { display: flex; gap: 12px; font-size: .75rem; color: #64748b; font-weight: 600; flex-wrap: wrap; }
.eff-txt { font-weight: 800; }

.file-card__actions { display: flex; gap: 6px; margin-top: auto; }
.fcard-btn {
  font-size: .75rem; font-weight: 700; padding: 5px 12px; border-radius: 8px;
  border: none; cursor: pointer; text-decoration: none; display: inline-block;
  transition: all .2s;
}
.fcard-btn--play { background: #6366f1; color: #fff; flex: 1; text-align: center; }
.fcard-btn--play:hover { background: #4f46e5; color: #fff; }
.fcard-btn--edit { background: #fef9c3; color: #ca8a04; }
.fcard-btn--del  { background: #fee2e2; color: #dc2626; }

/* Empty Box */
.empty-box { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty-box__icon { font-size: 3rem; margin-bottom: 12px; }
.empty-box a { color: #6366f1; font-weight: 700; }

/* Chart Card */
.chart-card {
  background: #fff; border-radius: 16px; border: 1px solid #f1f5f9;
  box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden; padding: 20px;
}
.chart-card__header h5 { font-size: .95rem; font-weight: 800; color: #1e293b; margin-bottom: 15px; }
.chart-card__body { min-height: 250px; position: relative; }

/* Responsive */
@media (max-width: 1024px) {
  .stats-overview { grid-template-columns: repeat(2, 1fr); }
  .method-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .listening-hero { flex-direction: column; }
  .stats-overview { grid-template-columns: 1fr 1fr; }
  .method-grid { grid-template-columns: 1fr; }
  .level-section-row { flex-direction: column; }
}
</style>

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Weekly Frequency
    const weeklyLabels = {!! json_encode($weeklyFreq->pluck('label')) !!};
    const weeklyData = {!! json_encode($weeklyFreq->pluck('count')) !!};

    const ctxWeekly = document.getElementById('weeklyFrequencyChart');
    if (ctxWeekly && weeklyLabels.length > 0) {
        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Số lần nghe',
                    data: weeklyData,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } else if (ctxWeekly) {
        const parent = ctxWeekly.parentElement;
        parent.innerHTML = '<div class="text-muted text-center py-5">Chưa có dữ liệu nghe theo tuần. Hãy bắt đầu luyện nghe để ghi nhận thông tin!</div>';
    }

    // Monthly Frequency
    const monthlyLabels = {!! json_encode($monthlyFreq->pluck('label')) !!};
    const monthlyData = {!! json_encode($monthlyFreq->pluck('count')) !!};

    const ctxMonthly = document.getElementById('monthlyFrequencyChart');
    if (ctxMonthly && monthlyLabels.length > 0) {
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Số lần nghe',
                    data: monthlyData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } else if (ctxMonthly) {
        const parent = ctxMonthly.parentElement;
        parent.innerHTML = '<div class="text-muted text-center py-5">Chưa có dữ liệu nghe theo tháng. Hãy bắt đầu luyện nghe để ghi nhận thông tin!</div>';
    }
});
</script>
@endpush