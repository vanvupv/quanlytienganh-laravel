<?php

namespace App\Http\Controllers\Listening;

use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use App\Models\Listening\Listening;
use App\Models\Listening\ListeningAssessment;
use App\Models\Listening\ListeningSession;
use App\Models\Listening\ListeningTopic;
use App\Models\Listening\ListeningTranscript;
use App\Models\Listening\ListeningWeeklyStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListeningController extends Controller
{
    // =========================================================
    // INDEX: Trang chủ kỹ năng Listening
    // =========================================================
    public function index()
    {
        // Danh sách tài liệu có file nghe, nhóm theo cấp độ
        $documents = Document::where('skill', 'listening')
            ->orWhereHas('listenings')
            ->with(['parts.units.listenings', 'listenings'])
            ->withCount('listenings')
            ->orderBy('level')
            ->get()
            ->groupBy('level');

        // Tất cả file nghe (cho danh sách nhanh)
        $doc_listening = Listening::with(['topic', 'transcripts', 'document', 'chapter'])
            ->latest()
            ->get();

        // Thống kê tổng quan
        $stats = [
            'total_docs'       => Document::count(),
            'total_files'      => Listening::count(),
            'total_duration'   => Listening::sum('duration'),
            'levels'           => Listening::distinct()->pluck('level')->filter()->sort()->values(),
            'user_sessions'    => ListeningSession::count(),
            'user_passed'      => ListeningSession::where('is_passed', true)->count(),
            'avg_score'        => ListeningAssessment::avg('score_percent') ?? 0,
            'avg_efficiency'   => ListeningSession::where('is_completed', true)->avg('efficiency_score') ?? 0,
        ];

        $isSqlite = DB::getDriverName() === 'sqlite';
        $weekFormat = $isSqlite ? "strftime('%Y-%W', listened_at)" : "DATE_FORMAT(listened_at, '%Y-%u')";
        $monthFormat = $isSqlite ? "strftime('%Y-%m', listened_at)" : "DATE_FORMAT(listened_at, '%Y-%m')";

        // Tần suất nghe theo tuần (8 tuần gần nhất)
        $weeklyFreq = ListeningSession::select(
                DB::raw("$weekFormat as week_key"),
                DB::raw("MIN(listened_at) as week_start"),
                DB::raw("count(*) as session_count")
            )
            ->groupBy('week_key')
            ->orderBy('week_key', 'asc')
            ->take(8)
            ->get()
            ->map(function($item) {
                return [
                    'label' => 'Tuần ' . date('W', strtotime($item->week_start)),
                    'count' => $item->session_count
                ];
            });

        // Tần suất nghe theo tháng (6 tháng gần nhất)
        $monthlyFreq = ListeningSession::select(
                DB::raw("$monthFormat as month_key"),
                DB::raw("MIN(listened_at) as month_start"),
                DB::raw("count(*) as session_count")
            )
            ->groupBy('month_key')
            ->orderBy('month_key', 'asc')
            ->take(6)
            ->get()
            ->map(function($item) {
                return [
                    'label' => 'Tháng ' . date('m/Y', strtotime($item->month_start)),
                    'count' => $item->session_count
                ];
            });

        return view('skills.listening.listening', compact(
            'documents', 'doc_listening', 'stats', 'weeklyFreq', 'monthlyFreq'
        ));
    }

    public function adminIndex()
    {
        return $this->index();
    }

    // =========================================================
    // DOCUMENT: Danh mục tài liệu
    // =========================================================
    public function documentIndex()
    {
        $documents = Document::with(['parts', 'listenings'])
            ->withCount('listenings')
            ->orderBy('level')
            ->orderBy('name')
            ->get()
            ->groupBy('level');

        return view('skills.listening.document.index', compact('documents'));
    }

    // =========================================================
    // DOCUMENT SHOW: Chi tiết tài liệu (6 nhóm + bảng thống kê)
    // =========================================================
    public function documentShow(int $id)
    {
        $document = Document::with([
            'parts.units.listenings.transcripts',
            'parts.units.listenings.sessions',
            'listenings.transcripts',
            'listenings.sessions',
            'creator',
        ])->findOrFail($id);

        // Thống kê theo chương
        $chapterStats = $document->parts->map(function ($part) {
            // Tập hợp tất cả listenings trong chương (qua units + trực tiếp)
            $listenings = collect();
            foreach ($part->units as $unit) {
                $listenings = $listenings->merge($unit->listenings);
            }
            $directListenings = $part->listenings;
            $listenings = $listenings->merge($directListenings);

            $totalDuration = $listenings->sum('duration');
            $h = floor($totalDuration / 3600);
            $m = floor(($totalDuration % 3600) / 60);
            $formattedDuration = $h > 0 ? "{$h}g {$m}p" : "{$m} phút";

            return [
                'id'               => $part->id,
                'name'             => $part->name,
                'order'            => $part->order,
                'file_count'       => $listenings->count(),
                'total_duration'   => $totalDuration,
                'formatted_duration' => $formattedDuration,
                'listenings'       => $listenings,
            ];
        });

        // Thống kê chi tiết từng file nghe
        $listeningDetails = Listening::where('document_id', $id)
            ->with(['sessions', 'assessments', 'transcripts', 'chapter'])
            ->get()
            ->map(function ($item) {
                $listenCount = $item->sessions()->count();
                $passCount   = $item->sessions()->where('is_passed', true)->count();
                $totalDur    = $item->sessions()->sum('duration_seconds');

                return array_merge((array)$item->toArray(), [
                    'listen_count'   => $listenCount,
                    'pass_count'     => $passCount,
                    'total_listened' => $totalDur,
                    'efficiency'     => $item->efficiency(),
                ]);
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
            'document', 'chapterStats', 'listeningDetails', 'weeklyTimeline'
        ));
    }

    // =========================================================
    // CREATE / STORE: Thêm file nghe mới
    // =========================================================
    public function create()
    {
        $documents = Document::orderBy('name')->get();
        $topics    = ListeningTopic::orderBy('name')->get();

        return view('skills.listening.session.form', compact('documents', 'topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'link'        => 'nullable|string|max:500',
            'type'        => 'nullable|string|max:20',
            'size'        => 'nullable|string|max:50',
            'duration'    => 'nullable|integer|min:0',
            'level'       => 'nullable|string|max:10',
            'score_level' => 'nullable|numeric|min:0|max:100',
            'document_id' => 'nullable|exists:documents,id',
            'chapter_id'  => 'nullable|exists:document_parts,id',
            'unit_id'     => 'nullable|exists:document_units,id',
            'topic_id'    => 'nullable|exists:listening_topics,id',
            'transcript'  => 'nullable|string',
            'translation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $listening = Listening::create($validated);

            // Lưu transcript gốc
            if (!empty($validated['transcript'])) {
                $words = str_word_count($validated['transcript']);
                $sents = preg_match_all('/[.!?]+/', $validated['transcript']) ?: 1;
                ListeningTranscript::create([
                    'listening_id'   => $listening->id,
                    'type'           => 'original',
                    'language_code'  => 'en',
                    'transcript'     => $validated['transcript'],
                    'word_count'     => $words,
                    'sentence_count' => $sents,
                ]);

                // Tách câu & Đồng bộ thời gian
                if ($request->has('sentences') && is_array($request->sentences)) {
                    foreach ($request->sentences as $idx => $sent) {
                        if (!empty($sent['text'])) {
                            $listening->sentences()->create([
                                'sentence_text' => $sent['text'],
                                'start_time'    => floatval($sent['start_time'] ?? 0),
                                'end_time'      => floatval($sent['end_time'] ?? 0),
                                'order'         => intval($sent['order'] ?? ($idx + 1)),
                            ]);
                        }
                    }
                } else {
                    // Tách câu mặc định
                    $sentencesText = preg_split('/(?<=[.!?])\s+/', $validated['transcript']);
                    foreach ($sentencesText as $idx => $text) {
                        $text = trim($text);
                        if (!empty($text)) {
                            $listening->sentences()->create([
                                'sentence_text' => $text,
                                'start_time'    => 0.00,
                                'end_time'      => 0.00,
                                'order'         => $idx + 1,
                            ]);
                        }
                    }
                }
            }

            // Lưu bản dịch
            if (!empty($validated['translation'])) {
                ListeningTranscript::create([
                    'listening_id'  => $listening->id,
                    'type'          => 'translation',
                    'language_code' => 'vi',
                    'transcript'    => $validated['translation'],
                ]);
            }
        });

        return redirect()->route('listening.index')
            ->with('success', '✅ Đã thêm file nghe thành công!');
    }

    // =========================================================
    // SHOW: Chi tiết + giao diện luyện nghe
    // =========================================================
    // =========================================================
    // SHOW: Chi tiết + giao diện luyện nghe
    // =========================================================
    public function show(int $id)
    {
        $listening = Listening::with([
            'transcripts', 'document', 'chapter', 'topic', 'sentences',
            'sessions' => fn($q) => $q->latest()->take(20),
            'assessments' => fn($q) => $q->latest()->take(10),
            'weeklyStats' => fn($q) => $q->orderBy('week_start', 'desc')->take(8),
        ])->findOrFail($id);

        $original    = $listening->transcripts->where('type', 'original')->first();
        $translation = $listening->transcripts->where('type', 'translation')->first();

        // Lịch sử nghe theo tuần
        $weeklyStats = $listening->weeklyStats;

        return view('skills.listening.session.practice', compact(
            'listening', 'original', 'translation', 'weeklyStats'
        ));
    }

    // =========================================================
    // EDIT / UPDATE
    // =========================================================
    public function edit(int $id)
    {
        $listening = Listening::with(['transcripts', 'sentences'])->findOrFail($id);
        $documents = Document::orderBy('name')->get();
        $topics    = ListeningTopic::orderBy('name')->get();

        return view('skills.listening.session.form', compact('listening', 'documents', 'topics'));
    }

    public function update(Request $request, int $id)
    {
        $listening = Listening::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'link'        => 'nullable|string|max:500',
            'type'        => 'nullable|string|max:20',
            'size'        => 'nullable|string|max:50',
            'duration'    => 'nullable|integer|min:0',
            'level'       => 'nullable|string|max:10',
            'score_level' => 'nullable|numeric|min:0|max:100',
            'document_id' => 'nullable|exists:documents,id',
            'chapter_id'  => 'nullable|exists:document_parts,id',
            'unit_id'     => 'nullable|exists:document_units,id',
            'topic_id'    => 'nullable|exists:listening_topics,id',
            'transcript'  => 'nullable|string',
            'translation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($listening, $validated, $request) {
            $listening->update($validated);

            // Cập nhật transcript gốc
            if (isset($validated['transcript'])) {
                $words = str_word_count($validated['transcript']);
                $sents = preg_match_all('/[.!?]+/', $validated['transcript']) ?: 1;
                $listening->transcripts()->updateOrCreate(
                    ['type' => 'original'],
                    ['transcript' => $validated['transcript'], 'word_count' => $words, 'sentence_count' => $sents, 'language_code' => 'en']
                );

                // Đồng bộ câu thoại & mốc thời gian
                if ($request->has('sentences') && is_array($request->sentences)) {
                    $listening->sentences()->delete();
                    foreach ($request->sentences as $idx => $sent) {
                        if (!empty($sent['text'])) {
                            $listening->sentences()->create([
                                'sentence_text' => $sent['text'],
                                'start_time'    => floatval($sent['start_time'] ?? 0),
                                'end_time'      => floatval($sent['end_time'] ?? 0),
                                'order'         => intval($sent['order'] ?? ($idx + 1)),
                            ]);
                        }
                    }
                } else {
                    // Tự động phân tách lại nếu không gửi lên mảng câu
                    $listening->sentences()->delete();
                    $sentencesText = preg_split('/(?<=[.!?])\s+/', $validated['transcript']);
                    foreach ($sentencesText as $idx => $text) {
                        $text = trim($text);
                        if (!empty($text)) {
                            $listening->sentences()->create([
                                'sentence_text' => $text,
                                'start_time'    => 0.00,
                                'end_time'      => 0.00,
                                'order'         => $idx + 1,
                            ]);
                        }
                    }
                }
            }

            // Cập nhật bản dịch
            if (isset($validated['translation'])) {
                $listening->transcripts()->updateOrCreate(
                    ['type' => 'translation'],
                    ['transcript' => $validated['translation'], 'language_code' => 'vi']
                );
            }
        });

        return redirect()->route('listening.show', $listening->id)
            ->with('success', '✅ Đã cập nhật file nghe!');
    }

    // =========================================================
    // DESTROY
    // =========================================================
    public function destroy(int $id)
    {
        Listening::findOrFail($id)->delete();
        return redirect()->route('listening.index')
            ->with('success', '🗑️ Đã xóa file nghe!');
    }

    // =========================================================
    // SESSION: Bắt đầu / kết thúc phiên nghe
    // =========================================================
    public function startSession(Request $request, int $id)
    {
        $listening = Listening::findOrFail($id);

        $attemptNumber = $listening->sessions()->count() + 1;

        $session = ListeningSession::create([
            'listening_id'  => $id,
            'user_id'       => auth()->id(),
            'attempt_number' => $attemptNumber,
            'duration_seconds' => 0,
            'is_completed'  => false,
            'is_passed'     => false,
        ]);

        // Tăng listen_count
        $listening->increment('listen_count');

        return response()->json([
            'session_id'    => $session->id,
            'attempt_number' => $attemptNumber,
            'message'       => "Bắt đầu lần nghe thứ {$attemptNumber}",
        ]);
    }

    // =========================================================
    // ASSESSMENT: Nộp transcript để đánh giá
    // =========================================================
    public function submitAssessment(Request $request, int $id)
    {
        $request->validate([
            'submitted_transcript' => 'required|string',
            'session_id'           => 'nullable|exists:listening_sessions,id',
            'duration_spent'       => 'nullable|integer|min:0',
        ]);

        $listening = Listening::with('transcripts')->findOrFail($id);
        $original  = $listening->transcripts->where('type', 'original')->first();

        if (!$original) {
            return response()->json(['error' => 'Chưa có transcript gốc cho file này.'], 422);
        }

        // Tính điểm
        $scoreData = ListeningAssessment::calculateScore(
            $request->submitted_transcript,
            $original->transcript
        );

        // Số lần đã nộp
        $attemptNumber = $listening->assessments()
            ->when(auth()->id(), fn($q) => $q->where('user_id', auth()->id()))
            ->count() + 1;

        // Tính hiệu suất: lần 1 = 100%, giảm dần
        $efficiency = max(10, round(100 / $attemptNumber, 2));

        $isPassed = $scoreData['score_percent'] >= 80; // ngưỡng đạt 80%

        $assessment = ListeningAssessment::create([
            'listening_id'         => $id,
            'user_id'              => auth()->id(),
            'session_id'           => $request->session_id,
            'attempt_number'       => $attemptNumber,
            'submitted_transcript' => $request->submitted_transcript,
            'original_transcript'  => $original->transcript,
            'score_percent'        => $scoreData['score_percent'],
            'correct_words'        => $scoreData['correct_words'],
            'total_words'          => $scoreData['total_words'],
            'duration_spent'       => $request->duration_spent ?? 0,
            'is_passed'            => $isPassed,
            'efficiency_score'     => $efficiency,
            'assessed_at'          => now(),
        ]);

        // Cập nhật session nếu có
        if ($request->session_id) {
            $session = ListeningSession::find($request->session_id);
            if ($session) {
                $session->update([
                    'is_completed'    => true,
                    'is_passed'       => $isPassed,
                    'efficiency_score' => $efficiency,
                    'duration_seconds' => $request->duration_spent ?? 0,
                ]);
                // Ghi thống kê tuần
                ListeningWeeklyStat::recordSession($session);
            }
        }

        return response()->json([
            'score_percent'    => $scoreData['score_percent'],
            'correct_words'    => $scoreData['correct_words'],
            'total_words'      => $scoreData['total_words'],
            'is_passed'        => $isPassed,
            'efficiency'       => $efficiency,
            'attempt_number'   => $attemptNumber,
            'assessment_id'    => $assessment->id,
        ]);
    }

    // =========================================================
    // RESULT: Kết quả + Lịch sử nghe
    // =========================================================
    public function result(int $id)
    {
        $listening = Listening::with([
            'transcripts', 'document', 'chapter',
            'sessions' => fn($q) => $q->latest(),
            'assessments' => fn($q) => $q->latest(),
            'weeklyStats' => fn($q) => $q->orderBy('week_start'),
        ])->findOrFail($id);

        return view('skills.listening.session.result', compact('listening'));
    }

    // =========================================================
    // LEGACY: Cây tài liệu (giữ lại tương thích)
    // =========================================================
    public function showDocumentTree(int $documentId)
    {
        $document = Document::with('parts.units.listenings')->findOrFail($documentId);
        return view('skills.listening.tree', compact('document'));
    }
}
