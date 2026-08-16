# Luồng xử lý — Chức năng Cây Phân Cấp Đa Hình (Hierarchy Tree)

> Tài liệu này mô tả đầy đủ kiến trúc, luồng dữ liệu, cơ sở dữ liệu và logic nghiệp vụ của chức năng Cây Phân Cấp Động (Polymorphic Hierarchy Tree) kèm tính năng Quản lý Phiên bản và Màu sắc trong hệ thống Quản lý Tiếng Anh.

---

## 1. Tổng quan kiến trúc

```
┌─────────────────────────────────────────────────────────────────┐
│                     CHỨC NĂNG CÂY PHÂN CẤP                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  [Cấu hình cây]                                                   │
│    HierarchyTree (Tên, Mã code định danh, Phân hệ)                │
│      ├─ HierarchyVersion (Lịch sử phiên bản lưu dưới dạng JSON)   │
│      └─ HierarchyNode (Từng nút trên cây - Đệ quy)                │
│           ├─ parent_id (Liên kết cha-con đệ quy └──)               │
│           ├─ metadata (Lưu ghi chú 'description', màu sắc 'color')│
│           └─ nodeable (Liên kết đa hình tới các Model thực tế)    │
│                ├─ App\Models\Grammar\GrammarLesson                │
│                ├─ App\Models\Vocabulary\VocabWord                 │
│                ├─ App\Models\Listening\Listening                  │
│                └─ App\Models\Document\Document                    │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Cơ sở dữ liệu

### 2.1. Bảng `hierarchy_trees` — Cấu hình cây phân cấp

| Nhóm | Trường | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| **Định danh** | `id` | BIGINT (PK) | Khóa chính tự tăng |
| | `name` | VARCHAR(255) | Tên cây (Ví dụ: Lộ Trình Ngữ Pháp) |
| | `code` | VARCHAR(100) (UNIQUE) | Mã code định danh duy nhất |
| | `module` | VARCHAR(50) | Phân hệ (grammar, vocabulary, listening, custom) |
| **Mô tả** | `description` | TEXT | Mô tả mục tiêu của cây |
| **Hệ thống** | `created_at` / `updated_at` | TIMESTAMP | Thời gian tạo và cập nhật |

### 2.2. Bảng `hierarchy_nodes` — Chi tiết các nút trong cây

| Nhóm | Trường | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| **Định danh** | `id` | BIGINT (PK) | Khóa chính tự tăng |
| **Liên kết** | `tree_id` | BIGINT (FK) | Thuộc về cây nào (Liên kết `hierarchy_trees.id`) |
| | `parent_id` | BIGINT (FK, Nullable) | Liên kết đệ quy tới nút cha (Liên kết `hierarchy_nodes.id`) |
| **Thuộc tính** | `title` | VARCHAR(255) | Tiêu đề hiển thị của nút |
| | `icon` | VARCHAR(100) (Nullable) | Biểu tượng của nút (Emoji hoặc Icon Class) |
| **Sắp xếp** | `order` | INT | Thứ tự sắp xếp của nút giữa các nút cùng cấp |
| **Đa hình** | `nodeable_type` | VARCHAR(255) (Nullable) | Đường dẫn Class của Model liên kết |
| | `nodeable_id` | BIGINT (Nullable) | ID của thực thể liên kết tương ứng |
| **Mở rộng** | `metadata` | JSON (Nullable) | Chứa ghi chú giải thích (`description`) và màu sắc (`color`) |
| **Hệ thống** | `created_at` / `updated_at` | TIMESTAMP | Thời gian tạo và cập nhật |

### 2.3. Bảng `hierarchy_versions` — Lưu trữ lịch sử phiên bản của cây

| Trường | Kiểu dữ liệu | Mô tả |
|---|---|---|
| `id` | BIGINT (PK) | Khóa chính tự tăng |
| `tree_id` | BIGINT (FK) | Thuộc về cây nào (Liên kết `hierarchy_trees.id` ON DELETE CASCADE) |
| `version_number` | INT | Số thứ tự phiên bản (Tự tăng theo từng cây: 1, 2, 3...) |
| `note` | VARCHAR(255) (Nullable) | Ghi chú về các thay đổi trong phiên bản này |
| `nodes_snapshot` | JSON | Bản chụp toàn bộ các nút của cây tại thời điểm lưu |
| `created_at` / `updated_at` | TIMESTAMP | Thời gian lưu phiên bản |

---

## 3. Luồng xử lý Kéo thả & Quản lý nút

```
Bước 1: Quản trị viên truy cập trang quản lý kéo thả
         GET /admin/hierarchy/{id}/manage  → manage.blade.php
         → Controller lấy toàn bộ Nodes của cây, gọi đệ quy dựng HTML lồng nhau.
         → Controller đồng thời lấy danh sách các phiên bản cũ từ bảng hierarchy_versions.

Bước 2: Quản trị viên thực hiện Kéo thả cấu trúc (Drag-and-Drop)
         - Sử dụng SortableJS để kéo lên/xuống (thay đổi order) hoặc kéo sang phải (thành nút con).
         - Khi có thay đổi, nút "Lưu Cấu Trúc Cây" sẽ nhấp nháy phát sáng (CSS Animation).

Bước 3: Nhấn "Lưu Cấu Trúc Cây"
         - JavaScript duyệt qua cây DOM phân cấp, chuyển đổi thành mảng phẳng chứa:
           [ { id: x, parent_id: y, order: z }, ... ]
         - Gửi AJAX POST lên Server:
           POST /admin/hierarchy/{id}/save-structure

Bước 4: Server xử lý cập nhật hàng loạt (Bulk Update)
         - Chạy trong DB Transaction để đảm bảo an toàn dữ liệu.
         - Cập nhật đồng loạt parent_id và order của các nút.
         - Trả về JSON thành công và tự động reload để vẽ lại cây chuẩn xác.

Bước 5: Chỉnh sửa chi tiết nút & màu sắc (Node Inspector & Color Palette)
         - Click vào nút ✏️ ở cột trái, thông tin nút hiện sang Form ở cột phải.
         - Quản trị viên có thể:
           + Chọn màu chủ đề (border accent) từ bảng 8 màu có sẵn hoặc ô chọn màu tùy biến (Hex Color).
           + Nhập "Ghi chú / Giải thích" để hiển thị dạng mũi tên ← ở bên phải nút.
         - Bấm "Cập Nhật Chi Tiết" gửi AJAX PUT lên máy chủ:
           PUT /admin/hierarchy/nodes/{node_id}
         - Server lưu mô tả và màu sắc vào cột metadata: 
           $metadata['description'] = $request->description;
           $metadata['color'] = $request->color;

Bước 6: Lưu Phiên Bản Mới (Version Snapshot)
         - Bấm nút "Lưu bản mới" ở card Lịch sử phiên bản bên phải.
         - Hiện hộp thoại prompt yêu cầu nhập ghi chú phiên bản.
         - Gửi AJAX POST lên Server:
           POST /admin/hierarchy/{id}/version
         - Server gom toàn bộ nodes hiện tại thành mảng phẳng, mã hóa thành JSON và lưu vào bảng hierarchy_versions.

Bước 7: Khôi phục Phiên Bản Cũ (Rollback)
         - Bấm nút "Khôi phục" bên cạnh phiên bản tương ứng trong danh sách lịch sử.
         - Hiện confirm cảnh báo xóa cấu trúc hiện tại.
         - Gửi AJAX POST lên Server:
           POST /admin/hierarchy/version/{version_id}/restore
         - Server chạy DB Transaction:
           + Xóa sạch toàn bộ các nút hiện tại của cây.
           + Chạy thuật toán khôi phục đệ quy restoreNodesRecursive() để dựng lại các nút mới từ bản chụp JSON.
           + Tự động tạo ID mới và ánh xạ lại parent_id chuẩn xác.

Bước 8: Xem trước & Xuất sơ đồ cây ra PDF
         - Bấm "Xem Trước" -> Hiện Modal chứa sơ đồ cây dạng văn bản thô hỗ trợ màu sắc {!! $textTree !!}.
         - Bấm "Xuất PDF" -> Tải xuống file PDF. Bản in lọc sạch Emojis và tự động căn lề thẳng hàng dấu ← ở cột 45.
```

---

## 4. Thuật toán Vẽ cây & Căn lề văn bản

### 4.1. Thuật toán vẽ cây đệ quy dựng HTML (SortableJS Dropzone)
Để đảm bảo người dùng có thể kéo thả một nút vào làm con của một nút chưa có con nào, hàm `buildTreeHtml` luôn chèn một danh sách trống `<ol class="dd-list">` kể cả khi nút đó chưa có nút con:
```php
$childrenHtml = $this->buildTreeHtml($nodes, $node->id);
if (empty($childrenHtml)) {
    $html .= '<ol class="dd-list list-group" style="min-height: 10px; margin-top: 5px;"></ol>';
} else {
    $html .= $childrenHtml;
}
```

### 4.2. Thuật toán căn lề dấu mũi tên giải thích `←` và tô màu trong PDF
Để sơ đồ cây văn bản hiển thị đẹp mắt, toàn bộ dấu mũi tên giải thích phải được căn thẳng hàng theo chiều dọc. Hệ thống sử dụng hàm đo độ dài ký tự đa mã hóa `mb_strlen` để chèn khoảng trắng bù trừ động, đồng thời bọc thẻ `<span>` màu sắc nếu nút có cấu hình màu:
```php
$cleanTitle = $this->stripEmojis($node->title);
$line = $prefix . $branch . $cleanTitle;

if (!empty($node->metadata['description'])) {
    $lineLen = mb_strlen($line);
    $targetLen = 45; // Căn thẳng hàng ở cột thứ 45
    $spaces = $targetLen > $lineLen ? str_repeat(' ', $targetLen - $lineLen) : '   ';
    $line .= $spaces . '← ' . $node->metadata['description'];
}

if (!empty($node->metadata['color'])) {
    $line = '<span style="color: ' . $node->metadata['color'] . ';">' . e($line) . '</span>';
} else {
    $line = e($line);
}
```

### 4.3. Thuật toán khôi phục cây từ bản chụp JSON (ID Mapping)
Khi khôi phục từ bản chụp, các nút sẽ nhận các ID tự tăng hoàn toàn mới. Hàm `restoreNodesRecursive` duyệt đệ quy theo tầng từ các nút gốc (`parent_id = null`) xuống dưới, chuyển ID mới của nút cha vừa tạo làm `parent_id` cho nút con thế hệ tiếp theo:
```php
private function restoreNodesRecursive($snapshotNodes, $oldParentId, $newParentId, $treeId)
{
    $nodesAtLevel = collect($snapshotNodes)->where('parent_id', $oldParentId)->sortBy('order');
    foreach ($nodesAtLevel as $nodeData) {
        $newNode = HierarchyNode::create([
            'tree_id' => $treeId,
            'parent_id' => $newParentId,
            'title' => $nodeData['title'],
            'icon' => $nodeData['icon'] ?? null,
            'nodeable_type' => $nodeData['nodeable_type'] ?? null,
            'nodeable_id' => $nodeData['nodeable_id'] ?? null,
            'order' => $nodeData['order'] ?? 0,
            'metadata' => $nodeData['metadata'] ?? null,
        ]);

        // Đệ quy gọi nút con dưới cấp
        $this->restoreNodesRecursive($snapshotNodes, $nodeData['id'], $newNode->id, $treeId);
    }
}
```

---

## 5. Cấu trúc Files

### Models
```
app/Models/Hierarchy/
├── HierarchyTree.php        ← Định nghĩa cây, quan hệ nodes(), rootNodes(), versions()
├── HierarchyNode.php        ← Định nghĩa nút, quan hệ parent(), children(), nodeable() (đa hình)
└── HierarchyVersion.php     ← Định nghĩa bản chụp phiên bản, quan hệ tree()
```

### Controllers
```
app/Http/Controllers/Hierarchy/
└── HierarchyController.php  ← CRUD cây & nút, xử lý saveStructure (Transaction), vẽ cây HTML, quản lý phiên bản và sinh PDF
```

### Views
```
resources/views/hierarchy/
├── index.blade.php           ← Trang danh mục & Khởi tạo cây phân cấp mới
├── manage.blade.php          ← Giao diện kéo thả SortableJS, khung Inspector, Lịch sử phiên bản, Modal Xem trước
└── pdf.blade.php             ← Template xuất bản PDF (DejaVu Sans font)
```

### Migrations & Seeders
```
database/migrations/
├── 2026_06_28_000000_create_hierarchy_tables.php   ← Khởi tạo bảng trees và nodes
└── 2026_06_28_010000_create_hierarchy_versions_table.php  ← Khởi tạo bảng lưu phiên bản
database/seeders/
└── HierarchySeeder.php                            ← Seeder lộ trình ngữ pháp mẫu
```

---

## 6. Routes

| Method | URL | Action | Tên | Vai trò |
|---|---|---|---|---|
| GET | `/admin/hierarchy` | `index` | `hierarchy.index` | Xem danh sách các cây phân cấp hiện có |
| POST | `/admin/hierarchy` | `store` | `hierarchy.store` | Khởi tạo một cây phân cấp mới |
| GET | `/admin/hierarchy/{id}/manage` | `manage` | `hierarchy.manage` | Giao diện thiết kế kéo thả & quản lý phiên bản |
| GET | `/admin/hierarchy/{id}/export-pdf` | `exportPdf` | `hierarchy.export-pdf` | Tải xuống sơ đồ cây định dạng PDF |
| POST | `/admin/hierarchy/nodes` | `storeNode` | `hierarchy.nodes.store` | Thêm một nút mới vào cây |
| PUT | `/admin/hierarchy/nodes/{id}` | `updateNode` | `hierarchy.nodes.update` | Cập nhật chi tiết nút (tên, icon, liên kết, chú thích, màu sắc) |
| DELETE | `/admin/hierarchy/nodes/{id}` | `destroyNode` | `hierarchy.nodes.destroy` | Xóa nút và toàn bộ các nút con |
| POST | `/admin/hierarchy/{id}/save-structure` | `saveStructure` | `hierarchy.save-structure` | Lưu cấu trúc cây phân cấp sau khi kéo thả |
| POST | `/admin/hierarchy/{id}/version` | `saveVersion` | `hierarchy.version.store` | Chụp lại cấu trúc cây thành phiên bản mới |
| POST | `/admin/hierarchy/version/{version_id}/restore` | `restoreVersion` | `hierarchy.version.restore` | Khôi phục cây về phiên bản snapshot cũ |

---

## 7. Tích hợp tương lai

- [ ] **Dynamic Navigation Menu**: Tự động dùng cây phân cấp để render hệ thống Menu/Sidebar động cho học viên.
- [ ] **Lock/Unlock Progress**: Khóa các nút con nếu học viên chưa hoàn thành bài học ở nút cha.
- [ ] **Version Compare (Diff)**: So sánh sự khác biệt giữa hai phiên bản cấu trúc cây trực tiếp trên giao diện.

---

*Cập nhật lần cuối: 28/06/2026*
*Tác giả: Antigravity AI Assistant*
