<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use App\Models\Document\DocumentPart;
use App\Models\Document\DocumentUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Listening\Listening;
use App\Models\Listening\ListeningWeeklyStat;

class DocumentController extends Controller
{
    // =========================================================
    // INDEX: Danh sách tài liệu
    // =========================================================
    public function index()
    {
        $documents = Document::withCount('listenings')
            ->orderBy('level')
            ->orderBy('name')
            ->get()
            ->groupBy('level');

        return view('skills.listening.document.index', compact('documents'));
    }

    public function adminIndex()
    {
        return $this->index();
    }

    // =========================================================
    // CREATE
    // =========================================================
    public function create()
    {
        return view('skills.listening.document.form');
    }

    // =========================================================
    // STORE
    // =========================================================
    public function store(Request $request)
    {
        $validated = $this->validateDocument($request);

        // Xử lý upload ảnh bìa
        if ($request->hasFile('cover_image_file')) {
            $path = $request->file('cover_image_file')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        DB::transaction(function () use ($validated, $request) {
            $document = Document::create($validated);

            // Tạo các chương từ JSON nếu có
            if ($request->has('chapters') && is_array($request->chapters)) {
                foreach ($request->chapters as $i => $chapterName) {
                    if (!empty($chapterName)) {
                        DocumentPart::create([
                            'document_id' => $document->id,
                            'name'        => $chapterName,
                            'order'       => $i + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('document.index')
            ->with('success', '✅ Đã thêm tài liệu thành công!');
    }

    // =========================================================
    // SHOW: Chi tiết tài liệu (6 nhóm)
    // =========================================================
    public function show(int $id)
    {
        $document = Document::with([
            'parts.units.listenings.transcripts',
            'parts.units.listenings.sessions',
            'listenings.transcripts',
            'listenings.sessions',
            'creator',
        ])->findOrFail($id);

        // Thống kê từng chương
        $chapterStats = $document->parts->map(function ($part) {
            $listenings = collect();
            foreach ($part->units as $unit) {
                $listenings = $listenings->merge($unit->listenings);
            }
            $listenings = $listenings->merge($part->listenings);

            $totalDuration = $listenings->sum('duration');
            $h = floor($totalDuration / 3600);
            $m = floor(($totalDuration % 3600) / 60);

            return [
                'part'             => $part,
                'file_count'       => $listenings->count(),
                'total_duration'   => $totalDuration,
                'formatted'        => $h > 0 ? "{$h}g {$m}p" : "{$m} phút",
                'listenings'       => $listenings,
            ];
        });

        $totalFiles    = $document->listenings()->count();
        $totalDuration = $document->listenings()->sum('duration');
        $th = floor($totalDuration / 3600);
        $tm = floor(($totalDuration % 3600) / 60);
        $formattedTotal = $th > 0 ? "{$th}g {$tm}p" : "{$tm} phút";

        // Thống kê chi tiết từng file nghe
        $listeningDetails = Listening::where('document_id', $id)
            ->with(['sessions', 'assessments', 'transcripts', 'chapter'])
            ->get()
            ->map(function ($item) {
                $listenCount = $item->sessions()->count();
                $passCount   = $item->sessions()->where('is_passed', true)->count();
                $totalDur    = $item->sessions()->sum('duration_seconds');

                return collect(array_merge($item->toArray(), [
                    'listen_count'   => $listenCount,
                    'pass_count'     => $passCount,
                    'total_listened' => $totalDur,
                    'efficiency'     => $item->efficiency(),
                ]));
            });

        // Timeline nghe theo tuần (8 tuần gần nhất)
        $weeklyTimeline = ListeningWeeklyStat::whereIn(
                'listening_id',
                Listening::where('document_id', $id)->pluck('id')
            )
            ->orderBy('week_start', 'desc')
            ->take(80)
            ->get()
            ->groupBy('listening_id');

        return view('skills.listening.document.show', compact(
            'document', 'chapterStats', 'totalFiles', 'formattedTotal', 'listeningDetails', 'weeklyTimeline'
        ));
    }

    // =========================================================
    // EDIT
    // =========================================================
    public function edit(int $id)
    {
        $document = Document::with('parts.units')->findOrFail($id);
        return view('skills.listening.document.form', compact('document'));
    }

    // =========================================================
    // UPDATE
    // =========================================================
    public function update(Request $request, int $id)
    {
        $document  = Document::findOrFail($id);
        $validated = $this->validateDocument($request);

        if ($request->hasFile('cover_image_file')) {
            $path = $request->file('cover_image_file')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $document->update($validated);

        return redirect()->route('document.show', $id)
            ->with('success', '✅ Đã cập nhật tài liệu!');
    }

    // =========================================================
    // DESTROY
    // =========================================================
    public function destroy(int $id)
    {
        Document::findOrFail($id)->delete();
        return redirect()->route('document.index')
            ->with('success', '🗑️ Đã xóa tài liệu!');
    }

    // =========================================================
    // Helper: Validation
    // =========================================================
    private function validateDocument(Request $request): array
    {
        return $request->validate([
            // Nhóm 1
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'skill'              => 'nullable|string|in:listening,reading,speaking,writing',
            'level'              => 'nullable|string|in:A1,A2,B1,B2,C1,C2',
            'proficiency'        => 'nullable|string',
            // Nhóm 2
            'author'             => 'nullable|string|max:255',
            'publisher'          => 'nullable|string|max:255',
            'published_year'     => 'nullable|integer|min:1900|max:' . date('Y'),
            'version'            => 'nullable|string|max:50',
            'isbn'               => 'nullable|string|max:20',
            'language'           => 'nullable|string|max:10',
            // Nhóm 3
            'cover_image'        => 'nullable|string',
            'page_count'         => 'nullable|integer|min:0',
            'dimensions'         => 'nullable|string|max:50',
            'file_size'          => 'nullable|string|max:50',
            'file_format'        => 'nullable|string|max:50',
            'total_lessons'      => 'nullable|integer|min:0',
            // Nhóm 4
            'status'             => 'nullable|string|in:active,inactive,archived',
            // Nhóm 5
            'overview'           => 'nullable|string',
            'chapter_list'       => 'nullable|array',
            'chapter_contents'   => 'nullable|array',
            'chapter_volumes'    => 'nullable|array',
            // Nhóm 6
            'learning_objective' => 'nullable|string',
            'study_duration'     => 'nullable|integer|min:0',
            'review_duration'    => 'nullable|integer|min:0',
            'assessment'         => 'nullable|string',
            'completion_criteria' => 'nullable|array',
            'journey_status'     => 'nullable|string|in:not_started,in_progress,completed',
            'is_passed'          => 'nullable|boolean',
        ]);
    }
}
