@extends('skills.reading.index')

@section('title', 'Quản lý Bộ lọc — Reading Analyzer')

@section('content_reading')
<div class="rf-page py-4 px-3">

  {{-- HEADER --}}
  <div class="rf-header mb-4">
    <div class="rf-header__left">
      <div class="rf-header__icon">⚙️</div>
      <div>
        <h1 class="rf-header__title">Quản lý Bộ lọc Từ vựng</h1>
        <p class="rf-header__sub">Cấu hình bộ lọc: Ngôn ngữ · Chủ đề · Danh mục · Ngữ cảnh · Từ bỏ qua</p>
      </div>
    </div>
    <div class="rf-header__actions">
      <a href="{{ route('reading.analyzer.index') }}" class="rf-btn rf-btn--ghost">
        ← Quay lại Analyzer
      </a>
      <button id="btnNewFilter" class="rf-btn rf-btn--primary">
        ➕ Thêm bộ lọc mới
      </button>
    </div>
  </div>

  {{-- FLASH --}}
  @if(session('success'))
    <div class="rf-alert rf-alert--success mb-4">
      ✅ {{ session('success') }}
    </div>
  @endif

  {{-- ═══════════════════════════════
       FORM ADD / EDIT
       ═══════════════════════════════ --}}
  <div id="filterForm" class="rf-form-card mb-4" style="display:none;">
    <div class="rf-form-card__header">
      <span id="formTitle">➕ Thêm bộ lọc mới</span>
      <button id="btnCloseForm" class="rf-close-btn">✕</button>
    </div>
    <form id="filterFormInner" method="POST" action="{{ route('reading.analyzer.filter.store') }}">
      @csrf
      <input type="hidden" name="_form_id" id="formId" value="">

      <div class="rf-form-grid">
        <div class="rf-form-group">
          <label class="rf-label">Tên bộ lọc <span class="rf-required">*</span></label>
          <input type="text" name="name" id="inputName" required maxlength="255"
                 placeholder="VD: IELTS Academic, Business English..."
                 class="rf-input">
        </div>

        <div class="rf-form-group">
          <label class="rf-label">Ngôn ngữ <span class="rf-required">*</span></label>
          <select name="language" id="inputLanguage" class="rf-select">
            <option value="en">🇬🇧 English</option>
            <option value="vi">🇻🇳 Tiếng Việt</option>
            <option value="fr">🇫🇷 French</option>
            <option value="de">🇩🇪 German</option>
            <option value="ja">🇯🇵 Japanese</option>
            <option value="ko">🇰🇷 Korean</option>
            <option value="zh">🇨🇳 Chinese</option>
          </select>
        </div>

        <div class="rf-form-group">
          <label class="rf-label">Chủ đề tài liệu</label>
          <input type="text" name="topic" id="inputTopic" maxlength="255"
                 placeholder="VD: Science, Technology, Literature..."
                 class="rf-input">
        </div>

        <div class="rf-form-group">
          <label class="rf-label">Danh mục</label>
          <input type="text" name="category" id="inputCategory" maxlength="255"
                 placeholder="VD: IELTS, TOEFL, General..."
                 class="rf-input">
        </div>

        <div class="rf-form-group">
          <label class="rf-label">Ngữ cảnh</label>
          <select name="context" id="inputContext" class="rf-select">
            <option value="">— Không chọn —</option>
            <option value="academic">Academic (Học thuật)</option>
            <option value="business">Business (Kinh doanh)</option>
            <option value="everyday">Everyday (Hàng ngày)</option>
            <option value="literature">Literature (Văn học)</option>
            <option value="science">Science (Khoa học)</option>
            <option value="technology">Technology (Công nghệ)</option>
            <option value="medical">Medical (Y tế)</option>
            <option value="legal">Legal (Pháp lý)</option>
          </select>
        </div>

        <div class="rf-form-group rf-form-group--full">
          <label class="rf-label">Danh sách từ bỏ qua</label>
          <textarea name="ignored_words" id="inputIgnored" rows="4" class="rf-textarea"
                    placeholder="Nhập các từ cần bỏ qua, phân cách bằng dấu phẩy hoặc xuống dòng&#10;VD: the, a, an, is, are, was, were, have, has, had..."></textarea>
          <div class="rf-hint">Những từ này sẽ không được đánh dấu là "từ mới" kể cả khi không có trong bảng từ vựng.</div>
        </div>

        <div class="rf-form-group rf-form-group--inline">
          <label class="rf-toggle-label">
            <input type="checkbox" name="is_active" id="inputActive" value="1" checked class="rf-toggle-input">
            <span class="rf-toggle-switch"></span>
            <span class="rf-toggle-text">Kích hoạt bộ lọc này</span>
          </label>
        </div>
      </div>

      <div class="rf-form-actions">
        <button type="submit" class="rf-btn rf-btn--primary" id="btnSubmitFilter">
          💾 Lưu bộ lọc
        </button>
        <button type="button" id="btnResetForm" class="rf-btn rf-btn--ghost">Đặt lại</button>
      </div>
    </form>
  </div>

  {{-- ═══════════════════════════════
       FILTER LIST
       ═══════════════════════════════ --}}
  @if($filters->isEmpty())
    <div class="rf-empty">
      <div class="rf-empty__icon">🎛️</div>
      <h3>Chưa có bộ lọc nào</h3>
      <p>Tạo bộ lọc đầu tiên để kiểm soát từ vựng khi phân tích tài liệu</p>
      <button class="rf-btn rf-btn--primary mt-3" id="btnEmptyNew">➕ Tạo bộ lọc đầu tiên</button>
    </div>
  @else
    <div class="rf-filter-grid">
      @foreach($filters as $filter)
        <div class="rf-filter-card {{ $filter->is_active ? 'rf-filter-card--active' : '' }}">
          <div class="rf-filter-card__header">
            <div class="rf-filter-card__name">{{ $filter->name }}</div>
            <div class="rf-filter-card__badges">
              @if($filter->is_active)
                <span class="rf-badge rf-badge--active">● Active</span>
              @endif
              <span class="rf-badge rf-badge--lang">{{ strtoupper($filter->language) }}</span>
            </div>
          </div>

          <div class="rf-filter-card__meta">
            @if($filter->topic)
              <div class="rf-meta-item">
                <span class="rf-meta-icon">📚</span>
                <span>{{ $filter->topic }}</span>
              </div>
            @endif
            @if($filter->category)
              <div class="rf-meta-item">
                <span class="rf-meta-icon">🏷️</span>
                <span>{{ $filter->category }}</span>
              </div>
            @endif
            @if($filter->context)
              <div class="rf-meta-item">
                <span class="rf-meta-icon">🌐</span>
                <span>{{ ucfirst($filter->context) }}</span>
              </div>
            @endif
          </div>

          @if(!empty($filter->ignored_words))
            <div class="rf-ignored-preview">
              <span class="rf-ignored-label">Bỏ qua:</span>
              @foreach(array_slice($filter->ignored_words, 0, 10) as $w)
                <span class="rf-ignored-tag">{{ $w }}</span>
              @endforeach
              @if(count($filter->ignored_words) > 10)
                <span class="rf-ignored-more">+{{ count($filter->ignored_words) - 10 }} từ nữa</span>
              @endif
            </div>
          @endif

          <div class="rf-filter-card__actions">
            <button class="rf-btn rf-btn--edit btn-edit-filter"
                    data-id="{{ $filter->id }}"
                    data-name="{{ $filter->name }}"
                    data-language="{{ $filter->language }}"
                    data-topic="{{ $filter->topic ?? '' }}"
                    data-category="{{ $filter->category ?? '' }}"
                    data-context="{{ $filter->context ?? '' }}"
                    data-ignored="{{ implode(', ', $filter->ignored_words ?? []) }}"
                    data-active="{{ $filter->is_active ? '1' : '0' }}">
              ✏️ Sửa
            </button>
            <form action="{{ route('reading.analyzer.filter.destroy', $filter->id) }}"
                  method="POST" style="display:inline;"
                  onsubmit="return confirm('Xóa bộ lọc « {{ $filter->name }} »?')">
              @csrf @method('DELETE')
              <button type="submit" class="rf-btn rf-btn--danger">🗑️ Xóa</button>
            </form>
          </div>
        </div>
      @endforeach

      {{-- Card thêm mới --}}
      <div class="rf-filter-card rf-filter-card--add" id="btnAddCard">
        <div class="rf-add-icon">➕</div>
        <div class="rf-add-text">Thêm bộ lọc mới</div>
      </div>
    </div>
  @endif

</div>
@endsection

<style>
/* ── Filter Page ── */
.rf-page { max-width: 1400px; font-family: 'Inter','Segoe UI',Arial,sans-serif; }

/* Header */
.rf-header {
  display:flex; justify-content:space-between; align-items:center;
  background: linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#14532d 100%);
  border-radius:20px; padding:28px 32px; gap:20px; flex-wrap:wrap;
  box-shadow:0 12px 40px rgba(15,23,42,.3);
}
.rf-header__left { display:flex; align-items:center; gap:16px; }
.rf-header__icon { font-size:2.8rem; }
.rf-header__title { font-size:1.6rem; font-weight:900; color:#fff; margin:0 0 4px; }
.rf-header__sub   { color:#94a3b8; font-size:.9rem; margin:0; }
.rf-header__actions { display:flex; gap:10px; flex-wrap:wrap; }

/* Buttons */
.rf-btn {
  padding:9px 18px; border-radius:10px; font-weight:700; font-size:.85rem;
  border:none; cursor:pointer; text-decoration:none; display:inline-block; transition:all .2s;
}
.rf-btn--primary { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.rf-btn--primary:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(99,102,241,.4); color:#fff; }
.rf-btn--ghost  { background:rgba(255,255,255,.1); color:#fff; border:1px solid rgba(255,255,255,.25); }
.rf-btn--ghost:hover { background:rgba(255,255,255,.2); color:#fff; }
.rf-btn--edit   { background:#eef2ff; color:#4f46e5; border:1px solid #c7d2fe; }
.rf-btn--edit:hover  { background:#e0e7ff; }
.rf-btn--danger { background:#fee2e2; color:#dc2626; border:none; }
.rf-btn--danger:hover { background:#fecaca; }
.mt-3 { margin-top:12px; }

/* Alert */
.rf-alert { padding:14px 18px; border-radius:10px; font-weight:600; font-size:.9rem; }
.rf-alert--success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }

/* Form Card */
.rf-form-card {
  background:#fff; border-radius:18px; padding:0;
  box-shadow:0 4px 20px rgba(0,0,0,.08); border:1px solid #e2e8f0; overflow:hidden;
}
.rf-form-card__header {
  display:flex; justify-content:space-between; align-items:center;
  padding:16px 24px; background:#f8fafc; border-bottom:1px solid #e2e8f0;
  font-size:1rem; font-weight:800; color:#1e293b;
}
.rf-close-btn { background:none; border:none; font-size:1.1rem; cursor:pointer; color:#94a3b8; transition:color .2s; }
.rf-close-btn:hover { color:#1e293b; }

.rf-form-grid {
  display:grid; grid-template-columns:1fr 1fr; gap:20px;
  padding:24px;
}
.rf-form-group { display:flex; flex-direction:column; gap:6px; }
.rf-form-group--full { grid-column:1/-1; }
.rf-form-group--inline { grid-column:1/-1; }

.rf-label { font-size:.83rem; font-weight:700; color:#374151; }
.rf-required { color:#ef4444; }
.rf-hint { font-size:.75rem; color:#94a3b8; margin-top:4px; }

.rf-input, .rf-select, .rf-textarea {
  padding:9px 12px; border-radius:8px; border:1px solid #d1d5db;
  font-size:.88rem; color:#1e293b; outline:none; transition:border .2s, box-shadow .2s;
  font-family:inherit;
}
.rf-input:focus, .rf-select:focus, .rf-textarea:focus {
  border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.15);
}
.rf-textarea { resize:vertical; min-height:100px; }

/* Toggle */
.rf-toggle-label { display:flex; align-items:center; gap:12px; cursor:pointer; }
.rf-toggle-input { display:none; }
.rf-toggle-switch {
  width:44px; height:24px; background:#d1d5db; border-radius:20px;
  position:relative; transition:background .3s;
}
.rf-toggle-switch::after {
  content:''; position:absolute; top:2px; left:2px;
  width:20px; height:20px; border-radius:50%; background:#fff;
  box-shadow:0 2px 4px rgba(0,0,0,.2); transition:left .3s;
}
.rf-toggle-input:checked + .rf-toggle-switch { background:#6366f1; }
.rf-toggle-input:checked + .rf-toggle-switch::after { left:22px; }
.rf-toggle-text { font-size:.88rem; font-weight:700; color:#374151; }

.rf-form-actions {
  display:flex; gap:10px; padding:16px 24px;
  border-top:1px solid #f1f5f9; background:#fafafa;
}

/* Filter Grid */
.rf-filter-grid {
  display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:20px;
}
.rf-filter-card {
  background:#fff; border-radius:18px; padding:22px;
  box-shadow:0 4px 16px rgba(0,0,0,.06); border:1.5px solid #f1f5f9;
  transition:all .25s; display:flex; flex-direction:column; gap:14px;
}
.rf-filter-card:hover { box-shadow:0 8px 28px rgba(0,0,0,.1); transform:translateY(-3px); }
.rf-filter-card--active { border-color:#a5b4fc; background:linear-gradient(180deg,#fafaff,#fff); }
.rf-filter-card--add {
  border-style:dashed; border-color:#c7d2fe; cursor:pointer;
  align-items:center; justify-content:center; min-height:160px;
  background:#fafaff; color:#6366f1;
}
.rf-filter-card--add:hover { background:#eef2ff; }
.rf-add-icon { font-size:2.2rem; margin-bottom:8px; }
.rf-add-text { font-weight:800; font-size:.95rem; }

.rf-filter-card__header { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; }
.rf-filter-card__name { font-size:1.05rem; font-weight:800; color:#1e293b; }
.rf-filter-card__badges { display:flex; gap:6px; flex-wrap:wrap; flex-shrink:0; }

.rf-badge { padding:2px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.rf-badge--active { background:#dcfce7; color:#15803d; }
.rf-badge--lang   { background:#ddd6fe; color:#6d28d9; }

.rf-filter-card__meta { display:flex; flex-direction:column; gap:6px; }
.rf-meta-item { display:flex; align-items:center; gap:8px; font-size:.83rem; color:#475569; }
.rf-meta-icon { font-size:1rem; }

.rf-ignored-preview {
  display:flex; flex-wrap:wrap; gap:5px; align-items:center;
  padding:10px 12px; background:#f8fafc; border-radius:10px;
}
.rf-ignored-label { font-size:.73rem; font-weight:700; color:#94a3b8; white-space:nowrap; }
.rf-ignored-tag {
  background:#f1f5f9; color:#475569; padding:2px 8px;
  border-radius:6px; font-size:.72rem; font-weight:600;
}
.rf-ignored-more { font-size:.72rem; color:#94a3b8; font-style:italic; }

.rf-filter-card__actions { display:flex; gap:8px; margin-top:auto; }

/* Empty */
.rf-empty { text-align:center; padding:80px 20px; color:#94a3b8; }
.rf-empty__icon { font-size:4rem; margin-bottom:16px; }
.rf-empty h3 { font-size:1.2rem; font-weight:800; color:#374151; margin-bottom:8px; }
.rf-empty p  { font-size:.9rem; }

@media(max-width:768px) {
  .rf-form-grid { grid-template-columns:1fr; }
  .rf-header { flex-direction:column; }
  .rf-filter-grid { grid-template-columns:1fr; }
}
</style>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const filterForm      = document.getElementById('filterForm');
  const filterFormInner = document.getElementById('filterFormInner');
  const formTitle       = document.getElementById('formTitle');
  const formId          = document.getElementById('formId');

  function openForm(mode = 'add', data = {}) {
    filterForm.style.display = 'block';
    filterForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    if (mode === 'edit') {
      formTitle.textContent = '✏️ Chỉnh sửa bộ lọc';
      filterFormInner.action = `/reading-analyzer/filters/${data.id}/update`;
      formId.value = data.id;
      document.getElementById('inputName').value     = data.name || '';
      document.getElementById('inputLanguage').value = data.language || 'en';
      document.getElementById('inputTopic').value    = data.topic || '';
      document.getElementById('inputCategory').value = data.category || '';
      document.getElementById('inputContext').value  = data.context || '';
      document.getElementById('inputIgnored').value  = data.ignored || '';
      document.getElementById('inputActive').checked = data.active === '1';
    } else {
      formTitle.textContent = '➕ Thêm bộ lọc mới';
      filterFormInner.action = '{{ route("reading.analyzer.filter.store") }}';
      formId.value = '';
      filterFormInner.reset();
      document.getElementById('inputActive').checked = true;
    }
  }

  function closeForm() {
    filterForm.style.display = 'none';
  }

  // Open buttons
  document.getElementById('btnNewFilter')?.addEventListener('click', () => openForm('add'));
  document.getElementById('btnEmptyNew')?.addEventListener('click', () => openForm('add'));
  document.getElementById('btnAddCard')?.addEventListener('click', () => openForm('add'));
  document.getElementById('btnCloseForm')?.addEventListener('click', closeForm);
  document.getElementById('btnResetForm')?.addEventListener('click', () => filterFormInner.reset());

  // Edit buttons
  document.querySelectorAll('.btn-edit-filter').forEach(btn => {
    btn.addEventListener('click', () => {
      openForm('edit', {
        id:       btn.dataset.id,
        name:     btn.dataset.name,
        language: btn.dataset.language,
        topic:    btn.dataset.topic,
        category: btn.dataset.category,
        context:  btn.dataset.context,
        ignored:  btn.dataset.ignored,
        active:   btn.dataset.active,
      });
    });
  });

});
</script>
@endpush
