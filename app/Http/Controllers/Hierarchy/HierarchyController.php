<?php

namespace App\Http\Controllers\Hierarchy;

use App\Http\Controllers\Controller;
use App\Models\Hierarchy\HierarchyTree;
use App\Models\Hierarchy\HierarchyNode;
use App\Models\Hierarchy\HierarchyVersion;
use App\Models\Grammar\GrammarLesson;
use App\Models\Vocabulary\VocabWord;
use App\Models\Listening\Listening;
use App\Models\Document\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HierarchyController extends Controller
{
    /**
     * Display a listing of the hierarchy trees.
     */
    public function index()
    {
        $trees = HierarchyTree::withCount('nodes')->orderBy('created_at', 'desc')->get();
        return view('hierarchy.index', compact('trees'));
    }

    /**
     * Store a newly created hierarchy tree in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:hierarchy_trees,code',
            'module' => 'required|string|in:grammar,vocabulary,listening,custom',
            'description' => 'nullable|string|max:1000',
        ]);

        $tree = HierarchyTree::create($data);

        return redirect()->route('hierarchy.manage', $tree->id)
            ->with('success', 'Đã khởi tạo cây phân cấp mới. Hãy bắt đầu xây dựng các nút!');
    }

    /**
     * Show the drag-and-drop tree manager interface.
     */
    public function manage($id)
    {
        $tree = HierarchyTree::with(['nodes', 'versions'])->findOrFail($id);
        $versions = $tree->versions;

        // Fetch linkable models for polymorphic mapping
        $grammarLessons = GrammarLesson::orderBy('title')->get(['id', 'title']);
        $vocabWords = VocabWord::orderBy('word')->get(['id', 'word']);
        $listeningFiles = Listening::orderBy('title')->get(['id', 'title']);
        $documents = Document::orderBy('name')->get(['id', 'name']);

        // Build hierarchical tree structure from flat nodes list in PHP
        $nodes = $tree->nodes;
        $nestedNodes = $this->buildTreeHtml($nodes, null);
        
        // Generate the text-based tree preview
        $textTree = $this->generateTextTree($nodes, null, '');

        return view('hierarchy.manage', compact(
            'tree',
            'nestedNodes',
            'textTree',
            'versions',
            'grammarLessons',
            'vocabWords',
            'listeningFiles',
            'documents'
        ));
    }

    /**
     * Store a new node in the tree.
     */
    public function storeNode(Request $request)
    {
        $data = $request->validate([
            'tree_id' => 'required|exists:hierarchy_trees,id',
            'parent_id' => 'nullable|exists:hierarchy_nodes,id',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        // Get max order in the same level
        $maxOrder = HierarchyNode::where('tree_id', $data['tree_id'])
            ->where('parent_id', $data['parent_id'])
            ->max('order') ?? 0;

        $data['order'] = $maxOrder + 1;

        $node = HierarchyNode::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm nút mới thành công!',
            'node' => $node
        ]);
    }

    /**
     * Update a specific node details.
     */
    public function updateNode(Request $request, $id)
    {
        $node = HierarchyNode::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'nodeable_type' => 'nullable|string|max:255',
            'nodeable_id' => 'nullable|integer',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7', // Hex color e.g. #3b82f6
            'metadata' => 'nullable|array',
        ]);

        // Store the description and color inside the metadata JSON column
        $metadata = $node->metadata ?? [];
        $metadata['description'] = $data['description'] ?? '';
        $metadata['color'] = $data['color'] ?? '';
        
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $metadata = array_merge($metadata, $data['metadata']);
        }
        
        $node->update([
            'title' => $data['title'],
            'icon' => $data['icon'],
            'nodeable_type' => $data['nodeable_type'],
            'nodeable_id' => $data['nodeable_type'] ? $data['nodeable_id'] : null,
            'metadata' => $metadata
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật thông tin nút thành công!',
            'node' => $node
        ]);
    }

    /**
     * Delete a node from the tree (cascades children).
     */
    public function destroyNode($id)
    {
        $node = HierarchyNode::findOrFail($id);
        $node->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa nút và toàn bộ các nhánh con liên quan.'
        ]);
    }

    /**
     * Save the entire tree drag-and-drop structure in a transaction.
     */
    public function saveStructure(Request $request, $id)
    {
        $tree = HierarchyTree::findOrFail($id);
        $structure = $request->input('structure', []); // Expects flat array of {id, parent_id, order}

        DB::beginTransaction();
        try {
            foreach ($structure as $item) {
                HierarchyNode::where('id', $item['id'])
                    ->where('tree_id', $tree->id)
                    ->update([
                        'parent_id' => $item['parent_id'] ?: null,
                        'order' => $item['order']
                    ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu cấu trúc và thứ tự sắp xếp của cây phân cấp!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lưu cấu trúc: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recursive helper to build nested HTML list for SortableJS.
     */
    private function buildTreeHtml($nodes, $parentId = null)
    {
        $html = '';
        $filtered = $nodes->where('parent_id', $parentId)->sortBy('order');

        if ($filtered->isEmpty()) {
            return '';
        }

        $html .= '<ol class="dd-list list-group" style="list-style: none; padding-left: 20px;">';
        foreach ($filtered as $node) {
            $hasChildren = $nodes->contains('parent_id', $node->id);
            $iconHtml = $node->icon ? '<span class="node-icon me-2">' . e($node->icon) . '</span>' : '📁 ';
            
            $linkBadge = '';
            if ($node->nodeable_type) {
                $typeName = class_basename($node->nodeable_type);
                $linkBadge = ' <span class="badge bg-light text-dark border ms-2" style="font-size: 9.5px;">🔗 ' . $typeName . ' #' . $node->nodeable_id . '</span>';
            }

            // [MỚI] Hiển thị phần chú thích / giải thích nếu có
            $descHtml = '';
            if (!empty($node->metadata['description'])) {
                $descHtml = ' <span class="text-muted ms-2 small" style="font-size: 11px; font-style: italic;">← ' . e($node->metadata['description']) . '</span>';
            }

            $borderStyle = '';
            if (!empty($node->metadata['color'])) {
                $borderStyle = ' border-left: 5px solid ' . $node->metadata['color'] . ' !important;';
            }

            $html .= '<li class="dd-item list-group-item border-0 p-0 mb-2" data-id="' . $node->id . '">';
            $html .= '  <div class="dd-handle d-flex align-items-center justify-content-between p-2.5 bg-white border rounded shadow-sm hover-card" style="cursor: move;' . $borderStyle . '">';
            $html .= '    <div class="d-flex align-items-center">';
            $html .= '      <span class="drag-handle text-muted me-3" style="cursor: grab;">⠿</span>';
            $html .= '      <span class="fw-bold text-dark node-title" style="font-size: 13px;">' . $iconHtml . e($node->title) . '</span>';
            $html .=        $linkBadge;
            $html .=        $descHtml;
            $html .= '    </div>';
            $html .= '    <div class="action-buttons d-flex gap-1.5">';
            $html .= '      <button class="btn btn-xs btn-outline-primary btn-add-child-node px-1.5 py-0.5" data-id="' . $node->id . '" title="Thêm nút con" style="font-size: 10px;">＋ Con</button>';
            $html .= '      <button class="btn btn-xs btn-outline-secondary btn-edit-node px-1.5 py-0.5" data-id="' . $node->id . '" data-title="' . e($node->title) . '" data-icon="' . e($node->icon) . '" data-type="' . e($node->nodeable_type) . '" data-type-id="' . e($node->nodeable_id) . '" data-description="' . e($node->metadata['description'] ?? '') . '" data-color="' . e($node->metadata['color'] ?? '') . '" title="Chỉnh sửa" style="font-size: 10px;">✏️</button>';
            $html .= '      <button class="btn btn-xs btn-outline-danger btn-delete-node px-1.5 py-0.5" data-id="' . $node->id . '" title="Xóa nút" style="font-size: 10px;">❌</button>';
            $html .= '    </div>';
            $html .= '  </div>';
            
            // Recurse children
            $childrenHtml = $this->buildTreeHtml($nodes, $node->id);
            if (empty($childrenHtml)) {
                $html .= '<ol class="dd-list list-group" style="list-style: none; padding-left: 20px; min-height: 10px; margin-top: 5px;"></ol>';
            } else {
                $html .= $childrenHtml;
            }
            
            $html .= '</li>';
        }
        $html .= '</ol>';

        return $html;
    }

    /**
     * Export the hierarchy tree structure as a PDF.
     */
    public function exportPdf($id)
    {
        $tree = HierarchyTree::with('nodes')->findOrFail($id);
        $tree->name = $this->stripEmojis($tree->name);
        $textTree = $this->generateTextTree($tree->nodes, null, '');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('hierarchy.pdf', compact('tree', 'textTree'));
        
        return $pdf->download('hierarchy_' . $tree->code . '.pdf');
    }

    /**
     * Recursive helper to build the text-based folder tree (using ├── and └──).
     */
    private function generateTextTree($nodes, $parentId = null, $prefix = '')
    {
        $text = '';
        $filtered = $nodes->where('parent_id', $parentId)->sortBy('order');
        $count = $filtered->count();
        $i = 0;

        foreach ($filtered as $node) {
            $i++;
            $isLast = ($i === $count);

            // Choose the branch character
            $branch = $isLast ? '└── ' : '├── ';

            // Clean title by stripping any emojis
            $cleanTitle = $this->stripEmojis($node->title);
            $line = $prefix . $branch . $cleanTitle;

            // [MỚI] Ghép phần chú thích căn lề thẳng cột bằng ký tự trống
            if (!empty($node->metadata['description'])) {
                $lineLen = mb_strlen($line);
                $targetLen = 45; // Căn lề mũi tên bắt đầu ở cột thứ 45
                $spaces = $targetLen > $lineLen ? str_repeat(' ', $targetLen - $lineLen) : '   ';
                $line .= $spaces . '← ' . $node->metadata['description'];
            }

            // [MỚI] Bọc thẻ màu nếu có cấu hình màu sắc cho nút
            if (!empty($node->metadata['color'])) {
                $line = '<span style="color: ' . $node->metadata['color'] . ';">' . e($line) . '</span>';
            } else {
                $line = e($line);
            }

            $text .= $line . "\n";

            // Next level prefix
            $nextPrefix = $prefix . ($isLast ? '    ' : '│   ');

            // Recurse
            $text .= $this->generateTextTree($nodes, $node->id, $nextPrefix);
        }

        return $text;
    }

    /**
     * Helper to strip emojis from text.
     */
    private function stripEmojis($text)
    {
        $pattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1F018}-\x{1F0F5}\x{2600}-\x{26FF}\x{1F1E6}-\x{1F1FF}]/u';
        return trim(preg_replace($pattern, '', $text));
    }

    /**
     * Save a snapshot of the current tree nodes as a new version.
     */
    public function saveVersion(Request $request, $id)
    {
        $tree = HierarchyTree::with('nodes')->findOrFail($id);
        
        $request->validate([
            'note' => 'nullable|string|max:255',
        ]);

        $nodes = $tree->nodes;
        if ($nodes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo phiên bản cho cây rỗng!'
            ], 422);
        }

        // Map nodes into a clean array for JSON storage
        $snapshot = $nodes->map(function ($node) {
            return [
                'id' => $node->id,
                'parent_id' => $node->parent_id,
                'title' => $node->title,
                'icon' => $node->icon,
                'nodeable_type' => $node->nodeable_type,
                'nodeable_id' => $node->nodeable_id,
                'order' => $node->order,
                'metadata' => $node->metadata,
            ];
        })->toArray();

        $latestVersionNumber = HierarchyVersion::where('tree_id', $tree->id)->max('version_number') ?? 0;
        $newVersionNumber = $latestVersionNumber + 1;

        $version = HierarchyVersion::create([
            'tree_id' => $tree->id,
            'version_number' => $newVersionNumber,
            'note' => $request->note ?: 'Phiên bản #' . $newVersionNumber,
            'nodes_snapshot' => $snapshot,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu phiên bản mới #' . $newVersionNumber . ' thành công!',
            'version' => $version
        ]);
    }

    /**
     * Restore the tree to a previous version snapshot.
     */
    public function restoreVersion($versionId)
    {
        $version = HierarchyVersion::findOrFail($versionId);
        $tree = HierarchyTree::findOrFail($version->tree_id);
        $snapshotNodes = $version->nodes_snapshot;

        DB::beginTransaction();
        try {
            // Delete current nodes
            $tree->nodes()->delete();

            // Rebuild nodes recursively starting from root nodes (where parent_id is null)
            $this->restoreNodesRecursive($snapshotNodes, null, null, $tree->id);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã khôi phục cây về phiên bản #' . $version->version_number . ' thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khôi phục phiên bản: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recursive helper to recreate nodes with brand new IDs while maintaining parent-child relations.
     */
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

            // Recurse for children
            $this->restoreNodesRecursive($snapshotNodes, $nodeData['id'], $newNode->id, $treeId);
        }
    }
}
