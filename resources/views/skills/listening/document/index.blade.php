@extends('skills.listening.index')

@section('title', 'Listening — Danh mục Tài liệu')

@section('content_listening')
<section class="doc-library py-4 px-4">

  {{-- Header --}}
  <div class="doc-lib__header d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="doc-lib__title mb-1">
        <span class="emoji-icon">📚</span> Danh mục Tài liệu Listening
      </h2>
      <p class="text-muted small mb-0">Quản lý tài liệu theo cấp độ &amp; hành trình học</p>
    </div>
    @if(request()->is('*-admin*') || request()->is('admin*'))
      <a href="{{ route('document.create') }}" class="btn-primary-custom">
        <i class="mdi mdi-plus-circle me-1"></i> Thêm Tài liệu
      </a>
    @endif
  </div>

  {{-- Flash message --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Level Filter Tabs --}}
  <div class="level-tabs mb-4">
    <button class="level-tab active" data-level="all">Tất cả</button>
    @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
      <button class="level-tab" data-level="{{ $lvl }}">{{ $lvl }}</button>
    @endforeach
  </div>

  {{-- Documents grouped by level --}}
  @if($documents->isEmpty())
    <div class="empty-state text-center py-5">
      <div style="font-size:4rem;">📭</div>
      <h4 class="mt-3 text-muted">Chưa có tài liệu nào</h4>
      <p class="text-muted">Hãy thêm tài liệu đầu tiên để bắt đầu!</p>
      @if(request()->is('*-admin*') || request()->is('admin*'))
        <a href="{{ route('document.create') }}" class="btn-primary-custom mt-2">
          + Thêm Tài liệu
        </a>
      @endif
    </div>
  @else
    @foreach($documents as $level => $docs)
      <div class="level-section mb-5" data-section-level="{{ $level ?: 'other' }}">

        {{-- Level Badge Header --}}
        <div class="level-header d-flex align-items-center mb-3 gap-3">
          <span class="level-badge level-{{ strtolower($level ?: 'x') }}">
            {{ $level ?: 'Chưa phân loại' }}
          </span>
          <div class="level-line flex-grow-1"></div>
          <span class="text-muted small">{{ $docs->count() }} tài liệu</span>
        </div>

        {{-- Document Cards --}}
        <div class="doc-grid">
          @foreach($docs as $doc)
          <div class="doc-card" data-level="{{ $doc->level }}">
            {{-- Cover Image --}}
            <div class="doc-card__cover">
              @if($doc->cover_image)
                <img src="{{ asset('storage/' . $doc->cover_image) }}" alt="{{ $doc->name }}">
              @else
                <div class="doc-card__cover-placeholder">
                  <span>📖</span>
                </div>
              @endif
              {{-- Status Badge --}}
              <span class="doc-card__status status-{{ $doc->status }}">
                {{ $doc->status === 'active' ? 'Đang dùng' : ($doc->status === 'archived' ? 'Lưu trữ' : 'Tạm dừng') }}
              </span>
            </div>

            {{-- Card Body --}}
            <div class="doc-card__body">
              <h5 class="doc-card__name">{{ $doc->name }}</h5>

              @if($doc->author)
                <p class="doc-card__author text-muted small mb-2">
                  <i class="mdi mdi-account-edit me-1"></i>{{ $doc->author }}
                  @if($doc->publisher) · {{ $doc->publisher }} @endif
                </p>
              @endif

              @if($doc->description)
                <p class="doc-card__desc text-muted small">
                  {{ Str::limit($doc->description, 80) }}
                </p>
              @endif

              {{-- Stats Row --}}
              <div class="doc-card__stats">
                <span class="stat-pill">
                  <i class="mdi mdi-headphones"></i>
                  {{ $doc->listenings_count ?? 0 }} file
                </span>
                @if($doc->total_lessons)
                  <span class="stat-pill">
                    <i class="mdi mdi-book-open-page-variant"></i>
                    {{ $doc->total_lessons }} bài
                  </span>
                @endif
                @if($doc->level)
                  <span class="stat-pill stat-pill--level">
                    {{ $doc->level }}
                  </span>
                @endif
              </div>

              {{-- Journey Progress --}}
              @if($doc->journey_status !== 'not_started')
                <div class="journey-bar mt-2">
                  <div class="journey-bar__inner journey-{{ $doc->journey_status }}"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                  <small class="text-muted">
                    {{ $doc->journey_status === 'completed' ? '✅ Hoàn thành' : '📖 Đang học' }}
                  </small>
                  @if($doc->is_passed)
                    <small class="text-success fw-bold">🏆 Đạt</small>
                  @endif
                </div>
              @endif

              {{-- Actions --}}
              <div class="doc-card__actions mt-3">
                <a href="{{ route('document.show', $doc->id) }}" class="btn-action btn-action--primary">
                  Chi tiết
                </a>
                @if(request()->is('*-admin*') || request()->is('admin*'))
                  <a href="{{ route('document.edit', $doc->id) }}" class="btn-action btn-action--edit">
                    Sửa
                  </a>
                  <form action="{{ route('document.destroy', $doc->id) }}" method="POST"
                        onsubmit="return confirm('Xóa tài liệu này?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-action--delete">Xóa</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>

      </div>{{-- /level-section --}}
    @endforeach
  @endif

</section>
@endsection

@push('script')
<script>
// Filter by level tab
document.querySelectorAll('.level-tab').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.level-tab').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    const level = this.dataset.level;
    document.querySelectorAll('.level-section').forEach(sec => {
      if (level === 'all') {
        sec.style.display = '';
      } else {
        sec.style.display = sec.dataset.sectionLevel === level ? '' : 'none';
      }
    });
  });
});
</script>
@endpush

<style>
/* ========================================
   DOCUMENT LIBRARY STYLES
   ======================================== */
.doc-library { max-width: 1400px; }

.doc-lib__title { font-size: 1.6rem; font-weight: 700; color: #1e293b; }
.emoji-icon { font-size: 1.4em; }

/* Level Tabs */
.level-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
.level-tab {
  padding: 6px 18px; border-radius: 20px; border: 2px solid #e2e8f0;
  background: #fff; color: #64748b; font-weight: 600; cursor: pointer;
  transition: all .2s;
}
.level-tab:hover { border-color: #6366f1; color: #6366f1; }
.level-tab.active { background: #6366f1; color: #fff; border-color: #6366f1; }

/* Level Badge */
.level-badge {
  font-size: .8rem; font-weight: 700; padding: 4px 14px;
  border-radius: 12px; color: #fff; white-space: nowrap;
}
.level-a1 { background: #10b981; }
.level-a2 { background: #06b6d4; }
.level-b1 { background: #3b82f6; }
.level-b2 { background: #8b5cf6; }
.level-c1 { background: #f59e0b; }
.level-c2 { background: #ef4444; }
.level-x  { background: #94a3b8; }
.level-line { height: 1px; background: #e2e8f0; }

/* Doc Grid */
.doc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}

/* Doc Card */
.doc-card {
  background: #fff; border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,.07); overflow: hidden;
  transition: transform .25s, box-shadow .25s;
  border: 1px solid #f1f5f9;
}
.doc-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }

.doc-card__cover {
  position: relative; height: 160px; background: linear-gradient(135deg, #6366f1, #8b5cf6);
  display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.doc-card__cover img { width: 100%; height: 100%; object-fit: cover; }
.doc-card__cover-placeholder { font-size: 3rem; opacity: .6; }

.doc-card__status {
  position: absolute; top: 10px; right: 10px;
  font-size: .7rem; font-weight: 700; padding: 3px 10px; border-radius: 10px;
}
.status-active  { background: rgba(16,185,129,.9); color: #fff; }
.status-inactive { background: rgba(239,68,68,.9); color: #fff; }
.status-archived { background: rgba(107,114,128,.9); color: #fff; }

.doc-card__body { padding: 16px; }
.doc-card__name { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.doc-card__author { font-size: .78rem; }
.doc-card__desc { font-size: .8rem; line-height: 1.4; margin-bottom: 8px; }

/* Stat Pills */
.doc-card__stats { display: flex; flex-wrap: wrap; gap: 6px; margin: 8px 0; }
.stat-pill {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: .72rem; padding: 2px 10px; border-radius: 10px;
  background: #f1f5f9; color: #475569; font-weight: 600;
}
.stat-pill i { font-size: .85em; }
.stat-pill--level { background: #ddd6fe; color: #6d28d9; }

/* Journey Bar */
.journey-bar {
  height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;
}
.journey-bar__inner { height: 100%; border-radius: 3px; }
.journey-not_started { width: 5%; background: #94a3b8; }
.journey-in_progress { width: 50%; background: linear-gradient(90deg, #3b82f6, #8b5cf6); }
.journey-completed   { width: 100%; background: linear-gradient(90deg, #10b981, #06b6d4); }

/* Action Buttons */
.doc-card__actions { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-action {
  font-size: .78rem; font-weight: 600; padding: 5px 14px; border-radius: 8px;
  border: none; cursor: pointer; text-decoration: none; display: inline-block;
  transition: all .2s;
}
.btn-action--primary { background: #6366f1; color: #fff; }
.btn-action--primary:hover { background: #4f46e5; color: #fff; }
.btn-action--edit { background: #fef3c7; color: #d97706; }
.btn-action--edit:hover { background: #fde68a; }
.btn-action--delete { background: #fee2e2; color: #dc2626; }
.btn-action--delete:hover { background: #fecaca; }

/* Primary Button */
.btn-primary-custom {
  background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
  padding: 10px 22px; border-radius: 12px; font-weight: 700; font-size: .9rem;
  text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
  transition: all .25s; box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.btn-primary-custom:hover { 
  transform: translateY(-2px); color: #fff;
  box-shadow: 0 8px 20px rgba(99,102,241,.4); 
}

/* Empty State */
.empty-state { padding: 60px 20px; }
</style>
