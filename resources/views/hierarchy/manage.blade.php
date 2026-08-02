@extends('grammars.grammar')

@section('content_grammar')
<!-- Load SortableJS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div class="container-fluid py-4" style="background: #f8fafc; min-height: 100vh;">
  <!-- Header Bar -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 bg-white p-3 shadow-sm rounded-4">
    <div class="d-flex align-items-center gap-3">
      <a href="{{ route('hierarchy.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        ⬅️ Danh sách
      </a>
      <div>
        <h1 class="fw-extrabold text-dark mb-0" style="font-size: 22px; letter-spacing: -0.5px;">
          🌳 {{ $tree->name }}
        </h1>
        <p class="text-muted small mb-0">Mã cây: <code class="text-primary">{{ $tree->code }}</code> | Phân hệ: <span class="badge bg-secondary-subtle text-secondary">{{ strtoupper($tree->module) }}</span></p>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-primary fw-bold px-3 py-2 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#previewTreeModal" style="border-radius: 8px;">
        👁️ Xem Trước
      </button>
      <a href="{{ route('hierarchy.export-pdf', $tree->id) }}" class="btn btn-outline-danger fw-bold px-3 py-2 d-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px;">
        📄 Xuất PDF
      </a>
      <button type="button" id="btn-save-structure" class="btn btn-primary fw-bold px-4 py-2 d-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px; transition: all 0.3s;">
        💾 Lưu Cấu Trúc Cây
      </button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="row g-4">
    <!-- Cột trái: Cấu trúc cây (Nestable List) -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold mb-0 text-dark" style="font-size: 17px;">
            🌿 Sơ Đồ Cấu Trúc Kéo Thả
          </h3>
          <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-1.5" id="btn-add-root-node" style="border-radius: 8px; font-size: 12px;">
            ➕ Thêm Nút Gốc
          </button>
        </div>

        <div id="tree-builder-container" class="py-2">
          @if(empty($nestedNodes))
            <div id="empty-tree-placeholder" class="text-center py-5 border border-dashed rounded-4" style="border-color: #cbd5e1 !important; background: #f8fafc;">
              <div class="display-6 mb-2">🌳</div>
              <h6 class="text-secondary fw-bold">Cây phân cấp đang trống</h6>
              <p class="text-muted small mb-3">Hãy tạo nút gốc đầu tiên để bắt đầu xây dựng cấu trúc cây.</p>
              <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-1.5" id="btn-add-root-node-empty" style="border-radius: 8px;">
                ➕ Tạo Nút Gốc
              </button>
            </div>
            <ol class="dd-list list-group" id="root-list" style="list-style: none; padding-left: 0; min-height: 50px; display: none;">
            </ol>
          @else
            <!-- Root List Container -->
            <div class="dd">
              {!! $nestedNodes !!}
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Cột phải: Khung biên tập nút (Inspector) & Lịch sử phiên bản -->
    <div class="col-lg-5">
      <!-- Card 1: Cấu hình chi tiết nút -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: white;">
        <h3 class="fw-bold mb-3 text-dark border-bottom pb-2.5" style="font-size: 17px;">
          ⚙️ Chi Tiết Nút & Cấu Hình
        </h3>

        <!-- Placeholder khi chưa chọn nút -->
        <div id="inspector-placeholder" class="text-center py-5 text-muted">
          <div class="fs-1 mb-2">👈</div>
          <h6 class="fw-semibold">Chưa chọn nút nào</h6>
          <p class="small">Nhấp vào nút sửa <span class="text-secondary">✏️</span> của bất kỳ phần tử nào ở bên trái để chỉnh sửa cấu hình chi tiết.</p>
        </div>

        <!-- Form chỉnh sửa (Ẩn mặc định) -->
        <form id="node-editor-form" style="display: none;" onsubmit="return false;">
          <input type="hidden" id="edit-node-id">
          
          <div class="mb-3">
            <label for="edit-node-title" class="form-label fw-bold text-secondary small">Tên hiển thị trên nút</label>
            <input type="text" class="form-control" id="edit-node-title" required style="border-radius: 8px;">
          </div>

          <div class="mb-3">
            <label for="edit-node-icon" class="form-label fw-bold text-secondary small">Biểu tượng (Emoji hoặc Icon Class)</label>
            <input type="text" class="form-control" id="edit-node-icon" placeholder="Ví dụ: 📚, 📝, lni lni-book" style="border-radius: 8px;">
          </div>

          <!-- Bảng màu sắc chủ đề -->
          <div class="mb-3">
            <label class="form-label fw-bold text-secondary small">Màu sắc chủ đề (Bản in & Đường viền)</label>
            <input type="hidden" id="edit-node-color" value="">
            <div class="d-flex align-items-center gap-2 flex-wrap p-2 bg-light rounded-3" style="border: 1px solid #e2e8f0;">
              <button type="button" class="color-picker-btn rounded-circle border border-2 d-flex align-items-center justify-content-center" data-color="" style="width: 28px; height: 28px; background-color: #ffffff;" title="Mặc định"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#3b82f6" style="width: 28px; height: 28px; background-color: #3b82f6;" title="Xanh dương"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#10b981" style="width: 28px; height: 28px; background-color: #10b981;" title="Lá cây"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#f43f5e" style="width: 28px; height: 28px; background-color: #f43f5e;" title="Đỏ hồng"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#f59e0b" style="width: 28px; height: 28px; background-color: #f59e0b;" title="Vàng hổ phách"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#8b5cf6" style="width: 28px; height: 28px; background-color: #8b5cf6;" title="Tím"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#6366f1" style="width: 28px; height: 28px; background-color: #6366f1;" title="Xanh chàm"></button>
              <button type="button" class="color-picker-btn rounded-circle border border-2" data-color="#475569" style="width: 28px; height: 28px; background-color: #475569;" title="Xám"></button>
              
              <span class="text-muted small mx-1">|</span>
              
              <input type="color" id="custom-color-picker" class="border border-1" style="width: 28px; height: 28px; padding: 0; cursor: pointer; border-radius: 50%; border: none; background: transparent;" title="Màu tùy chọn">
            </div>
          </div>

          <div class="mb-3">
            <label for="edit-node-description" class="form-label fw-bold text-secondary small">Ghi chú / Giải thích (Hiển thị bên phải dạng ←)</label>
            <input type="text" class="form-control" id="edit-node-description" placeholder="Ví dụ: 6 nhóm fillable, casts..." style="border-radius: 8px;">
          </div>

          <div class="mb-3">
            <label for="edit-node-type" class="form-label fw-bold text-secondary small">Liên kết dữ liệu (Đa hình)</label>
            <select class="form-select" id="edit-node-type" style="border-radius: 8px;">
              <option value="">-- Nút tĩnh (Thư mục / Chữ thường) --</option>
              <option value="App\Models\Grammar\GrammarLesson">Bài học Ngữ pháp (Grammar Lesson)</option>
              <option value="App\Models\Vocabulary\VocabWord">Từ vựng (Vocabulary Word)</option>
              <option value="App\Models\Listening\Listening">File nghe (Listening Audio)</option>
              <option value="App\Models\Document\Document">Tài liệu (Document)</option>
            </select>
          </div>

          <!-- Chọn thực thể ID động -->
          <div class="mb-4" id="div-linkable-id" style="display: none;">
            <label for="edit-node-type-id" class="form-label fw-bold text-secondary small">Chọn mục cụ thể để liên kết</label>
            <select class="form-select" id="edit-node-type-id" style="border-radius: 8px;">
              <!-- Populated dynamically -->
            </select>
          </div>

          <hr class="my-3">

          <div class="d-flex gap-2">
            <button type="button" id="btn-save-node-details" class="btn btn-primary fw-bold flex-grow-1" style="border-radius: 8px;">
              💾 Cập Nhật Chi Tiết
            </button>
            <button type="button" id="btn-cancel-edit" class="btn btn-light fw-bold" style="border-radius: 8px;">
              Hủy
            </button>
          </div>
        </form>
      </div>

      <!-- Card 2: Lịch sử phiên bản -->
      <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
        <h3 class="fw-bold mb-3 text-dark border-bottom pb-2.5 d-flex align-items-center justify-content-between" style="font-size: 17px;">
          <span>🕒 Lịch Sử Phiên Bản</span>
          <button type="button" id="btn-save-version" class="btn btn-xs btn-outline-warning fw-bold px-2 py-1" style="border-radius: 6px; font-size: 11px;">
            📦 Lưu bản mới
          </button>
        </h3>

        @if($versions->isEmpty())
          <div class="text-center py-4 text-muted">
            <div class="fs-2 mb-2">📦</div>
            <h6 class="fw-semibold small">Chưa có phiên bản nào được lưu</h6>
            <p class="small mb-0 text-muted" style="font-size: 11px;">Hãy bấm "Lưu bản mới" để chụp lại cấu hình hiện tại của cây.</p>
          </div>
        @else
          <div class="version-history-list" style="max-height: 280px; overflow-y: auto; padding-right: 4px;">
            @foreach($versions as $version)
              <div class="d-flex justify-content-between align-items-center p-2.5 mb-2 border rounded-3 bg-light-subtle hover-card" style="transition: all 0.2s;">
                <div style="max-width: 70%;">
                  <div class="fw-bold text-dark" style="font-size: 12.5px;">Phiên bản #{{ $version->version_number }}</div>
                  <div class="text-secondary small text-truncate" style="font-size: 11.5px;" title="{{ $version->note }}">{{ $version->note }}</div>
                  <div class="text-muted" style="font-size: 10px; margin-top: 2px;">📅 {{ $version->created_at->format('H:i - d/m/Y') }}</div>
                </div>
                <div>
                  <button type="button" class="btn btn-xs btn-outline-primary btn-restore-version fw-bold px-2.5 py-1" data-id="{{ $version->id }}" data-number="{{ $version->version_number }}" style="border-radius: 6px; font-size: 11px;">
                    🔄 Khôi phục
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

          <div class="d-flex gap-2">
            <button type="button" id="btn-save-node-details" class="btn btn-primary fw-bold flex-grow-1" style="border-radius: 8px;">
              💾 Cập Nhật Chi Tiết
            </button>
            <button type="button" id="btn-cancel-edit" class="btn btn-light fw-bold" style="border-radius: 8px;">
              Hủy
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Thêm nút mới (Dùng chung cho nút gốc và nút con) -->
<div class="modal fade" id="addNodeModal" tabindex="-1" aria-labelledby="addNodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-0 bg-light p-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title fw-bold" id="addNodeModalLabel" style="margin: 0;">➕ Thêm Nút Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="add-node-form" onsubmit="return false;">
          <input type="hidden" id="add-node-parent-id">
          
          <div class="mb-3">
            <label for="add-node-title" class="form-label fw-bold text-secondary small">Tên nút</label>
            <input type="text" class="form-control" id="add-node-title" required placeholder="Ví dụ: Chương I, Bài 1..." style="border-radius: 8px;">
          </div>

          <div class="mb-3">
            <label for="add-node-icon" class="form-label fw-bold text-secondary small">Biểu tượng (Tùy chọn)</label>
            <input type="text" class="form-control" id="add-node-icon" placeholder="Ví dụ: 📘, 📂..." style="border-radius: 8px;">
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
        <button type="button" id="btn-submit-add-node" class="btn btn-primary fw-bold" style="border-radius: 8px;">Tạo nút</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Xem Trước Sơ Đồ Cây -->
<div class="modal fade" id="previewTreeModal" tabindex="-1" aria-labelledby="previewTreeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header bg-dark text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <h5 class="modal-title fw-bold text-white" id="previewTreeModalLabel" style="margin: 0;">👁️ Xem Trước Sơ Đồ Cây (Định dạng PDF)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1); opacity: 0.8;"></button>
      </div>
      <div class="modal-body p-4" style="background-color: #f8fafc; text-align: left;">
        <p class="text-muted small mb-3">Dưới đây là bản xem trước trực quan của sơ đồ cây dưới dạng văn bản đơn cách (Monospaced). Khi xuất ra file PDF, sơ đồ sẽ được căn lề thẳng hàng tăm tắp như sau:</p>
        <pre class="p-3 bg-white border rounded-3 shadow-sm text-dark font-monospace" style="font-size: 12px; line-height: 1.6; overflow-x: auto; white-space: pre; font-family: 'DejaVu Sans', monospace;">{!! $textTree !!}</pre>
      </div>
      <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: flex-end; gap: 8px;">
        <a href="{{ route('hierarchy.export-pdf', $tree->id) }}" class="btn btn-danger fw-bold" style="border-radius: 8px; text-decoration: none;">📄 Tải Xuống PDF</a>
        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
      </div>
    </div>
  </div>
</div>

<!-- Raw option storage for Dynamic Select Lists -->
<div style="display: none;">
  <select id="options-grammar">
    <option value="">-- Chọn bài học ngữ pháp --</option>
    @foreach($grammarLessons as $item)
      <option value="{{ $item->id }}">{{ $item->title }}</option>
    @endforeach
  </select>
  <select id="options-vocabulary">
    <option value="">-- Chọn từ vựng --</option>
    @foreach($vocabWords as $item)
      <option value="{{ $item->id }}">{{ $item->word }}</option>
    @endforeach
  </select>
  <select id="options-listening">
    <option value="">-- Chọn file nghe --</option>
    @foreach($listeningFiles as $item)
      <option value="{{ $item->id }}">{{ $item->title }}</option>
    @endforeach
  </select>
  <select id="options-document">
    <option value="">-- Chọn tài liệu --</option>
    @foreach($documents as $item)
      <option value="{{ $item->id }}">{{ $item->name }}</option>
    @endforeach
  </select>
</div>

<!-- Inline Styles -->
<style>
  .dd-list {
    margin-top: 6px;
    transition: all 0.2s;
  }
  .dd-item {
    display: block;
  }
  .dd-handle {
    transition: all 0.2s;
    background-color: white;
  }
  .dd-handle:hover {
    background-color: #f8fafc !important;
    border-color: #3b82f6 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
  }
  .btn-xs {
    padding: 2px 6px;
    font-size: 10px;
    border-radius: 4px;
  }
  .btn-pulse-glow {
    animation: pulse-glow-save 1.5s infinite ease-in-out;
  }
  @keyframes pulse-glow-save {
    0%, 100% {
      box-shadow: 0 0 2px rgba(37, 99, 235, 0.5);
      background-color: #2563eb;
    }
    50% {
      box-shadow: 0 0 12px rgba(37, 99, 235, 1);
      background-color: #1d4ed8;
    }
  }
  
  /* [MỚI] Định dạng nút bảng màu */
  .color-picker-btn {
    transition: all 0.2s;
    cursor: pointer;
    border-color: #cbd5e1 !important;
  }
  .color-picker-btn:hover {
    transform: scale(1.15);
  }
  .color-picker-btn.active {
    border-color: #0f172a !important;
    transform: scale(1.2);
    box-shadow: 0 0 0 2px rgba(255,255,255,1), 0 0 0 3px #3b82f6;
  }
</style>

<!-- Drag and Drop JS -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Khởi tạo SortableJS cho các cấp độ của cây ---
    function initSortable(el) {
      new Sortable(el, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        handle: '.drag-handle',
        onEnd: function(evt) {
          // Khi kết thúc kéo thả, kích hoạt nhấp nháy nút "Lưu Cấu Trúc Cây"
          document.getElementById('btn-save-structure').classList.add('btn-pulse-glow');
        }
      });
    }

    // Khởi chạy trên toàn bộ danh sách hiện có
    document.querySelectorAll('.dd-list').forEach(initSortable);

    // --- 2. Xử lý mở Modal Thêm Nút ---
    const addNodeModal = new bootstrap.Modal(document.getElementById('addNodeModal'));
    const addNodeForm = document.getElementById('add-node-form');
    const inputParentId = document.getElementById('add-node-parent-id');

    // Thêm nút gốc (Root)
    const btnAddRootNode = document.getElementById('btn-add-root-node');
    const btnAddRootNodeEmpty = document.getElementById('btn-add-root-node-empty');

    function openAddRootModal() {
      addNodeForm.reset();
      inputParentId.value = '';
      document.getElementById('addNodeModalLabel').innerText = '➕ Thêm Nút Gốc';
      addNodeModal.show();
    }

    if (btnAddRootNode) btnAddRootNode.addEventListener('click', openAddRootModal);
    if (btnAddRootNodeEmpty) btnAddRootNodeEmpty.addEventListener('click', openAddRootModal);

    // Thêm nút con (Child) - Ủy quyền sự kiện (Event Delegation)
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('btn-add-child-node')) {
        const parentId = e.target.getAttribute('data-id');
        addNodeForm.reset();
        inputParentId.value = parentId;
        document.getElementById('addNodeModalLabel').innerText = '➕ Thêm Nút Con';
        addNodeModal.show();
      }
    });

    // --- 3. Gửi AJAX tạo nút mới ---
    const btnSubmitAddNode = document.getElementById('btn-submit-add-node');
    btnSubmitAddNode.addEventListener('click', function() {
      const title = document.getElementById('add-node-title').value.trim();
      if (!title) {
        alert('Vui lòng nhập tên nút!');
        return;
      }

      const icon = document.getElementById('add-node-icon').value.trim();
      const parentId = inputParentId.value;
      const treeId = "{{ $tree->id }}";

      btnSubmitAddNode.disabled = true;
      btnSubmitAddNode.innerText = '⏳ Đang tạo...';

      fetch("{{ route('hierarchy.nodes.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          tree_id: treeId,
          parent_id: parentId || null,
          title: title,
          icon: icon || null
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          addNodeModal.hide();
          alert(data.message);
          location.reload(); // Tải lại trang để vẽ lại cây phân cấp
        } else {
          alert('Lỗi: ' + data.message);
          btnSubmitAddNode.disabled = false;
          btnSubmitAddNode.innerText = 'Tạo nút';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi kết nối máy chủ.');
        btnSubmitAddNode.disabled = false;
        btnSubmitAddNode.innerText = 'Tạo nút';
      });
    });

    // --- 4. Xử lý sửa nút (Mở Inspector bên phải) ---
    const inspectorPlaceholder = document.getElementById('inspector-placeholder');
    const nodeEditorForm = document.getElementById('node-editor-form');
    
    const editNodeId = document.getElementById('edit-node-id');
    const editNodeTitle = document.getElementById('edit-node-title');
    const editNodeIcon = document.getElementById('edit-node-icon');
    const editNodeDescription = document.getElementById('edit-node-description');
    const editNodeColor = document.getElementById('edit-node-color');
    const customColorPicker = document.getElementById('custom-color-picker');
    const editNodeType = document.getElementById('edit-node-type');
    const editNodeTypeId = document.getElementById('edit-node-type-id');
    const divLinkableId = document.getElementById('div-linkable-id');

    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('btn-edit-node')) {
        const id = e.target.getAttribute('data-id');
        const title = e.target.getAttribute('data-title');
        const icon = e.target.getAttribute('data-icon');
        const nodeableType = e.target.getAttribute('data-type');
        const nodeableId = e.target.getAttribute('data-type-id');
        const description = e.target.getAttribute('data-description');
        const color = e.target.getAttribute('data-color');

        // Điền dữ liệu vào form
        editNodeId.value = id;
        editNodeTitle.value = title;
        editNodeIcon.value = icon === 'null' ? '' : icon;
        editNodeDescription.value = description === 'null' ? '' : description;
        editNodeColor.value = color === 'null' ? '' : color;
        editNodeType.value = nodeableType === 'null' ? '' : nodeableType;

        // Kích hoạt bảng màu hiển thị nút được chọn
        document.querySelectorAll('.color-picker-btn').forEach(btn => {
          btn.classList.remove('active');
          if (btn.getAttribute('data-color') === (color || '')) {
            btn.classList.add('active');
          }
        });
        if (color && !['', '#3b82f6', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6', '#6366f1', '#475569'].includes(color)) {
          customColorPicker.value = color;
        } else {
          customColorPicker.value = '#000000';
        }

        // Kích hoạt cập nhật dropdown liên kết
        updateLinkableDropdown(nodeableType, nodeableId);

        // Hiển thị Form, ẩn Placeholder
        inspectorPlaceholder.style.display = 'none';
        nodeEditorForm.style.display = 'block';

        // Cuộn mượt tới bảng cấu hình trên Mobile
        if (window.innerWidth < 992) {
          nodeEditorForm.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });

    // Hủy chỉnh sửa
    document.getElementById('btn-cancel-edit').addEventListener('click', function() {
      nodeEditorForm.reset();
      nodeEditorForm.style.display = 'none';
      inspectorPlaceholder.style.display = 'block';
    });

    // Đổi loại liên kết thì cập nhật danh sách chọn thực thể
    editNodeType.addEventListener('change', function() {
      updateLinkableDropdown(this.value, null);
    });

    function updateLinkableDropdown(type, selectedId) {
      editNodeTypeId.innerHTML = '';
      
      if (!type) {
        divLinkableId.style.display = 'none';
        return;
      }

      let sourceSelectId = '';
      if (type === 'App\\Models\\Grammar\\GrammarLesson') {
        sourceSelectId = 'options-grammar';
      } else if (type === 'App\\Models\\Vocabulary\\VocabWord') {
        sourceSelectId = 'options-vocabulary';
      } else if (type === 'App\\Models\\Listening\\Listening') {
        sourceSelectId = 'options-listening';
      } else if (type === 'App\\Models\\Document\\Document') {
        sourceSelectId = 'options-document';
      }

      if (sourceSelectId) {
        const sourceSelect = document.getElementById(sourceSelectId);
        editNodeTypeId.innerHTML = sourceSelect.innerHTML;
        if (selectedId) {
          editNodeTypeId.value = selectedId;
        }
        divLinkableId.style.display = 'block';
      } else {
        divLinkableId.style.display = 'none';
      }
    }

    // Gửi AJAX lưu chi tiết chỉnh sửa của Nút
    const btnSaveNodeDetails = document.getElementById('btn-save-node-details');
    btnSaveNodeDetails.addEventListener('click', function() {
      const id = editNodeId.value;
      const title = editNodeTitle.value.trim();
      const icon = editNodeIcon.value.trim();
      const type = editNodeType.value;
      const typeId = editNodeTypeId.value;

      if (!title) {
        alert('Vui lòng nhập tên nút!');
        return;
      }

      btnSaveNodeDetails.disabled = true;
      btnSaveNodeDetails.innerText = '⏳ Đang lưu...';

      let url = "{{ route('hierarchy.nodes.update', ':id') }}";
      url = url.replace(':id', id);

      fetch(url, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          title: title,
          icon: icon || null,
          nodeable_type: type || null,
          nodeable_id: type ? typeId : null,
          description: editNodeDescription.value.trim() || null,
          color: editNodeColor.value || null
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          location.reload();
        } else {
          alert('Lỗi: ' + data.message);
          btnSaveNodeDetails.disabled = false;
          btnSaveNodeDetails.innerText = '💾 Cập Nhật Chi Tiết';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi kết nối máy chủ.');
        btnSaveNodeDetails.disabled = false;
        btnSaveNodeDetails.innerText = '💾 Cập Nhật Chi Tiết';
      });
    });

    // [MỚI] Click chọn bảng màu
    document.querySelectorAll('.color-picker-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.color-picker-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        editNodeColor.value = this.getAttribute('data-color');
      });
    });
    customColorPicker.addEventListener('input', function() {
      document.querySelectorAll('.color-picker-btn').forEach(b => b.classList.remove('active'));
      editNodeColor.value = this.value;
    });

    // --- 5. Xóa Nút (Delete Node) ---
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('btn-delete-node')) {
        const id = e.target.getAttribute('data-id');
        if (confirm('CẢNH BÁO: Hành động này sẽ xóa nút này và TOÀN BỘ các nút con cấp dưới của nó. Bạn có chắc chắn muốn xóa không?')) {
          let url = "{{ route('hierarchy.nodes.destroy', ':id') }}";
          url = url.replace(':id', id);

          fetch(url, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Lỗi: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi kết nối máy chủ.');
          });
        }
      }
    });

    // --- 6. Xử lý Lưu Cấu Trúc Cây (Save Drag & Drop Structure) ---
    const btnSaveStructure = document.getElementById('btn-save-structure');
    btnSaveStructure.addEventListener('click', function() {
      
      // Hàm đệ quy duyệt qua các phần tử HTML để lấy ra cấu trúc phẳng
      const structure = [];
      
      function serializeList(listEl, parentId = null) {
        // Lấy tất cả các li là con trực tiếp của listEl
        const items = Array.from(listEl.children);
        items.forEach((item, index) => {
          const id = item.getAttribute('data-id');
          if (id) {
            structure.push({
              id: parseInt(id),
              parent_id: parentId,
              order: index + 1
            });
            
            // Tìm danh sách con trực tiếp nằm dưới li này
            const childList = item.querySelector('.dd-list');
            if (childList) {
              serializeList(childList, parseInt(id));
            }
          }
        });
      }

      // Bắt đầu duyệt từ root list
      const rootLists = document.querySelectorAll('.dd > .dd-list');
      rootLists.forEach(list => serializeList(list, null));

      if (structure.length === 0) {
        alert('Cấu trúc cây đang trống, không có gì để lưu!');
        return;
      }

      btnSaveStructure.disabled = true;
      btnSaveStructure.innerText = '⏳ Đang lưu cấu trúc...';

      fetch("{{ route('hierarchy.save-structure', $tree->id) }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          structure: structure
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          btnSaveStructure.classList.remove('btn-pulse-glow');
          location.reload();
        } else {
          alert('Lỗi: ' + data.message);
          btnSaveStructure.disabled = false;
          btnSaveStructure.innerText = '💾 Lưu Cấu Trúc Cây';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi kết nối máy chủ khi lưu cấu trúc.');
        btnSaveStructure.disabled = false;
        btnSaveStructure.innerText = '💾 Lưu Cấu Trúc Cây';
      });
    });

    // --- 7. [MỚI] Xử lý Lưu Phiên Bản & Khôi phục Phiên Bản ---
    const btnSaveVersion = document.getElementById('btn-save-version');
    if (btnSaveVersion) {
      btnSaveVersion.addEventListener('click', function() {
        const note = prompt('Nhập ghi chú cho phiên bản này (Ví dụ: Bản ban đầu, Thêm bài học mới...):');
        if (note === null) return; // Người dùng hủy bỏ
        
        btnSaveVersion.disabled = true;
        btnSaveVersion.innerText = '⏳ Đang lưu...';

        fetch("{{ route('hierarchy.version.store', $tree->id) }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            note: note.trim()
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            location.reload();
          } else {
            alert('Lỗi: ' + data.message);
            btnSaveVersion.disabled = false;
            btnSaveVersion.innerText = '📦 Lưu bản mới';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi kết nối máy chủ.');
          btnSaveVersion.disabled = false;
          btnSaveVersion.innerText = '📦 Lưu bản mới';
        });
      });
    }

    // Khôi phục phiên bản
    document.querySelectorAll('.btn-restore-version').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const versionNumber = this.getAttribute('data-number');
        
        if (confirm('CẢNH BÁO: Hành động này sẽ xóa toàn bộ cấu trúc hiện tại và khôi phục cây về Phiên bản #' + versionNumber + '. Bạn có chắc chắn muốn khôi phục không?')) {
          this.disabled = true;
          this.innerText = '⏳...';
          
          let url = "{{ route('hierarchy.version.restore', ':id') }}";
          url = url.replace(':id', id);
          
          fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Lỗi: ' + data.message);
              this.disabled = false;
              this.innerText = '🔄 Khôi phục';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi kết nối máy chủ.');
            this.disabled = false;
            this.innerText = '🔄 Khôi phục';
          });
        }
      });
    });

  });
</script>
@endsection
