@extends('skills.listening.index')

@section('title', (isset($document) ? 'Sửa Tài liệu' : 'Thêm Tài liệu Mới'))

@section('content_listening')
<div class="doc-form-page py-4 px-3">

  {{-- Breadcrumb --}}
  <nav class="breadcrumb-custom mb-3">
    <a href="{{ route('document.index') }}">📚 Tài liệu</a>
    <span class="sep">›</span>
    <span>{{ isset($document) ? 'Sửa: ' . $document->name : 'Thêm Tài liệu Mới' }}</span>
  </nav>

  <div class="doc-form-card">
    <div class="doc-form-card__header">
      <div class="header-icon">{{ isset($document) ? '✏️' : '📖' }}</div>
      <div>
        <h3 class="mb-1">{{ isset($document) ? 'Sửa Tài liệu' : 'Thêm Tài liệu Listening Mới' }}</h3>
        <p class="text-white-50 mb-0 small">Điền đầy đủ 6 nhóm thông tin của tài liệu</p>
      </div>
    </div>

    <form action="{{ isset($document) ? route('document.update', $document->id) : route('document.store') }}"
          method="POST" enctype="multipart/form-data" class="doc-form-body">
      @csrf
      @if(isset($document)) @method('PUT') @endif

      @if($errors->any())
        <div class="alert alert-danger mb-4">
          <strong>⚠️ Vui lòng kiểm tra lại:</strong>
          <ul class="mb-0 mt-2 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- ════════════════════════════════
           NHÓM 1: Thông tin cơ bản
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">①</span> Thông tin cơ bản
        </div>
        <div class="form-grid">
          <div class="form-group form-group--full">
            <label class="form-label" for="name">Tên tài liệu <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-input"
                   value="{{ old('name', $document->name ?? '') }}"
                   placeholder="VD: Headway 5e Elementary" required>
          </div>
          <div class="form-group form-group--full">
            <label class="form-label" for="description">Mô tả ngắn</label>
            <textarea id="description" name="description" class="form-input form-textarea" rows="2"
                      placeholder="Mô tả sơ lược nội dung tài liệu...">{{ old('description', $document->description ?? '') }}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="skill">Kỹ năng</label>
            <select id="skill" name="skill" class="form-input">
              <option value="">— Chọn —</option>
              @foreach(['listening' => '🎧 Listening', 'reading' => '📖 Reading', 'speaking' => '🗣️ Speaking', 'writing' => '✍️ Writing'] as $val => $label)
                <option value="{{ $val }}" {{ old('skill', $document->skill ?? 'listening') === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="level">Cấp độ</label>
            <select id="level" name="level" class="form-input">
              <option value="">— Chọn cấp độ —</option>
              @foreach(['A1','A2','B1','B2','C1','C2'] as $lv)
                <option value="{{ $lv }}" {{ old('level', $document->level ?? '') === $lv ? 'selected' : '' }}>{{ $lv }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="proficiency">Trình độ</label>
            <select id="proficiency" name="proficiency" class="form-input">
              <option value="">— Chọn —</option>
              @foreach(['Beginner','Elementary','Pre-Intermediate','Intermediate','Upper-Intermediate','Advanced'] as $p)
                <option value="{{ $p }}" {{ old('proficiency', $document->proficiency ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- ════════════════════════════════
           NHÓM 2: Thông tin xuất bản
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">②</span> Thông tin xuất bản
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="author">Tác giả</label>
            <input type="text" id="author" name="author" class="form-input"
                   value="{{ old('author', $document->author ?? '') }}"
                   placeholder="VD: John Soars">
          </div>
          <div class="form-group">
            <label class="form-label" for="publisher">Nhà xuất bản</label>
            <input type="text" id="publisher" name="publisher" class="form-input"
                   value="{{ old('publisher', $document->publisher ?? '') }}"
                   placeholder="VD: Oxford University Press">
          </div>
          <div class="form-group">
            <label class="form-label" for="published_year">Năm xuất bản</label>
            <input type="number" id="published_year" name="published_year" class="form-input"
                   value="{{ old('published_year', $document->published_year ?? '') }}"
                   min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
          </div>
          <div class="form-group">
            <label class="form-label" for="version">Phiên bản</label>
            <input type="text" id="version" name="version" class="form-input"
                   value="{{ old('version', $document->version ?? '') }}"
                   placeholder="VD: 5th Edition">
          </div>
          <div class="form-group">
            <label class="form-label" for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" class="form-input"
                   value="{{ old('isbn', $document->isbn ?? '') }}"
                   placeholder="VD: 978-0-19-476136-3">
          </div>
          <div class="form-group">
            <label class="form-label" for="language">Ngôn ngữ</label>
            <select id="language" name="language" class="form-input">
              <option value="en" {{ old('language', $document->language ?? 'en') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
              <option value="vi" {{ old('language', $document->language ?? '') === 'vi' ? 'selected' : '' }}>🇻🇳 Tiếng Việt</option>
              <option value="fr" {{ old('language', $document->language ?? '') === 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
              <option value="de" {{ old('language', $document->language ?? '') === 'de' ? 'selected' : '' }}>🇩🇪 German</option>
            </select>
          </div>
        </div>
      </div>

      {{-- ════════════════════════════════
           NHÓM 3: Thông tin vật lý & File
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">③</span> Thông tin vật lý &amp; File
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="cover_image_file">Ảnh bìa (upload)</label>
            @if(isset($document) && $document->cover_image)
              <div class="cover-preview mb-2">
                <img src="{{ asset('storage/' . $document->cover_image) }}" alt="Ảnh bìa" style="height:80px;border-radius:8px;">
              </div>
            @endif
            <input type="file" id="cover_image_file" name="cover_image_file" class="form-input"
                   accept="image/*" onchange="previewCover(this)">
            <div id="coverPreviewWrap" class="mt-2" style="display:none">
              <img id="coverPreview" src="" alt="Preview" style="height:80px;border-radius:8px;">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="page_count">Số trang</label>
            <input type="number" id="page_count" name="page_count" class="form-input"
                   value="{{ old('page_count', $document->page_count ?? '') }}"
                   min="0" placeholder="VD: 144">
          </div>
          <div class="form-group">
            <label class="form-label" for="dimensions">Kích thước</label>
            <input type="text" id="dimensions" name="dimensions" class="form-input"
                   value="{{ old('dimensions', $document->dimensions ?? '') }}"
                   placeholder="VD: 21x29.7 cm">
          </div>
          <div class="form-group">
            <label class="form-label" for="file_size">Dung lượng</label>
            <input type="text" id="file_size" name="file_size" class="form-input"
                   value="{{ old('file_size', $document->file_size ?? '') }}"
                   placeholder="VD: 350 MB">
          </div>
          <div class="form-group">
            <label class="form-label" for="file_format">Định dạng File</label>
            <input type="text" id="file_format" name="file_format" class="form-input"
                   value="{{ old('file_format', $document->file_format ?? '') }}"
                   placeholder="VD: PDF + MP3">
          </div>
          <div class="form-group">
            <label class="form-label" for="total_lessons">Tổng số bài học</label>
            <input type="number" id="total_lessons" name="total_lessons" class="form-input"
                   value="{{ old('total_lessons', $document->total_lessons ?? '') }}"
                   min="0" placeholder="VD: 48">
          </div>
        </div>
      </div>

      {{-- ════════════════════════════════
           NHÓM 4: Trạng thái & Quản lý
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">④</span> Trạng thái &amp; Quản lý
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="status">Trạng thái</label>
            <select id="status" name="status" class="form-input">
              <option value="active" {{ old('status', $document->status ?? 'active') === 'active' ? 'selected' : '' }}>✅ Đang sử dụng</option>
              <option value="inactive" {{ old('status', $document->status ?? '') === 'inactive' ? 'selected' : '' }}>⏸️ Tạm dừng</option>
              <option value="archived" {{ old('status', $document->status ?? '') === 'archived' ? 'selected' : '' }}>📦 Lưu trữ</option>
            </select>
          </div>
        </div>
      </div>

      {{-- ════════════════════════════════
           NHÓM 5: Cấu trúc kiến thức
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">⑤</span> Cấu trúc kiến thức
        </div>
        <div class="form-grid">
          <div class="form-group form-group--full">
            <label class="form-label" for="overview">Tổng quan nội dung</label>
            <textarea id="overview" name="overview" class="form-input form-textarea" rows="3"
                      placeholder="Mô tả tổng quan cấu trúc và nội dung chính của tài liệu...">{{ old('overview', $document->overview ?? '') }}</textarea>
          </div>
        </div>

        {{-- Dynamic Chapter List --}}
        <div class="chapter-builder mt-3">
          <label class="form-label mb-2">📑 Danh sách các Chương</label>
          <div id="chapterList">
            @if(isset($document) && $document->parts->count())
              @foreach($document->parts->sortBy('order') as $part)
                <div class="chapter-row">
                  <span class="chapter-order">{{ $loop->iteration }}</span>
                  <input type="text" name="chapters[]" class="form-input chapter-input"
                         value="{{ $part->name }}" placeholder="Tên chương...">
                  <button type="button" class="btn-rm-chapter" onclick="removeChapter(this)">×</button>
                </div>
              @endforeach
            @else
              <div class="chapter-row">
                <span class="chapter-order">1</span>
                <input type="text" name="chapters[]" class="form-input chapter-input"
                       placeholder="VD: Unit 1 — Getting to know you">
                <button type="button" class="btn-rm-chapter" onclick="removeChapter(this)">×</button>
              </div>
            @endif
          </div>
          <button type="button" class="btn-add-chapter mt-2" onclick="addChapter()">
            + Thêm chương
          </button>
        </div>
      </div>

      {{-- ════════════════════════════════
           NHÓM 6: Hành trình học
           ════════════════════════════════ --}}
      <div class="form-section">
        <div class="form-section__label">
          <span class="section-num">⑥</span> Hành trình học
        </div>
        <div class="form-grid">
          <div class="form-group form-group--full">
            <label class="form-label" for="learning_objective">Mục tiêu học tập</label>
            <textarea id="learning_objective" name="learning_objective" class="form-input form-textarea" rows="2"
                      placeholder="VD: Hoàn thành toàn bộ 12 Unit, nắm vững từ vựng A2–B1...">{{ old('learning_objective', $document->learning_objective ?? '') }}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="study_duration">Thời gian học / ngày (phút)</label>
            <input type="number" id="study_duration" name="study_duration" class="form-input"
                   value="{{ old('study_duration', $document->study_duration ?? '') }}"
                   min="0" placeholder="VD: 30">
          </div>
          <div class="form-group">
            <label class="form-label" for="review_duration">Thời gian ôn / ngày (phút)</label>
            <input type="number" id="review_duration" name="review_duration" class="form-input"
                   value="{{ old('review_duration', $document->review_duration ?? '') }}"
                   min="0" placeholder="VD: 15">
          </div>
          <div class="form-group form-group--full">
            <label class="form-label" for="assessment">Phương pháp đánh giá</label>
            <textarea id="assessment" name="assessment" class="form-input form-textarea" rows="2"
                      placeholder="VD: Nghe và viết lại transcript; đạt 80% trở lên là qua...">{{ old('assessment', $document->assessment ?? '') }}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="min_score">Tiêu chí điểm tối thiểu (%)</label>
            <input type="number" id="min_score" name="completion_criteria[min_score]" class="form-input"
                   value="{{ old('completion_criteria.min_score', ($document->completion_criteria['min_score'] ?? '')) }}"
                   min="0" max="100" step="5" placeholder="VD: 80">
          </div>
          <div class="form-group">
            <label class="form-label" for="min_lessons">Tiêu chí số bài tối thiểu</label>
            <input type="number" id="min_lessons" name="completion_criteria[min_lessons]" class="form-input"
                   value="{{ old('completion_criteria.min_lessons', ($document->completion_criteria['min_lessons'] ?? '')) }}"
                   min="0" placeholder="VD: 10">
          </div>
          <div class="form-group">
            <label class="form-label" for="journey_status">Trạng thái hành trình</label>
            <select id="journey_status" name="journey_status" class="form-input">
              <option value="not_started" {{ old('journey_status', $document->journey_status ?? 'not_started') === 'not_started' ? 'selected' : '' }}>⏳ Chưa bắt đầu</option>
              <option value="in_progress" {{ old('journey_status', $document->journey_status ?? '') === 'in_progress' ? 'selected' : '' }}>📖 Đang học</option>
              <option value="completed"   {{ old('journey_status', $document->journey_status ?? '') === 'completed' ? 'selected' : '' }}>✅ Hoàn thành</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Đạt / Hoàn thành</label>
            <div class="toggle-wrap">
              <label class="toggle-switch">
                <input type="checkbox" name="is_passed" value="1"
                       {{ old('is_passed', $document->is_passed ?? false) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
              <span class="toggle-label">Đánh dấu là Đạt 🏆</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="form-actions">
        <a href="{{ route('document.index') }}" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-submit">
          {{ isset($document) ? '💾 Lưu thay đổi' : '📖 Thêm Tài liệu' }}
        </button>
      </div>

    </form>
  </div>

</div>
@endsection

@push('script')
<script>
// Preview ảnh bìa
function previewCover(input) {
  const wrap = document.getElementById('coverPreviewWrap');
  const img  = document.getElementById('coverPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}

// Chapter management
let chapterCount = document.querySelectorAll('.chapter-row').length;

function addChapter() {
  chapterCount++;
  const list = document.getElementById('chapterList');
  const row = document.createElement('div');
  row.className = 'chapter-row';
  row.innerHTML = `
    <span class="chapter-order">${chapterCount}</span>
    <input type="text" name="chapters[]" class="form-input chapter-input"
           placeholder="VD: Unit ${chapterCount} — Tên chương">
    <button type="button" class="btn-rm-chapter" onclick="removeChapter(this)">×</button>
  `;
  list.appendChild(row);
  row.querySelector('input').focus();
}

function removeChapter(btn) {
  const row = btn.closest('.chapter-row');
  if (document.querySelectorAll('.chapter-row').length <= 1) return;
  row.remove();
  // Re-number
  document.querySelectorAll('.chapter-order').forEach((el, i) => { el.textContent = i + 1; });
  chapterCount = document.querySelectorAll('.chapter-row').length;
}
</script>
@endpush

<style>
/* ====================================================
   DOCUMENT FORM STYLES
   ==================================================== */
.doc-form-page { max-width: 960px; }

/* Form Card */
.doc-form-card {
  background: #fff; border-radius: 20px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.09); border: 1px solid #f1f5f9;
}
.doc-form-card__header {
  background: linear-gradient(135deg, #1e293b 0%, #334155 60%, #1e3a5f 100%);
  padding: 28px 32px; display: flex; align-items: center; gap: 18px; color: #fff;
}
.doc-form-card__header .header-icon { font-size: 2.4rem; }
.doc-form-card__header h3 { margin: 0; font-size: 1.3rem; font-weight: 800; }

.doc-form-body { padding: 32px; }

/* Form Section */
.form-section {
  margin-bottom: 28px; padding-bottom: 28px;
  border-bottom: 1px dashed #e2e8f0;
}
.form-section:last-of-type { border-bottom: none; }
.form-section__label {
  display: flex; align-items: center; gap: 10px;
  font-size: .9rem; font-weight: 800; color: #1e293b; margin-bottom: 16px;
}
.section-num {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px; border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
  font-size: .85rem; font-weight: 900; flex-shrink: 0;
}

/* Grid */
.form-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
}
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group--full { grid-column: 1 / -1; }

.form-label { font-size: .8rem; font-weight: 700; color: #374151; }
.form-input {
  padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 10px;
  font-size: .9rem; color: #1e293b; background: #fff;
  transition: border-color .2s, box-shadow .2s; outline: none; width: 100%;
}
.form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.form-textarea { resize: vertical; min-height: 70px; }

/* Chapter Builder */
.chapter-builder { background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; }
.chapter-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.chapter-order {
  flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%;
  background: #e0e7ff; color: #4f46e5; font-size: .78rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
}
.chapter-input { flex: 1; }
.btn-rm-chapter {
  flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%;
  border: none; background: #fee2e2; color: #dc2626; font-size: 1.1rem;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  line-height: 1; transition: all .2s;
}
.btn-rm-chapter:hover { background: #dc2626; color: #fff; }
.btn-add-chapter {
  padding: 7px 18px; border-radius: 10px; border: 2px dashed #c7d2fe;
  background: #eef2ff; color: #4f46e5; font-weight: 700; font-size: .82rem;
  cursor: pointer; transition: all .2s; width: 100%;
}
.btn-add-chapter:hover { background: #e0e7ff; border-color: #6366f1; }

/* Toggle Switch */
.toggle-wrap { display: flex; align-items: center; gap: 10px; padding: 10px 0; }
.toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
  position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
  background: #e2e8f0; border-radius: 24px; transition: .3s;
}
.toggle-slider:before {
  position: absolute; content: ''; width: 18px; height: 18px; left: 3px; bottom: 3px;
  background: #fff; border-radius: 50%; transition: .3s;
  box-shadow: 0 2px 4px rgba(0,0,0,.15);
}
input:checked + .toggle-slider { background: #6366f1; }
input:checked + .toggle-slider:before { transform: translateX(20px); }
.toggle-label { font-size: .85rem; color: #374151; font-weight: 500; }

/* Actions */
.form-actions {
  display: flex; justify-content: flex-end; gap: 12px;
  padding-top: 24px; border-top: 2px solid #f1f5f9; margin-top: 8px;
}
.btn-cancel {
  padding: 11px 28px; border-radius: 12px; border: 2px solid #e2e8f0;
  color: #64748b; font-weight: 700; text-decoration: none; display: inline-block;
  transition: all .2s; font-size: .92rem;
}
.btn-cancel:hover { border-color: #94a3b8; color: #1e293b; }
.btn-submit {
  padding: 11px 32px; border-radius: 12px; border: none;
  background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
  font-weight: 800; font-size: .95rem; cursor: pointer;
  transition: all .25s; box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99,102,241,.45); }

/* Breadcrumb */
.breadcrumb-custom { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #64748b; }
.breadcrumb-custom a { color: #6366f1; text-decoration: none; font-weight: 600; }
.breadcrumb-custom a:hover { text-decoration: underline; }
.breadcrumb-custom .sep { color: #cbd5e1; }

/* Responsive */
@media (max-width: 640px) {
  .form-grid { grid-template-columns: 1fr; }
  .doc-form-body { padding: 20px; }
  .doc-form-card__header { padding: 20px; }
}
</style>
