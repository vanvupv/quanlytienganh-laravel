@extends('skills.reading.index')

@section('title', 'English Reading Analyzer')

@section('content_reading')
<div class="ra-page py-4 px-3">

  {{-- ═══════════════════════════════
       HEADER
       ═══════════════════════════════ --}}
  <div class="ra-header mb-4">
    <div class="ra-header__left">
      <div class="ra-header__icon">📖</div>
      <div>
        <h1 class="ra-header__title">English Reading Analyzer</h1>
        <p class="ra-header__sub">Phân tích tài liệu PDF, DOCX, TXT — đánh dấu từ vựng mới &amp; lặp lại</p>
      </div>
    </div>
    <div class="ra-header__actions">
      <a href="{{ route('reading.analyzer.filter.index') }}" class="ra-btn ra-btn--ghost">
        ⚙️ Quản lý Bộ lọc
      </a>
      <button id="btnUploadTrigger" class="ra-btn ra-btn--primary">
        📤 Upload File
      </button>
    </div>
  </div>

  {{-- ═══════════════════════════════
       UPLOAD ZONE (hidden by default)
       ═══════════════════════════════ --}}
  <div id="uploadZone" class="ra-upload-zone mb-4" style="display:none;">
    <form id="uploadForm" enctype="multipart/form-data">
      @csrf
      <div class="ra-drop-area" id="dropArea">
        <div class="ra-drop-icon">📂</div>
        <div class="ra-drop-text">Kéo thả file vào đây hoặc <label for="fileInput" class="ra-drop-link">chọn file</label></div>
        <div class="ra-drop-hint">Hỗ trợ: PDF, DOCX, TXT — Tối đa 10MB</div>
        <input type="file" id="fileInput" name="document" accept=".pdf,.docx,.doc,.txt" style="display:none;">
      </div>

      <div class="ra-filter-row mt-3">
        <label class="ra-label">Áp dụng bộ lọc:</label>
        <select name="filter_id" id="filterSelect" class="ra-select">
          <option value="">— Mặc định (bộ lọc active) —</option>
          @foreach($filters as $f)
            <option value="{{ $f->id }}" {{ $f->is_active ? 'selected' : '' }}>
              {{ $f->name }}
              @if($f->is_active) ✓ @endif
            </option>
          @endforeach
        </select>
        <button type="submit" class="ra-btn ra-btn--primary ml-2" id="btnAnalyze" disabled>
          🔍 Phân tích
        </button>
        <button type="button" id="btnCancelUpload" class="ra-btn ra-btn--ghost ml-2">Hủy</button>
      </div>

      <div id="fileInfo" class="ra-file-info" style="display:none;">
        <span id="fileName"></span>
        <span id="fileSize" class="ra-badge"></span>
      </div>
    </form>
  </div>

  {{-- Loading state --}}
  <div id="loadingState" class="ra-loading" style="display:none;">
    <div class="ra-spinner"></div>
    <p>Đang phân tích tài liệu...</p>
  </div>

  {{-- ═══════════════════════════════
       STATS GRID
       ═══════════════════════════════ --}}
  <div id="statsGrid" class="ra-stats-grid mb-4" style="display:none;">
    <div class="ra-stat-card ra-stat--blue">
      <span>Tổng từ</span>
      <strong id="statTotal">0</strong>
    </div>
    <div class="ra-stat-card ra-stat--green">
      <span>Từ mới</span>
      <strong id="statNew">0</strong>
    </div>
    <div class="ra-stat-card ra-stat--purple">
      <span>Từ đã biết</span>
      <strong id="statKnown">0</strong>
    </div>
    <div class="ra-stat-card ra-stat--orange">
      <span>Tỷ lệ hiểu</span>
      <strong id="statPct">0%</strong>
    </div>
  </div>

  {{-- ═══════════════════════════════
       MAIN LAYOUT: Sidebar + Document
       ═══════════════════════════════ --}}
  <div id="readerLayout" class="ra-layout mb-5" style="display:none;">

    {{-- SIDEBAR: Danh sách từ mới --}}
    <aside class="ra-sidebar" id="raSidebar">
      <div class="ra-sidebar__header">
        <div class="ra-sidebar__title">✨ Từ mới (<span id="sidebarCount">0</span>)</div>
        <input type="text" id="sidebarSearch" placeholder="Tìm từ..." class="ra-sidebar__search">
      </div>
      <div id="sidebarList" class="ra-sidebar__list"></div>
    </aside>

    {{-- DOCUMENT VIEWER --}}
    <main class="ra-doc-viewer">
      <div class="ra-toolbar">
        <button id="btnZoomIn" class="ra-tool-btn">A+</button>
        <button id="btnZoomOut" class="ra-tool-btn">A-</button>
        <button id="btnToggleNew" class="ra-tool-btn ra-tool-btn--active" data-active="true">
          🟢 Từ mới
        </button>
        <button id="btnToggleRepeat" class="ra-tool-btn ra-tool-btn--active" data-active="true">
          🟤 Từ lặp
        </button>
        <div class="ra-toolbar__spacer"></div>
        <span class="ra-toolbar__info" id="docFileName"></span>
      </div>
      <div id="docContent" class="ra-doc-content"></div>
    </main>

  </div>

  {{-- ═══════════════════════════════
       SUMMARY TABLE
       ═══════════════════════════════ --}}
  <div id="summarySection" class="ra-summary" style="display:none;">
    <div class="ra-summary__header">
      <h2>📊 Vocabulary Summary</h2>
      <div class="ra-summary__actions">
        <button id="btnExportPdf" class="ra-btn ra-btn--ghost ra-btn--sm dark">📥 Xuất PDF</button>
        <button id="btnExportDocx" class="ra-btn ra-btn--ghost ra-btn--sm dark">📝 Tải File Word</button>
        <button id="btnExportExcel" class="ra-btn ra-btn--ghost ra-btn--sm dark">📊 Tải File Excel (CSV)</button>
        <button id="btnExportSummary" class="ra-btn ra-btn--ghost ra-btn--sm dark">📋 Copy danh sách</button>
        <button id="btnSaveSelected" class="ra-btn ra-btn--save ra-btn--sm" disabled>
          💾 Lưu từ đã chọn <span id="saveCount" class="ra-save-badge">0</span>
        </button>
      </div>
    </div>
    <div class="ra-table-wrap">
      <table id="summaryTable">
        <thead>
          <tr>
            <th style="width:40px;">
              <input type="checkbox" id="checkAll" title="Chọn tất cả" class="ra-check">
            </th>
            <th>#</th>
            <th>Từ gốc</th>
            <th>Biến thể (lần đầu)</th>
            <th>Dòng đầu</th>
            <th>Tần suất</th>
          </tr>
        </thead>
        <tbody id="summaryBody"></tbody>
      </table>
    </div>
  </div>

  {{-- EMPTY STATE --}}
  <div id="emptyState" class="ra-empty">
    <div class="ra-empty__icon">📄</div>
    <h3>Chưa có tài liệu nào được phân tích</h3>
    <p>Upload file PDF, DOCX hoặc TXT để bắt đầu phân tích từ vựng</p>
    <button class="ra-btn ra-btn--primary mt-3" id="btnEmptyUpload">📤 Upload File ngay</button>
  </div>

  {{-- ═══════════════════════════════
       HISTORY PANEL
       ═══════════════════════════════ --}}
  <div id="historyPanel" class="ra-history mb-5" style="display:none;">
    <div class="ra-history__header">
      <div class="ra-history__title">🕐 Lịch sử phân tích</div>
      <span class="ra-history__count" id="historyCount">0 file</span>
    </div>
    <div id="historyList" class="ra-history__list"></div>
  </div>

</div>{{-- /ra-page --}}

{{-- ═══════════════════════════════
     SAVE VOCABULARY MODAL
     ═══════════════════════════════ --}}
<div id="saveModal" class="ra-modal-overlay" style="display:none;">
  <div class="ra-modal">
    <div class="ra-modal__header">
      <div class="ra-modal__title">💾 Lưu từ vựng vào Bộ sưu tập</div>
      <button id="btnCloseModal" class="ra-modal__close">✕</button>
    </div>
    <div class="ra-modal__body">
      <p class="ra-modal__hint">Điền nghĩa &amp; loại từ để lưu đầy đủ — hoặc để trống để chỉ lưu từ gốc.</p>
      <div id="modalWordList" class="ra-modal-word-list"></div>
    </div>
    <div class="ra-modal__footer">
      <button id="btnModalCancel" class="ra-btn ra-btn--ghost dark">Hủy</button>
      <button id="btnModalSave" class="ra-btn ra-btn--save">
        <span id="modalSavingSpinner" style="display:none;">⏳ Đang lưu...</span>
        <span id="modalSaveLabel">💾 Lưu tất cả</span>
      </button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════
     PRINT AREA (ẩn trên screen, hiện khi print)
     ═══════════════════════════════ --}}
<div id="printArea" class="print-area">
  <div class="print-header">
    <div class="print-logo">📖 English Reading Analyzer</div>
    <div class="print-meta">
      <div id="printFileName" class="print-filename"></div>
      <div id="printDate" class="print-date"></div>
    </div>
  </div>

  <div class="print-stats">
    <div class="print-stat">
      <span class="print-stat__label">Tổng từ</span>
      <span class="print-stat__val" id="pStatTotal">0</span>
    </div>
    <div class="print-stat print-stat--green">
      <span class="print-stat__label">Từ mới</span>
      <span class="print-stat__val" id="pStatNew">0</span>
    </div>
    <div class="print-stat print-stat--blue">
      <span class="print-stat__label">Đã biết</span>
      <span class="print-stat__val" id="pStatKnown">0</span>
    </div>
    <div class="print-stat print-stat--orange">
      <span class="print-stat__label">Tỷ lệ hiểu</span>
      <span class="print-stat__val" id="pStatPct">0%</span>
    </div>
  </div>

  <h2 class="print-section-title">📋 Danh sách từ vựng mới</h2>
  <table class="print-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Từ gốc</th>
        <th>Biến thể (lần đầu)</th>
        <th>Dòng đầu</th>
        <th>Tần suất</th>
      </tr>
    </thead>
    <tbody id="printSummaryBody"></tbody>
  </table>

  <div id="printDocSection">
    <h2 class="print-section-title">📄 Nội dung tài liệu</h2>
    <div id="printDocContent" class="print-doc-content"></div>
  </div>

  <div class="print-footer">
    Tạo bởi English Reading Analyzer · <span id="printFooterDate"></span>
  </div>
</div>

@endsection


<style>
/* ====================================================
   READING ANALYZER STYLES
   ==================================================== */
.ra-page { max-width: 1600px; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; }

/* ── Header ── */
.ra-header {
  display: flex; justify-content: space-between; align-items: center;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2d5c 100%);
  border-radius: 20px; padding: 28px 32px; gap: 20px; flex-wrap: wrap;
  box-shadow: 0 12px 40px rgba(15,23,42,.3);
}
.ra-header__left { display: flex; align-items: center; gap: 16px; }
.ra-header__icon { font-size: 2.8rem; }
.ra-header__title { font-size: 1.7rem; font-weight: 900; color: #fff; margin: 0 0 4px; }
.ra-header__sub   { color: #94a3b8; font-size: .9rem; margin: 0; }
.ra-header__actions { display: flex; gap: 10px; flex-wrap: wrap; }

/* ── Buttons ── */
.ra-btn {
  padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: .88rem;
  border: none; cursor: pointer; text-decoration: none; display: inline-block; transition: all .2s;
}
.ra-btn--primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
.ra-btn--primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,.4); color:#fff; }
.ra-btn--primary:disabled { opacity:.5; cursor:not-allowed; transform:none; box-shadow:none; }
.ra-btn--ghost { background: rgba(255,255,255,.1); color: #fff; border: 1px solid rgba(255,255,255,.25); }
.ra-btn--ghost:hover { background: rgba(255,255,255,.2); color:#fff; }
.ra-btn--save {
  background: linear-gradient(135deg, #10b981, #059669); color: #fff;
  box-shadow: 0 4px 12px rgba(16,185,129,.3);
}
.ra-btn--save:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,.45); }
.ra-btn--save:disabled { opacity:.45; cursor:not-allowed; transform:none; box-shadow:none; }
.ra-btn--sm { padding: 6px 14px; font-size: .78rem; }
.ra-btn--ghost.dark { background: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0; }
.ra-btn--ghost.dark:hover { background: #e2e8f0; color: #1e293b; }
.ml-2 { margin-left: 8px; }
.mt-3 { margin-top: 12px; }

/* ── Save badge ── */
.ra-save-badge {
  display: inline-flex; align-items: center; justify-content: center;
  background: rgba(255,255,255,.25); color: #fff;
  width: 20px; height: 20px; border-radius: 50%;
  font-size: .72rem; font-weight: 800; margin-left: 6px;
}

/* ── Upload Zone ── */
.ra-upload-zone {
  background: #fff; border-radius: 18px; padding: 28px;
  box-shadow: 0 4px 20px rgba(0,0,0,.07); border: 1px solid #e2e8f0;
}
.ra-drop-area {
  border: 2.5px dashed #c7d2fe; border-radius: 14px; padding: 48px 24px;
  text-align: center; background: #f8faff; cursor: pointer; transition: all .2s;
}
.ra-drop-area.drag-over { border-color: #6366f1; background: #eef2ff; }
.ra-drop-icon { font-size: 3rem; margin-bottom: 12px; }
.ra-drop-text { font-size: 1.05rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
.ra-drop-link { color: #6366f1; cursor: pointer; text-decoration: underline; }
.ra-drop-hint { font-size: .82rem; color: #94a3b8; }

.ra-filter-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.ra-label { font-size: .88rem; font-weight: 700; color: #374151; white-space: nowrap; }
.ra-select {
  flex: 1; min-width: 180px; padding: 8px 12px; border-radius: 8px;
  border: 1px solid #d1d5db; font-size: .88rem; color: #1e293b;
}

.ra-file-info {
  display: flex; align-items: center; gap: 10px; margin-top: 12px;
  padding: 10px 16px; background: #f0fdf4; border-radius: 10px;
  font-size: .88rem; font-weight: 600; color: #15803d;
}
.ra-badge {
  background: #dcfce7; color: #15803d; padding: 2px 10px;
  border-radius: 20px; font-size: .75rem; font-weight: 700;
}

/* ── Loading ── */
.ra-loading {
  text-align: center; padding: 80px 20px; color: #6366f1;
}
.ra-spinner {
  width: 52px; height: 52px; border: 4px solid #e0e7ff;
  border-top-color: #6366f1; border-radius: 50%; margin: 0 auto 20px;
  animation: spin .8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Stats Grid ── */
.ra-stats-grid {
  display: grid; grid-template-columns: repeat(4,1fr); gap: 16px;
}
.ra-stat-card {
  border-radius: 16px; padding: 20px; text-align: center; color: #fff;
  box-shadow: 0 6px 20px rgba(0,0,0,.1); transition: transform .2s;
}
.ra-stat-card:hover { transform: translateY(-4px); }
.ra-stat-card span { display: block; font-size: .78rem; font-weight: 600; opacity: .85; margin-bottom: 6px; }
.ra-stat-card strong { font-size: 2.2rem; font-weight: 900; }
.ra-stat--blue   { background: linear-gradient(135deg,#3b82f6,#6366f1); }
.ra-stat--green  { background: linear-gradient(135deg,#10b981,#06b6d4); }
.ra-stat--purple { background: linear-gradient(135deg,#8b5cf6,#a855f7); }
.ra-stat--orange { background: linear-gradient(135deg,#f59e0b,#ef4444); }

/* ── Layout ── */
.ra-layout {
  display: grid; grid-template-columns: 300px 1fr; gap: 24px;
}

/* ── Sidebar ── */
.ra-sidebar {
  background: #fff; border-radius: 20px; overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,.07); border: 1px solid #e2e8f0;
  display: flex; flex-direction: column; max-height: 800px;
  position: sticky; top: 20px;
}
.ra-sidebar__header { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; }
.ra-sidebar__title { font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 10px; }
.ra-sidebar__search {
  width: 100%; padding: 7px 12px; border-radius: 8px;
  border: 1px solid #e2e8f0; font-size: .83rem; color: #374151; outline: none;
}
.ra-sidebar__search:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.ra-sidebar__list { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 8px; }

.ra-word-item {
  background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
  padding: 8px 12px; cursor: pointer; transition: all .2s;
  display: flex; align-items: center; gap: 8px;
}
.ra-word-item:hover { background: #dcfce7; transform: translateX(4px); border-color: #86efac; }
.ra-word-item__text { flex: 1; min-width: 0; }
.ra-word-item__word { font-size: .9rem; font-weight: 700; color: #15803d; }
.ra-word-item__line { font-size: .72rem; color: #6b7280; margin-top: 2px; }
.ra-word-item__save {
  flex-shrink: 0; background: none; border: 1px solid #bbf7d0;
  border-radius: 6px; padding: 3px 7px; font-size: .72rem; cursor: pointer;
  color: #059669; transition: all .15s; white-space: nowrap;
}
.ra-word-item__save:hover { background: #059669; color: #fff; border-color: #059669; }
.ra-word-item__save.saved { background: #d1fae5; color: #065f46; border-color: #6ee7b7; cursor: default; }

/* ── Document Viewer ── */
.ra-doc-viewer {
  background: #fff; border-radius: 20px; overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,.07); border: 1px solid #e2e8f0;
  display: flex; flex-direction: column;
}
.ra-toolbar {
  display: flex; align-items: center; gap: 10px; padding: 14px 20px;
  border-bottom: 1px solid #f1f5f9; flex-wrap: wrap;
}
.ra-tool-btn {
  padding: 7px 14px; border-radius: 8px; border: 1px solid #e2e8f0;
  background: #f8fafc; font-size: .82rem; font-weight: 700; cursor: pointer; transition: all .2s;
  color: #374151;
}
.ra-tool-btn:hover { background: #eef2ff; border-color: #c7d2fe; }
.ra-tool-btn--active { background: #eef2ff; border-color: #a5b4fc; color: #4f46e5; }
.ra-toolbar__spacer { flex: 1; }
.ra-toolbar__info { font-size: .78rem; color: #94a3b8; font-weight: 600; }

.ra-doc-content {
  padding: 36px 40px; font-size: 1.1rem; line-height: 2;
  overflow-y: auto; max-height: 700px; flex: 1;
}
.ra-doc-line { margin-bottom: 6px; position: relative; }
.ra-line-num {
  display: inline-block; width: 36px; color: #cbd5e1; font-size: .72rem;
  font-weight: 700; vertical-align: middle; user-select: none; flex-shrink: 0;
}

/* ── Word Highlights ── */
.word-new {
  background: #dcfce7; color: #15803d; font-weight: 700;
  padding: 1px 5px; border-radius: 5px; cursor: default;
  transition: background .2s;
}
.word-new:hover { background: #bbf7d0; }

.word-repeat {
  background: #fef3c7; color: #92400e; font-weight: 700;
  position: relative; cursor: pointer; padding: 1px 5px; border-radius: 5px;
  transition: background .2s; text-decoration: underline dotted #d97706;
}
.word-repeat:hover { background: #fde68a; }
.word-repeat:hover::after {
  content: attr(data-tooltip);
  position: absolute; left: 50%; transform: translateX(-50%);
  bottom: 130%; background: #1f2937; color: #fff;
  padding: 6px 12px; border-radius: 8px; white-space: nowrap;
  font-size: .75rem; font-weight: 600; z-index: 999;
  box-shadow: 0 4px 16px rgba(0,0,0,.2);
  pointer-events: none;
}
.word-repeat:hover::before {
  content: '';
  position: absolute; left: 50%; transform: translateX(-50%);
  bottom: 118%; border: 6px solid transparent;
  border-top-color: #1f2937; z-index: 999;
}

.word-new.hidden-highlight, .word-repeat.hidden-highlight {
  background: transparent; color: inherit; font-weight: inherit;
  padding: 0; text-decoration: none;
}

/* ── Summary ── */
.ra-summary {
  background: #fff; border-radius: 20px; padding: 28px 30px;
  box-shadow: 0 4px 20px rgba(0,0,0,.07); border: 1px solid #e2e8f0;
}
.ra-summary__header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
  flex-wrap: wrap; gap: 12px;
}
.ra-summary__header h2 { font-size: 1.2rem; font-weight: 800; color: #1e293b; margin: 0; }
.ra-summary__actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.ra-table-wrap { overflow-x: auto; }

/* ── Checkbox ── */
.ra-check {
  width: 16px; height: 16px; cursor: pointer; accent-color: #10b981;
}
.ra-row-checked { background: #f0fdf4 !important; }

table { width: 100%; border-collapse: collapse; }
th, td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; text-align: left; font-size: .88rem; }
th { background: #f8fafc; font-size: .8rem; font-weight: 800; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
tbody tr:hover { background: #f8faff; }
td:first-child { color: #94a3b8; font-size: .75rem; }

.ra-root-word { font-weight: 800; color: #1e293b; }
.ra-variant   { color: #6366f1; font-style: italic; }
.ra-freq-badge {
  display: inline-block; background: #ddd6fe; color: #6d28d9;
  padding: 2px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700;
}
.ra-jump-link {
  display: inline-block; background: #e0f2fe; color: #0369a1;
  padding: 2px 9px; border-radius: 6px; font-size: .73rem; font-weight: 700;
  cursor: pointer; text-decoration: none; transition: background .2s;
}
.ra-jump-link:hover { background: #bae6fd; color: #0c4a6e; }

/* ── Empty state ── */
.ra-empty {
  text-align: center; padding: 80px 20px; color: #94a3b8;
}
.ra-empty__icon { font-size: 4rem; margin-bottom: 16px; }
.ra-empty h3 { font-size: 1.2rem; font-weight: 800; color: #374151; margin-bottom: 8px; }
.ra-empty p  { font-size: .9rem; }

/* ── Save Modal ── */
.ra-modal-overlay {
  position: fixed; inset: 0; background: rgba(15,23,42,.55);
  z-index: 9999; display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(4px); padding: 20px;
  animation: fadeIn .15s ease;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.ra-modal {
  background: #fff; border-radius: 22px; width: 100%; max-width: 760px;
  max-height: 85vh; display: flex; flex-direction: column;
  box-shadow: 0 24px 60px rgba(15,23,42,.35);
  animation: slideUp .2s ease;
}
@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: none; opacity: 1; } }
.ra-modal__header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 22px 28px; border-bottom: 1px solid #f1f5f9;
}
.ra-modal__title { font-size: 1.15rem; font-weight: 900; color: #1e293b; }
.ra-modal__close {
  background: #f1f5f9; border: none; border-radius: 8px; width: 32px; height: 32px;
  cursor: pointer; font-size: 1rem; color: #64748b; transition: all .15s;
}
.ra-modal__close:hover { background: #fee2e2; color: #dc2626; }
.ra-modal__body { flex: 1; overflow-y: auto; padding: 20px 28px; }
.ra-modal__hint { font-size: .83rem; color: #64748b; margin-bottom: 16px; }
.ra-modal__footer {
  display: flex; gap: 10px; justify-content: flex-end;
  padding: 18px 28px; border-top: 1px solid #f1f5f9;
}
.ra-modal-word-list { display: flex; flex-direction: column; gap: 12px; }
.ra-modal-word-row {
  display: grid; grid-template-columns: 160px 1fr 140px;
  gap: 12px; align-items: center;
  padding: 14px 16px; background: #f8fafc; border-radius: 12px;
  border: 1px solid #e2e8f0;
}
.ra-modal-word-label {
  font-weight: 800; color: #15803d; font-size: .95rem;
  display: flex; align-items: center; gap: 6px;
}
.ra-modal-word-label span { font-size: .72rem; color: #94a3b8; font-weight: 500; }
.ra-modal-input {
  padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db;
  font-size: .85rem; color: #1e293b; width: 100%; outline: none;
  transition: border-color .15s;
}
.ra-modal-input:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
.ra-modal-select {
  padding: 8px 10px; border-radius: 8px; border: 1px solid #d1d5db;
  font-size: .85rem; color: #1e293b; width: 100%; outline: none; cursor: pointer;
}

/* ── Toast Notification ── */
.ra-toast {
  position: fixed; bottom: 28px; right: 28px; z-index: 10000;
  background: #1e293b; color: #fff; padding: 14px 22px; border-radius: 14px;
  font-size: .88rem; font-weight: 700; box-shadow: 0 8px 32px rgba(0,0,0,.25);
  animation: toastIn .25s ease; max-width: 380px;
}
.ra-toast--success { border-left: 4px solid #10b981; }
.ra-toast--error   { border-left: 4px solid #ef4444; }
@keyframes toastIn { from { transform: translateY(20px); opacity: 0; } to { transform: none; opacity: 1; } }

/* Responsive */
@media (max-width: 1024px) {
  .ra-layout { grid-template-columns: 260px 1fr; }
  .ra-stats-grid { grid-template-columns: repeat(2,1fr); }
  .ra-modal-word-row { grid-template-columns: 120px 1fr 120px; }
}
@media (max-width: 768px) {
  .ra-layout { grid-template-columns: 1fr; }
  .ra-doc-content { padding: 20px; }
  .ra-header { flex-direction: column; }
  .ra-modal-word-row { grid-template-columns: 1fr; }
}
</style>

<style>
/* ── History Panel ── */
.ra-history {
  background: #fff;
  border-radius: 18px;
  border: 1.5px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0,0,0,.06);
  overflow: hidden;
}
.ra-history__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  background: linear-gradient(135deg, #0f172a, #1e3a5f);
  color: #fff;
}
.ra-history__title {
  font-size: 1rem;
  font-weight: 800;
  color: #fff;
}
.ra-history__count {
  background: rgba(255,255,255,.15);
  color: #e2e8f0;
  padding: 3px 12px;
  border-radius: 20px;
  font-size: .78rem;
  font-weight: 700;
}
.ra-history__list { padding: 16px; display: flex; flex-direction: column; gap: 10px; }

.ra-hist-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 18px;
  border-radius: 12px;
  border: 1.5px solid #f1f5f9;
  background: #fafafa;
  transition: all .2s;
}
.ra-hist-card:hover { border-color: #c7d2fe; background: #fafaff; box-shadow: 0 4px 14px rgba(99,102,241,.08); }

.ra-hist-icon {
  font-size: 1.8rem;
  flex-shrink: 0;
  width: 44px;
  text-align: center;
}
.ra-hist-body { flex: 1; min-width: 0; }
.ra-hist-name {
  font-size: .92rem;
  font-weight: 700;
  color: #1e293b;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 380px;
}
.ra-hist-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 5px;
  align-items: center;
}
.ra-hist-badge {
  padding: 2px 9px;
  border-radius: 20px;
  font-size: .72rem;
  font-weight: 700;
}
.ra-hist-badge--filter { background: #ddd6fe; color: #6d28d9; }
.ra-hist-badge--new    { background: #dcfce7; color: #15803d; }
.ra-hist-badge--known  { background: #eff6ff; color: #1d4ed8; }
.ra-hist-badge--pct    { background: #fef3c7; color: #92400e; }
.ra-hist-time {
  font-size: .73rem;
  color: #94a3b8;
  white-space: nowrap;
}
.ra-hist-actions { flex-shrink: 0; }
.ra-hist-del {
  background: #fee2e2;
  color: #dc2626;
  border: none;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: .78rem;
  font-weight: 700;
  cursor: pointer;
  transition: background .2s;
}
.ra-hist-del:hover { background: #fecaca; }
.ra-history-empty {
  text-align: center;
  padding: 32px 20px;
  color: #94a3b8;
  font-size: .9rem;
}

/* ── Print Area (screen: hidden) ── */
.print-area { display: none; }


@media print {
  /* ── Reset nền trang ── */
  html, body {
    background: #fff !important;
    margin: 0 !important;
    padding: 0 !important;
  }


  /* ── Ẩn TẤT CẢ bằng visibility, giữ layout ── */
  body * {
    visibility: hidden !important;
  }

  /* ── Chỉ hiện print-area và con của nó ── */
  #printArea,
  #printArea * {
    visibility: visible !important;
  }

  /* ── Đưa print-area về vị trí đầu trang ── */
  #printArea {
    display: block !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    background: #fff !important;
    padding: 0 !important;
    margin: 0 !important;
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 11pt;
    color: #000;
    z-index: 99999;
  }

  /* Header đầu trang */
  .print-header {
    display: flex !important;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2.5px solid #1e293b;
    padding-bottom: 10px;
    margin-bottom: 16px;
  }
  .print-logo { font-size: 16pt; font-weight: 900; color: #0f172a; }
  .print-filename { font-size: 10pt; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
  .print-date { font-size: 9pt; color: #64748b; }

  /* Stats */
  .print-stats {
    display: flex !important;
    gap: 12px;
    margin-bottom: 18px;
  }
  .print-stat {
    flex: 1;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    text-align: center;
    background: #f8fafc;
  }
  .print-stat--green { border-color: #4ade80 !important; background: #f0fdf4 !important; }
  .print-stat--blue  { border-color: #60a5fa !important; background: #eff6ff !important; }
  .print-stat--orange{ border-color: #fb923c !important; background: #fff7ed !important; }
  .print-stat__label { display: block; font-size: 8pt; color: #64748b; margin-bottom: 3px; }
  .print-stat__val   { display: block; font-size: 16pt; font-weight: 900; color: #0f172a; }

  /* Section titles */
  .print-section-title {
    font-size: 12pt;
    font-weight: 800;
    color: #1e293b;
    border-left: 4px solid #6366f1;
    padding-left: 10px;
    margin: 18px 0 10px;
  }

  /* Vocabulary table */
  .print-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9.5pt;
  }
  .print-table th {
    background: #1e293b !important;
    color: #fff !important;
    padding: 6px 10px;
    text-align: left;
    font-size: 8.5pt;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  .print-table td {
    padding: 5px 10px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
  }
  .print-table tr:nth-child(even) td {
    background: #f8fafc !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  .print-table tr { page-break-inside: avoid; }

  /* Document content */
  .print-doc-content {
    font-size: 9.5pt;
    line-height: 1.65;
    margin-top: 8px;
  }
  .print-doc-content .word-new {
    background: #dcfce7 !important;
    color: #15803d !important;
    border-radius: 2px;
    padding: 0 2px;
    font-weight: 600;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  .print-doc-content .word-repeat {
    background: #fef3c7 !important;
    color: #92400e !important;
    border-radius: 2px;
    padding: 0 2px;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  .print-doc-content .ra-line-num {
    color: #94a3b8;
    font-size: 8pt;
    margin-right: 8px;
  }
  .print-doc-content .ra-doc-line { margin-bottom: 4px; }

  /* Footer */
  .print-footer {
    margin-top: 24px;
    border-top: 1px solid #e2e8f0;
    padding-top: 8px;
    font-size: 8pt;
    color: #94a3b8;
    text-align: center;
  }

  @page {
    margin: 15mm 18mm;
    size: A4;
  }
}

</style>

@push('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

  // ── State ──────────────────────────────────────────────────
  let currentFontSize = 1.1;
  let showNew    = true;
  let showRepeat = true;
  let analysisData = null;
  let selectedWords = new Set();   // set of root words selected for saving
  let currentFileName = '';

  const SAVE_URL    = '{{ route("reading.analyzer.saveWords") }}';
  const HISTORY_URL = '{{ route("reading.analyzer.history") }}';
  const EXPORT_DOCX_URL = '{{ route("reading.analyzer.exportDocx") }}';
  const EXPORT_CSV_URL  = '{{ route("reading.analyzer.exportCsv") }}';
  const CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';


  // ── Elements ───────────────────────────────────────────────
  const uploadZone      = document.getElementById('uploadZone');
  const emptyState      = document.getElementById('emptyState');
  const statsGrid       = document.getElementById('statsGrid');
  const readerLayout    = document.getElementById('readerLayout');
  const summarySection  = document.getElementById('summarySection');
  const loadingState    = document.getElementById('loadingState');
  const fileInput       = document.getElementById('fileInput');
  const fileInfo        = document.getElementById('fileInfo');
  const fileName        = document.getElementById('fileName');
  const fileSize        = document.getElementById('fileSize');
  const dropArea        = document.getElementById('dropArea');
  const uploadForm      = document.getElementById('uploadForm');
  const btnAnalyze      = document.getElementById('btnAnalyze');
  const docContent      = document.getElementById('docContent');
  const sidebarList     = document.getElementById('sidebarList');
  const sidebarSearch   = document.getElementById('sidebarSearch');
  const sidebarCount    = document.getElementById('sidebarCount');
  const summaryBody     = document.getElementById('summaryBody');
  const docFileName     = document.getElementById('docFileName');
  const btnSaveSelected = document.getElementById('btnSaveSelected');
  const btnExportDocx   = document.getElementById('btnExportDocx');
  const btnExportExcel  = document.getElementById('btnExportExcel');
  const saveCountBadge  = document.getElementById('saveCount');
  const checkAll        = document.getElementById('checkAll');
  const saveModal       = document.getElementById('saveModal');
  const modalWordList   = document.getElementById('modalWordList');

  // ── Upload Toggle ──────────────────────────────────────────
  document.getElementById('btnUploadTrigger').addEventListener('click', () => showUploadZone());
  document.getElementById('btnEmptyUpload').addEventListener('click', () => showUploadZone());
  document.getElementById('btnCancelUpload').addEventListener('click', () => hideUploadZone());

  function showUploadZone() {
    uploadZone.style.display = 'block';
    emptyState.style.display = 'none';
    uploadZone.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function hideUploadZone() {
    uploadZone.style.display = 'none';
    if (!analysisData) emptyState.style.display = 'block';
  }

  // ── File Selection ─────────────────────────────────────────
  dropArea.addEventListener('click', () => fileInput.click());
  dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('drag-over'); });
  dropArea.addEventListener('dragleave', () => dropArea.classList.remove('drag-over'));
  dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('drag-over');
    if (e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      handleFileSelect(e.dataTransfer.files[0]);
    }
  });

  fileInput.addEventListener('change', () => {
    if (fileInput.files.length) handleFileSelect(fileInput.files[0]);
  });

  function handleFileSelect(file) {
    const allowed = ['pdf','docx','doc','txt'];
    const ext = file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
      alert('Chỉ hỗ trợ file PDF, DOCX, DOC, TXT!');
      return;
    }
    fileName.textContent = '📄 ' + file.name;
    fileSize.textContent  = formatSize(file.size);
    fileInfo.style.display = 'flex';
    btnAnalyze.disabled = false;
  }

  function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
  }

  // ── Form Submit (Analyze) ─────────────────────────────────
  uploadForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    if (!fileInput.files.length) return;

    hideUploadZone();
    loadingState.style.display = 'block';
    statsGrid.style.display    = 'none';
    readerLayout.style.display = 'none';
    summarySection.style.display = 'none';
    selectedWords.clear();
    updateSaveButton();

    const formData = new FormData(this);
    try {
      const resp = await fetch('{{ route("reading.analyzer.upload") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
      });
      const data = await resp.json();
      if (data.error) { alert('Lỗi: ' + data.error); loadingState.style.display='none'; emptyState.style.display='block'; return; }
      analysisData = data;
      renderAnalysis(data, fileInput.files[0].name);
      loadHistory(); // Refresh lịch sử sau khi phân tích

    } catch (err) {
      alert('Có lỗi xảy ra: ' + err.message);
      loadingState.style.display = 'none';
      emptyState.style.display = 'block';
    }
  });

  // ── Render Analysis ────────────────────────────────────────
  function renderAnalysis(data, fname) {
    loadingState.style.display = 'none';
    currentFileName = fname;

    // Stats
    document.getElementById('statTotal').textContent = data.stats.total_words.toLocaleString();
    document.getElementById('statNew').textContent   = data.stats.new_count.toLocaleString();
    document.getElementById('statKnown').textContent = data.stats.known_count.toLocaleString();
    document.getElementById('statPct').textContent   = data.stats.comprehension + '%';
    statsGrid.style.display = 'grid';

    docFileName.textContent = '📄 ' + fname;

    // Sidebar
    sidebarList.innerHTML = '';
    sidebarCount.textContent = data.new_words.length;
    data.new_words.forEach(w => {
      const item = document.createElement('div');
      item.className = 'ra-word-item';
      item.dataset.word = w.word.toLowerCase();

      const textDiv = document.createElement('div');
      textDiv.className = 'ra-word-item__text';
      textDiv.innerHTML = `<div class="ra-word-item__word">${escHtml(w.word)}</div>
        <div class="ra-word-item__line">Dòng ${w.line} · lần đầu xuất hiện</div>`;

      const saveBtn = document.createElement('button');
      saveBtn.className = 'ra-word-item__save';
      saveBtn.textContent = '💾';
      saveBtn.title = 'Lưu nhanh từ này';
      saveBtn.dataset.word = w.word.toLowerCase();
      saveBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        quickSaveWord(w.word.toLowerCase(), saveBtn);
      });

      item.addEventListener('click', () => {
        const anchor = document.getElementById(w.anchor_id);
        if (anchor) anchor.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });

      item.appendChild(textDiv);
      item.appendChild(saveBtn);
      sidebarList.appendChild(item);
    });

    // Document content
    docContent.innerHTML = '';
    const fragment = document.createDocumentFragment();
    data.lines.forEach(line => {
      const lineEl = document.createElement('div');
      lineEl.className = 'ra-doc-line';

      const numEl = document.createElement('span');
      numEl.className = 'ra-line-num';
      numEl.textContent = line.line_num;
      lineEl.appendChild(numEl);

      const textWrap = document.createElement('span');
      line.tokens.forEach(token => {
        if (token.type === 'punctuation' || token.type === 'space') {
          textWrap.insertAdjacentText('beforeend', token.text);
        } else if (token.class === 'new') {
          const span = document.createElement('span');
          span.className = 'word-new';
          span.id = token.anchor_id;
          span.textContent = token.text;
          textWrap.appendChild(span);
        } else if (token.class === 'repeat') {
          const span = document.createElement('span');
          span.className = 'word-repeat';
          span.dataset.tooltip = `↑ Từ gốc: "${token.root}" (dòng ${token.first_line})`;
          span.dataset.anchorId = token.anchor_id;
          span.textContent = token.text;
          span.addEventListener('click', () => {
            const anchor = document.getElementById(token.anchor_id);
            if (anchor) anchor.scrollIntoView({ behavior: 'smooth', block: 'center' });
          });
          textWrap.appendChild(span);
        } else {
          textWrap.insertAdjacentText('beforeend', token.text);
        }
      });
      lineEl.appendChild(textWrap);
      fragment.appendChild(lineEl);
    });
    docContent.appendChild(fragment);

    // Summary table with checkboxes
    summaryBody.innerHTML = '';
    data.summary.forEach((item, idx) => {
      const tr = document.createElement('tr');
      tr.dataset.root = item.root;
      tr.innerHTML = `
        <td>
          <input type="checkbox" class="ra-check row-check" data-root="${escHtml(item.root)}" title="Chọn ${escHtml(item.root)}">
        </td>
        <td>${idx + 1}</td>
        <td class="ra-root-word">${escHtml(item.root)}</td>
        <td class="ra-variant">${escHtml(item.variant)}</td>
        <td>
          <a class="ra-jump-link" onclick="document.getElementById('${item.anchor_id}')?.scrollIntoView({behavior:'smooth',block:'center'})">
            Dòng ${item.line}
          </a>
        </td>
        <td><span class="ra-freq-badge">${item.frequency}×</span></td>
      `;
      summaryBody.appendChild(tr);
    });

    // Attach checkbox listeners
    summaryBody.querySelectorAll('.row-check').forEach(cb => {
      cb.addEventListener('change', () => onRowCheckChange(cb));
    });

    // Show sections
    readerLayout.style.display  = 'grid';
    summarySection.style.display = 'block';
    emptyState.style.display = 'none';
    document.getElementById('historyPanel').style.display = 'none'; // ẩn history khi đang xem tài liệu
  }


  // ── Checkbox logic ─────────────────────────────────────────
  function onRowCheckChange(cb) {
    const root = cb.dataset.root;
    const tr   = cb.closest('tr');
    if (cb.checked) {
      selectedWords.add(root);
      tr.classList.add('ra-row-checked');
    } else {
      selectedWords.delete(root);
      tr.classList.remove('ra-row-checked');
    }
    updateCheckAll();
    updateSaveButton();
  }

  checkAll.addEventListener('change', () => {
    const checked = checkAll.checked;
    summaryBody.querySelectorAll('.row-check').forEach(cb => {
      cb.checked = checked;
      const root = cb.dataset.root;
      const tr   = cb.closest('tr');
      if (checked) {
        selectedWords.add(root);
        tr.classList.add('ra-row-checked');
      } else {
        selectedWords.delete(root);
        tr.classList.remove('ra-row-checked');
      }
    });
    updateSaveButton();
  });

  function updateCheckAll() {
    const allCbs = summaryBody.querySelectorAll('.row-check');
    const checkedCbs = summaryBody.querySelectorAll('.row-check:checked');
    checkAll.indeterminate = checkedCbs.length > 0 && checkedCbs.length < allCbs.length;
    checkAll.checked = checkedCbs.length > 0 && checkedCbs.length === allCbs.length;
  }

  function updateSaveButton() {
    const n = selectedWords.size;
    saveCountBadge.textContent = n;
    btnSaveSelected.disabled = n === 0;
  }

  // ── Open Save Modal ────────────────────────────────────────
  btnSaveSelected.addEventListener('click', () => openSaveModal([...selectedWords]));

  function openSaveModal(words) {
    if (!words.length) return;
    modalWordList.innerHTML = '';

    words.forEach(root => {
      const row = document.createElement('div');
      row.className = 'ra-modal-word-row';
      row.innerHTML = `
        <div class="ra-modal-word-label">
          📗 ${escHtml(root)}
          <span>từ gốc</span>
        </div>
        <input type="text" class="ra-modal-input" placeholder="Nghĩa tiếng Việt (tùy chọn)"
               data-word="${escHtml(root)}" data-field="meaning" autocomplete="off">
        <select class="ra-modal-select" data-word="${escHtml(root)}" data-field="pos">
          <option value="">Loại từ...</option>
          <option value="Noun">Noun — Danh từ</option>
          <option value="Verb">Verb — Động từ</option>
          <option value="Adjective">Adjective — Tính từ</option>
          <option value="Adverb">Adverb — Trạng từ</option>
          <option value="Phrase">Phrase — Cụm từ</option>
          <option value="Other">Other — Khác</option>
        </select>
      `;
      modalWordList.appendChild(row);
    });

    saveModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Focus first input
    setTimeout(() => modalWordList.querySelector('.ra-modal-input')?.focus(), 50);
  }

  function closeSaveModal() {
    saveModal.style.display = 'none';
    document.body.style.overflow = '';
  }

  document.getElementById('btnCloseModal').addEventListener('click', closeSaveModal);
  document.getElementById('btnModalCancel').addEventListener('click', closeSaveModal);
  saveModal.addEventListener('click', e => { if (e.target === saveModal) closeSaveModal(); });

  // ── Submit Save ────────────────────────────────────────────
  document.getElementById('btnModalSave').addEventListener('click', async () => {
    const words = [];
    modalWordList.querySelectorAll('.ra-modal-word-row').forEach(row => {
      const wordKey = row.querySelector('[data-field="meaning"]').dataset.word;
      const meaning = row.querySelector('[data-field="meaning"]').value.trim();
      const pos     = row.querySelector('[data-field="pos"]').value;
      words.push({ word: wordKey, meaning, part_of_speech: pos || '' });
    });

    if (!words.length) return;

    // Show spinner
    document.getElementById('modalSaveLabel').style.display = 'none';
    document.getElementById('modalSavingSpinner').style.display = 'inline';
    document.getElementById('btnModalSave').disabled = true;
    document.getElementById('btnModalCancel').disabled = true;

    try {
      const resp = await fetch(SAVE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ words, source_document: currentFileName }),
      });
      const result = await resp.json();

      closeSaveModal();

      if (result.success) {
        showToast(result.message, 'success');
        // Mark saved words in sidebar
        words.forEach(w => markWordSavedInSidebar(w.word));
        // Uncheck saved rows
        summaryBody.querySelectorAll('.row-check:checked').forEach(cb => {
          cb.checked = false;
          cb.closest('tr').classList.remove('ra-row-checked');
        });
        selectedWords.clear();
        updateCheckAll();
        updateSaveButton();
      } else {
        showToast('❌ Có lỗi xảy ra khi lưu.', 'error');
      }
    } catch (err) {
      closeSaveModal();
      showToast('❌ Lỗi kết nối: ' + err.message, 'error');
    }
  });

  // ── Quick Save (Sidebar button) ───────────────────────────
  async function quickSaveWord(word, btn) {
    if (btn.classList.contains('saved')) return;
    btn.textContent = '⏳';
    btn.disabled = true;

    try {
      const resp = await fetch(SAVE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          words: [{ word, meaning: '', part_of_speech: '' }],
          source_document: currentFileName
        }),
      });
      const result = await resp.json();

      if (result.success) {
        btn.textContent = '✅';
        btn.classList.add('saved');
        btn.title = 'Đã lưu';
        showToast(result.skipped > 0 ? `"${word}" đã có trong từ vựng.` : `✅ Đã lưu "${word}"!`, result.skipped > 0 ? 'error' : 'success');
      } else {
        btn.textContent = '❌';
        btn.disabled = false;
        showToast('Lỗi khi lưu từ.', 'error');
      }
    } catch (err) {
      btn.textContent = '💾';
      btn.disabled = false;
      showToast('Lỗi kết nối.', 'error');
    }
  }

  function markWordSavedInSidebar(word) {
    sidebarList.querySelectorAll('.ra-word-item__save').forEach(btn => {
      if (btn.dataset.word === word) {
        btn.textContent = '✅';
        btn.classList.add('saved');
        btn.title = 'Đã lưu';
      }
    });
  }

  // ── Sidebar Search ─────────────────────────────────────────
  sidebarSearch.addEventListener('input', () => {
    const q = sidebarSearch.value.toLowerCase().trim();
    sidebarList.querySelectorAll('.ra-word-item').forEach(item => {
      item.style.display = (!q || item.dataset.word.includes(q)) ? '' : 'none';
    });
  });

  // ── Toolbar: Zoom ─────────────────────────────────────────
  document.getElementById('btnZoomIn').addEventListener('click', () => {
    currentFontSize = Math.min(2, currentFontSize + 0.1);
    docContent.style.fontSize = currentFontSize + 'rem';
  });
  document.getElementById('btnZoomOut').addEventListener('click', () => {
    currentFontSize = Math.max(0.7, currentFontSize - 0.1);
    docContent.style.fontSize = currentFontSize + 'rem';
  });

  // ── Toolbar: Toggle Highlights ────────────────────────────
  document.getElementById('btnToggleNew').addEventListener('click', function () {
    showNew = !showNew;
    this.dataset.active = showNew;
    this.classList.toggle('ra-tool-btn--active', showNew);
    docContent.querySelectorAll('.word-new').forEach(el => {
      el.classList.toggle('hidden-highlight', !showNew);
    });
  });

  document.getElementById('btnToggleRepeat').addEventListener('click', function () {
    showRepeat = !showRepeat;
    this.dataset.active = showRepeat;
    this.classList.toggle('ra-tool-btn--active', showRepeat);
    docContent.querySelectorAll('.word-repeat').forEach(el => {
      el.classList.toggle('hidden-highlight', !showRepeat);
    });
  });

  // ── Export Summary ────────────────────────────────────────
  document.getElementById('btnExportSummary').addEventListener('click', () => {
    if (!analysisData) return;
    const lines = analysisData.summary.map((item, i) =>
      `${i+1}. ${item.root} (${item.variant}) — ${item.frequency} lần — Dòng ${item.line}`
    );
    navigator.clipboard.writeText(lines.join('\n'))
      .then(() => showToast('✅ Đã copy danh sách từ vựng!', 'success'))
      .catch(() => alert('Không thể copy, thử thủ công nhé.'));
  });

  // ── Toast ─────────────────────────────────────────────────
  let toastTimer = null;
  function showToast(msg, type = 'success') {
    const existing = document.querySelector('.ra-toast');
    if (existing) existing.remove();
    clearTimeout(toastTimer);

    const toast = document.createElement('div');
    toast.className = `ra-toast ra-toast--${type}`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    toastTimer = setTimeout(() => toast.remove(), 3500);
  }

  // ── Helpers ───────────────────────────────────────────────
  function escHtml(str) {
    return String(str)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;');
  }

  // ── History ───────────────────────────────────────────────
  async function loadHistory() {
    try {
      const resp = await fetch(HISTORY_URL, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
      });
      const json = await resp.json();
      renderHistory(json.data || []);
    } catch (e) {
      console.warn('Không thể tải lịch sử:', e);
    }
  }

  function renderHistory(items) {
    const panel = document.getElementById('historyPanel');
    const list  = document.getElementById('historyList');
    const count = document.getElementById('historyCount');

    if (!items.length) {
      panel.style.display = 'none';
      return;
    }

    // Chỉ hiện history khi không đang xem kết quả phân tích
    if (!analysisData) panel.style.display = 'block';

    count.textContent = items.length + ' file';
    list.innerHTML = '';

    items.forEach(item => {
      const card = document.createElement('div');
      card.className = 'ra-hist-card';
      card.innerHTML = `
        <div class="ra-hist-icon">${escHtml(item.file_icon)}</div>
        <div class="ra-hist-body">
          <div class="ra-hist-name" title="${escHtml(item.file_name)}">${escHtml(item.file_name)}</div>
          <div class="ra-hist-meta">
            ${item.filter_name ? `<span class="ra-hist-badge ra-hist-badge--filter">⚙️ ${escHtml(item.filter_name)}</span>` : '<span class="ra-hist-badge ra-hist-badge--filter">⚙️ Mặc định</span>'}
            <span class="ra-hist-badge ra-hist-badge--new">✨ ${item.new_count} từ mới</span>
            <span class="ra-hist-badge ra-hist-badge--known">📚 ${item.known_count} đã biết</span>
            <span class="ra-hist-badge ra-hist-badge--pct">🎯 ${item.comprehension_pct}%</span>
            <span class="ra-hist-time" title="${escHtml(item.created_at_human)}">🕐 ${escHtml(item.created_at_diff)}</span>
          </div>
        </div>
        <div class="ra-hist-actions">
          <button class="ra-hist-del" data-id="${item.id}" title="Xóa lịch sử này">🗑️</button>
        </div>
      `;
      card.querySelector('.ra-hist-del').addEventListener('click', async (e) => {
        e.stopPropagation();
        const id = e.currentTarget.dataset.id;
        if (!confirm('Xóa lịch sử phân tích này?')) return;
        try {
          const r = await fetch(`/reading-analyzer/history/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
          });
          const res = await r.json();
          if (res.success) {
            card.style.opacity = '0';
            setTimeout(() => { card.remove(); loadHistory(); }, 300);
          }
        } catch (err) {
          showToast('Lỗi khi xóa lịch sử.', 'error');
        }
      });
      list.appendChild(card);
    });
  }

  // Load lịch sử ngay khi trang khởi động
  loadHistory();

  // ── Export PDF ────────────────────────────────────────────
  document.getElementById('btnExportPdf').addEventListener('click', function () {
    if (!analysisData) return;

    const now = new Date();
    const dateStr = now.toLocaleDateString('vi-VN', {
      day: '2-digit', month: '2-digit', year: 'numeric',
      hour: '2-digit', minute: '2-digit'
    });

    // Điền dữ liệu vào print area
    document.getElementById('pStatTotal').textContent  = document.getElementById('statTotal').textContent;
    document.getElementById('pStatNew').textContent    = document.getElementById('statNew').textContent;
    document.getElementById('pStatKnown').textContent  = document.getElementById('statKnown').textContent;
    document.getElementById('pStatPct').textContent    = document.getElementById('statPct').textContent;
    document.getElementById('printFileName').textContent = '📄 ' + currentFileName;
    document.getElementById('printDate').textContent   = 'Phân tích lúc: ' + dateStr;
    document.getElementById('printFooterDate').textContent = dateStr;

    // Copy bảng tổng kết (bỏ cột checkbox)
    const srcRows = document.querySelectorAll('#summaryBody tr');
    const printBody = document.getElementById('printSummaryBody');
    printBody.innerHTML = '';
    srcRows.forEach(row => {
      const cells = row.querySelectorAll('td');
      if (!cells.length) return;
      const tr = document.createElement('tr');
      // Bỏ cột checkbox (cells[0]), giữ #, từ gốc, biến thể, dòng, tần suất
      [1, 2, 3, 4, 5].forEach(i => {
        if (cells[i]) {
          const td = document.createElement('td');
          td.textContent = cells[i].textContent.trim();
          tr.appendChild(td);
        }
      });
      printBody.appendChild(tr);
    });

    // Copy nội dung tài liệu đã highlight
    document.getElementById('printDocContent').innerHTML =
      document.getElementById('docContent').innerHTML;

    window.print();
  });

  // ── Export DOCX ───────────────────────────────────────────
  btnExportDocx.addEventListener('click', async function () {
    if (!analysisData) return;
    const originalText = this.textContent;
    this.textContent = '⏳ Đang tạo...';
    this.disabled = true;

    try {
      await downloadFile(EXPORT_DOCX_URL, {
        file_name: currentFileName,
        lines: analysisData.lines,
        summary: analysisData.summary
      }, currentFileName.replace(/\.[^/.]+$/, "") + "_highlighted.docx");
    } finally {
      this.textContent = originalText;
      this.disabled = false;
    }
  });

  // ── Export Excel (CSV) ────────────────────────────────────
  btnExportExcel.addEventListener('click', async function () {
    if (!analysisData) return;
    const originalText = this.textContent;
    this.textContent = '⏳ Đang tạo...';
    this.disabled = true;

    try {
      await downloadFile(EXPORT_CSV_URL, {
        file_name: currentFileName,
        summary: analysisData.summary
      }, currentFileName.replace(/\.[^/.]+$/, "") + "_new_words.csv");
    } finally {
      this.textContent = originalText;
      this.disabled = false;
    }
  });

  // Helper tải tệp Blob từ POST request
  async function downloadFile(url, payload, defaultFilename) {
    try {
      const resp = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      if (!resp.ok) {
        throw new Error('Lỗi từ máy chủ: ' + resp.statusText);
      }

      const blob = await resp.blob();
      const urlBlob = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = urlBlob;
      a.download = defaultFilename;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(urlBlob);
      a.remove();
    } catch (error) {
      alert('Không thể tải tệp: ' + error.message);
    }
  }

});
</script>
@endpush
