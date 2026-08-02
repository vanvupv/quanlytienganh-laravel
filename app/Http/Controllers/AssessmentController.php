<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use App\Models\Result;
use App\Models\UserAssessment;
use Illuminate\Http\Request;
//  Model    
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\Vocabulary;
use Carbon\Carbon;

//
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

//
use Barryvdh\DomPDF\Facade\Pdf;

//


class AssessmentController extends Controller
{
    // Index (Student View)
    public function index()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // --- Thống kê nhanh ---
        // -- Lấy tổng buổi sắp diễn ra
        $upcomingCount = Assessment::where('start_time', '>', $now)
            ->count();

        // -- Lấy tổng buổi đang diễn ra  
        $ongoingCount = Assessment::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->count();

        // -- Lấy tổng buổi đã hoàn thành
        $finishedCount = Assessment::where('end_time', '<', $now)
            ->count();


        // --- Danh sách buổi ---
        // -- Lấy danh sách 
        $upcomingAssessments = Assessment::where('start_time', '>', $now)
            ->withCount('userAssessments')              // số học sinh tham gia
            ->withAvg('userAssessments', 'score')       // điểm trung bình                   
            ->orderBy('start_time', 'asc')
            ->get();

        $ongoingAssessments = Assessment::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->withCount('userAssessments')
            ->withAvg('userAssessments', 'score')
            ->orderBy('start_time', 'asc')
            ->get();

        $assessment_full = Assessment::with([
            'details.results',     // chi tiết đề + kết quả theo từng câu
            'results',             // toàn bộ kết quả (nếu bạn lưu riêng)
            'userAssessments.user' // thông tin làm bài + user
        ])
            ->where('end_time', '<=', $now)
            ->orderBy('start_time', 'desc') // sắp xếp từ mới -> cũ
            ->get();

        // Return Data
        return view('assessments.index', compact(
            'upcomingCount',
            'ongoingCount',
            'finishedCount',
            'upcomingAssessments',
            'ongoingAssessments',
            'assessment_full'
        ));
    }

    // Admin Index (Management Panel)
    public function adminIndex()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // --- Thống kê nhanh ---
        // -- Lấy tổng buổi sắp diễn ra
        $upcomingCount = Assessment::where('start_time', '>', $now)
            ->count();

        // -- Lấy tổng buổi đang diễn ra  
        $ongoingCount = Assessment::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->count();

        // -- Lấy tổng buổi đã hoàn thành
        $finishedCount = Assessment::where('end_time', '<', $now)
            ->count();


        // --- Danh sách buổi ---
        // -- Lấy danh sách 
        $upcomingAssessments = Assessment::where('start_time', '>', $now)
            ->withCount('userAssessments')              // số học sinh tham gia
            ->withAvg('userAssessments', 'score')       // điểm trung bình                   
            ->orderBy('start_time', 'asc')
            ->get();

        $ongoingAssessments = Assessment::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->withCount('userAssessments')
            ->withAvg('userAssessments', 'score')
            ->orderBy('start_time', 'asc')
            ->get();

        $assessment_full = Assessment::with([
            'details.results',     // chi tiết đề + kết quả theo từng câu
            'results',             // toàn bộ kết quả (nếu bạn lưu riêng)
            'userAssessments.user' // thông tin làm bài + user
        ])
            ->where('end_time', '<=', $now)
            ->orderBy('start_time', 'desc') // sắp xếp từ mới -> cũ
            ->get();

        // Return Data
        return view('assessments.admin.index', compact(
            'upcomingCount',
            'ongoingCount',
            'finishedCount',
            'upcomingAssessments',
            'ongoingAssessments',
            'assessment_full'
        ));
    }

    // Hàm Create (Hàm tạo)            
    public function store(Request $request)
    {

        // dd($request);         

        // Validate  
        try {
            $data = $request->validate([
                'topic' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'from' => 'nullable|integer|min:1',
                'to' => 'nullable|integer|min:1',
                'duration' => 'required|integer|min:1',
                'method' => 'required|string|max:255',
                'level' => 'nullable|string|max:50',
                'difficulty' => 'nullable|string|max:50',
                'require_completion' => 'nullable|integer|min:0|max:100',
                'require_score' => 'nullable|integer|min:0|max:100',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after_or_equal:start_time',
                'note' => 'nullable|string|max:1000',
            ]);

            //dd($data); // nếu hợp lệ sẽ in ra dữ liệu đã validate
        } catch (ValidationException $e) {
            dd($e->errors()); // 👈 đây là mảng lỗi validate
        }

        // dd($data);

        //
        $start = Carbon::now();
        $end = $start->copy()->addMinutes($data['duration']);

        DB::beginTransaction();

        try {
            // 1. Tạo buổi 

            // map topic từ request về enum type
            $topicMap = [
                'Từ vựng' => 'vocabulary',
                'Ngữ pháp' => 'grammar',
                'Hỗn hợp' => 'mixed',
            ];

            $type = $topicMap[$data['topic']] ?? 'mixed';

            //       
            $assessment = Assessment::create([
                'title' => $request->input('title') ?? ($data['topic'] . ' - ' . $data['method']),
                'type' => $type, // hoặc map về enum tiếng Anh nếu cần
                'method' => $data['method'],
                'quantity' => $data['quantity'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'duration' => $data['duration'],
                'note' => $data['note'],
                'location' => $request->input('location') ?? 'Hà Nội',
                'min_score' => $data['require_score'] ?? 0,
                'min_performance' => $data['require_completion'] ?? 0,
            ]);

            // 2. Sinh chi tiết buổi đánh giá
            switch ($type) {
                case 'vocabulary':
                    $query = Vocabulary::query();

                    if (!empty($data['from']) && !empty($data['to'])) {
                        // Nếu có khoảng thì lấy theo khoảng
                        $words = $query->whereBetween('id', [$data['from'], $data['to']])
                            ->inRandomOrder()
                            ->limit($data['quantity'])
                            ->get();
                    } else {
                        // Ngược lại thì lấy random toàn bộ
                        $words = $query->inRandomOrder()
                            ->limit($data['quantity'])
                            ->get();
                    }

                    foreach ($words as $word) {
                        AssessmentDetail::create([
                            'assessment_id' => $assessment->id,
                            'vocabulary_id' => $word->id,
                            'question' => "Nghĩa của từ '{$word->word}' là gì?",
                            'answer' => $word->meaning,
                            'skill' => 'vocabulary',
                            'point' => 1,
                        ]);
                    }
                    break;

                case 'grammar':
                    $grammars = Grammar::inRandomOrder()
                        ->limit($data['quantity'])
                        ->get();

                    foreach ($grammars as $grammar) {
                        AssessmentDetail::create([
                            'assessment_id' => $assessment->id,
                            'grammar_id' => $grammar->id,
                            'question' => $grammar->question,
                            'answer' => $grammar->answer,
                            'skill' => 'grammar',
                            'point' => 1,
                        ]);
                    }
                    break;

                case 'mixed':
                    $qty = (int) ceil($data['quantity'] / 2); // chia đều vocabulary và grammar

                    // vocabulary
                    $words = Vocabulary::inRandomOrder()->limit($qty)->get();
                    foreach ($words as $word) {
                        AssessmentDetail::create([
                            'assessment_id' => $assessment->id,
                            'vocabulary_id' => $word->id,
                            'question' => "Nghĩa của từ '{$word->word}' là gì?",
                            'answer' => $word->meaning,
                            'skill' => 'vocabulary',
                            'point' => 1,
                        ]);
                    }

                    // grammar
                    $grammars = Grammar::inRandomOrder()->limit($qty)->get();
                    foreach ($grammars as $grammar) {
                        AssessmentDetail::create([
                            'assessment_id' => $assessment->id,
                            'grammar_id' => $grammar->id,
                            'question' => $grammar->question,
                            'answer' => $grammar->answer,
                            'skill' => 'grammar',
                            'point' => 1,
                        ]);
                    }
                    break;
            }

            // TODO: grammar, mixed...

            DB::commit();

            return redirect()->route('assessments.do', $assessment->id)
                ->with('success', 'Tạo buổi đánh giá thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra khi tạo buổi đánh giá',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Hàm Get (Lấy đề thi)
    public function show($id)
    {
        $assessment = Assessment::with('details')->findOrFail($id);

        return response()->json([
            'message' => 'Lấy đề thi thành công',
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'type' => $assessment->type,
                'duration' => $assessment->duration,
                'questions' => $assessment->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'question' => $detail->question,
                        'skill' => $detail->skill,
                        // Nếu sau này có multiple-choice thì trả cả options ra
                    ];
                }),
            ]
        ]);
    }

    // Hàm Get (Làm đề thi)
    public function doExam($id)
    {
        // Lấy thông tin Buổi đánh giá
        $assessment = Assessment::findOrFail($id);

        // dd($assessment->details);

        // Lấy thông tin Người đánh giá
        $userAssessment = UserAssessment::firstOrCreate(
            [
                'user_id' => auth()->id() ?? '1',
                'assessment_id' => $assessment->id
            ],
            [
                'started_at' => now()
            ]
        );

        // 
        // dd([
        //     'Thời gian bắt đầu' => $userAssessment->started_at,
        //     'Thời gian kết thúc' => $userAssessment->finished_at,
        //     "Thời gian kết thúc thực tế" => $endTime,
        //     "Thời gian còn lại" => $remainingSeconds,
        // ]);

        // End time = start + duration
        $endTime = $userAssessment->started_at->addMinutes($assessment->duration);
        $remainingSeconds = max(0, $endTime->timestamp - now()->timestamp);

        //
        return view(
            'assessments.do',
            [
                'assessment' => $assessment,
                'endTime' => $endTime,
                'remainingSeconds' => $remainingSeconds
            ]
        );
    }

    // Hàm Set (Gửi kết quả bài làm)                     
    public function submit(Request $request, $id)
    {
        $assessment = Assessment::with('details')->findOrFail($id);
        $answers = $request->input('answers', []);
        $userId = auth()->id() ?? 1;

        DB::beginTransaction();
        try {
            // Tạo hoặc lấy bản ghi user_assessment (nếu chưa có thì set started_at)
            $userAssessment = UserAssessment::firstOrCreate(
                [
                    'user_id' => $userId,
                    'assessment_id' => $assessment->id,
                ],
                [
                    'started_at' => now(),
                ]
            );

            // Nếu đã có finished_at thì chặn không cho nộp lại
            if ($userAssessment->finished_at) {
                return redirect()->route('assessments.result', $assessment->id)
                    ->with('error', 'Bạn đã nộp bài rồi.');
            }

            $score = 0;
            $total = $assessment->details->count();

            foreach ($assessment->details as $detail) {
                $userAnswer = $answers[$detail->id] ?? null;

                // So sánh văn bản an toàn với tiếng Việt (không phân biệt hoa thường)
                $isCorrect = false;
                if (
                    $userAnswer !== null &&
                    mb_strtolower(trim($userAnswer)) === mb_strtolower(trim($detail->answer))
                ) {
                    $isCorrect = true;
                    $score++;
                }

                // Lưu / cập nhật kết quả (tránh duplicate)
                Result::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'assessment_id' => $assessment->id,
                        'assessment_detail_id' => $detail->id,
                    ],
                    [
                        'user_answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                    ]
                );
            }

            // Cập nhật finished_at và score cho user_assessment
            $userAssessment->update([
                'finished_at' => now(),
                'score' => $score,
            ]);

            DB::commit();

            return redirect()->route('assessments.result', $assessment->id)
                ->with('score', $score)
                ->with('total', $total);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submit assessment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi nộp bài: ' . $e->getMessage());
        }
    }

    // Hàm Get (Lấy Kết quả)       
    public function result($id)
    {
        // Thông tin buổi đánh giá
        $assessment = Assessment::with(['details.results'])->findOrFail($id);
        $userId = auth()->id() ?? 1;

        // Hiệu suất thực tế
        $userAssessment = DB::table('user_assessments')
            ->where('assessment_id', $assessment->id)
            ->where('user_id', $userId)
            ->first();

        if (!$userAssessment) {
            return redirect()->route('assessments.do', $assessment->id)
                ->with('error', 'Bạn chưa thực hiện bài đánh giá này.');
        }

        // Tổng số câu hỏi
        $total = $assessment->details->count();

        // Số câu đúng
        $correctCount = $assessment->details->flatMap->results
            ->where('user_id', $userId)
            ->where('is_correct', 1)
            ->count();

        // Số câu sai
        $wrongCount = $assessment->details->flatMap->results
            ->where('user_id', $userId)
            ->where('is_correct', 0)
            ->whereNotNull('user_answer') // chỉ tính khi có câu trả lời
            ->count();

        // Số câu chưa trả lời
        $blankCount = $total - ($correctCount + $wrongCount);

        $correctPercent = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        $wrongByType = $assessment->details
            ->groupBy('skill')
            ->map(fn($group) => $group->flatMap->results->where('user_id', $userId)->where('is_correct', 0)->count());

        // Điểm (ở đây = số câu đúng, bạn có thể đổi cách tính)
        $score = $correctCount;

        // Thời gian làm bài (Thời gian nộp bài - Thời gian bắt đầu làm)                      
        $startedAt = Carbon::parse($userAssessment->started_at);
        $finishedAt = Carbon::parse($userAssessment->finished_at);
        $actualDuration = $finishedAt->diffInMinutes($startedAt);

        //                    
        return view('assessments.result', compact(
            'assessment',
            'score',
            'total',
            'correctCount',
            'wrongCount',
            'blankCount',
            'correctPercent',
            'wrongByType',
            'actualDuration'
        ));
    }

    public function summary($id)
    {
        // Load assessment + details + userAssessments (with user)
        $assessment = Assessment::with(['details.results', 'userAssessments.user'])->findOrFail($id);
        $total = $assessment->details->count();
        $userId = auth()->id() ?? 1;

        // Detect how results are linked: prefer user_id, else user_assessment_id
        $hasUserId = Schema::hasColumn('results', 'user_id');
        $hasUaId = Schema::hasColumn('results', 'user_assessment_id');

        if (!$hasUserId && !$hasUaId) {
            abort(500, 'Bảng results hiện không có cột user_id hoặc user_assessment_id để liên kết kết quả với người làm. Thêm cột hoặc dùng bảng user_results.');
        }

        // Cập nhật score = số câu đúng cho mỗi user_assessment
        foreach ($assessment->userAssessments as $ua) {
            // --- Tính số lượng câu
            if ($hasUserId) {
                $correctCountUser = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_id', $ua->user_id)
                    ->where('is_correct', 1)
                    ->count();
            } else { // group by user_assessment_id
                $correctCountUser = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_assessment_id', $ua->id)
                    ->where('is_correct', 1)
                    ->count();
            }

            // Lưu SỐ CÂU ĐÚNG vào cột score
            $ua->score = (int) $correctCountUser;
            $ua->save();
        }

        // reload để có scores mới
        $assessment->load('userAssessments.user');

        //
        $userAssessments = $assessment->userAssessments->map(function ($ua) {
            if ($ua->started_at && $ua->finished_at) {
                $start = Carbon::parse($ua->started_at)->timezone('Asia/Ho_Chi_Minh');
                $end = Carbon::parse($ua->finished_at)->timezone('Asia/Ho_Chi_Minh');
                $ua->duration_minutes = $end->diffInMinutes($start);
                $ua->duration_human = $end->diff($start)->format('%H giờ %I phút %S giây');
            } else {
                $ua->duration_minutes = null;
                $ua->duration_human = null;
            }
            return $ua;
        });

        $participants = $userAssessments->count();

        // Lấy thông tin cá nhân của user hiện tại
        $currentUserAssessment = $assessment->userAssessments->firstWhere('user_id', $userId);
        
        $userCorrectCount = 0;
        $userWrongCount = 0;
        $userBlankCount = $total;
        $userActualDuration = 0;

        if ($currentUserAssessment) {
            if ($hasUserId) {
                $userCorrectCount = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_id', $userId)
                    ->where('is_correct', 1)
                    ->count();
                $userWrongCount = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_id', $userId)
                    ->where('is_correct', 0)
                    ->whereNotNull('user_answer')
                    ->count();
            } else {
                $userCorrectCount = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_assessment_id', $currentUserAssessment->id)
                    ->where('is_correct', 1)
                    ->count();
                $userWrongCount = DB::table('results')
                    ->where('assessment_id', $assessment->id)
                    ->where('user_assessment_id', $currentUserAssessment->id)
                    ->where('is_correct', 0)
                    ->whereNotNull('user_answer')
                    ->count();
            }
            $userBlankCount = max(0, $total - ($userCorrectCount + $userWrongCount));
            
            if ($currentUserAssessment->started_at && $currentUserAssessment->finished_at) {
                $userActualDuration = Carbon::parse($currentUserAssessment->started_at)
                    ->diffInMinutes(Carbon::parse($currentUserAssessment->finished_at));
            }
        }

        $userCorrectPercent = $total > 0 ? round(($userCorrectCount / $total) * 100, 1) : 0;

        // collection các số câu đúng (int)
        $scores = $userAssessments->pluck('score')->map(fn($s) => (int) $s);

        // các chỉ số cơ bản (theo SỐ CÂU ĐÚNG)
        $avgCorrect = $scores->avg() ?? 0;   // số câu đúng trung bình
        $highestCorrect = $scores->max() ?? 0;
        $lowestCorrect = $scores->min() ?? 0;

        // gán ra biến cho view
        $avgScore = (int) round($avgCorrect);
        $highestScore = (int) $highestCorrect;
        $lowestScore = (int) $lowestCorrect;

        // chuyển thành phần trăm nếu cần (1 số chỗ view muốn %)
        $avgScorePercent = $total > 0 ? round(($avgCorrect / $total) * 100, 1) : 0;
        $highestScorePercent = $total > 0 ? round(($highestCorrect / $total) * 100, 1) : 0;
        $lowestScorePercent = $total > 0 ? round(($lowestCorrect / $total) * 100, 1) : 0;

        // tổng số câu đúng của cả lớp
        $totalCorrectAnswers = $scores->sum();

        // wrongCount tính theo tổng (số học sinh * số câu) - tổng đúng
        $wrongCount = ($participants * $total) - $totalCorrectAnswers;

        // total answered (non-null user_answer) để tính blank nếu results có user_answer
        $totalAnswered = DB::table('results')
            ->where('assessment_id', $assessment->id)
            ->whereNotNull('user_answer')
            ->count();
        
        $blankCount = max(0, ($participants * $total) - $totalAnswered);

        // tỷ lệ đúng toàn lớp (percent)
        $correctPercent = ($participants > 0 && $total > 0)
            ? round(($totalCorrectAnswers / ($participants * $total)) * 100, 1)
            : 0;

        // phân bố điểm (theo số câu đúng hoặc theo % nếu bạn muốn)
        $scoreDistribution = [
            '0-49%' => $scores->filter(fn($s) => $total > 0 ? ($s / $total * 100) < 50 : false)->count(),
            '50-69%' => $scores->filter(fn($s) => $total > 0 ? ($s / $total * 100) >= 50 && ($s / $total * 100) < 70 : false)->count(),
            '70-89%' => $scores->filter(fn($s) => $total > 0 ? ($s / $total * 100) >= 70 && ($s / $total * 100) < 90 : false)->count(),
            '90-100%' => $scores->filter(fn($s) => $total > 0 ? ($s / $total * 100) >= 90 : false)->count(),
        ];

        // Độ chính xác theo kỹ năng cho biểu đồ
        $skillStats = $assessment->details
            ->groupBy('skill')
            ->map(function ($group) use ($userId) {
                $grpTotal = $group->count();
                $grpCorrect = $group->flatMap->results
                    ->where('user_id', $userId)
                    ->where('is_correct', 1)
                    ->count();
                return [
                    'correct' => $grpCorrect,
                    'total' => $grpTotal,
                    'accuracy' => $grpTotal > 0 ? round(($grpCorrect / $grpTotal) * 100, 1) : 0
                ];
            });

        // --- (1) Danh sách từ vựng sai ---
        // Lấy từ vựng sai của riêng user hiện tại
        $wrongWords = DB::table('results')
            ->join('assessment_details', 'results.assessment_detail_id', '=', 'assessment_details.id')
            ->leftJoin('vocabularies', 'assessment_details.vocabulary_id', '=', 'vocabularies.id')
            ->where('results.assessment_id', $assessment->id)
            ->where('results.is_correct', 0)
            ->where('results.user_id', $userId)
            ->select(
                DB::raw('COALESCE(vocabularies.word, assessment_details.question) as word'), 
                'results.user_answer', 
                'assessment_details.answer'
            )
            ->get();

        // --- (2) Bảng theo dõi hiệu suất ---
        $performanceTracking = $assessment->userAssessments->map(function ($ua) use ($total) {
            return [
                'user_name' => $ua->user->name ?? 'N/A',
                'score' => $ua->score,
                'score_percent' => $total > 0 ? round(($ua->score / $total) * 100, 1) : 0,
                'duration' => $ua->duration_human ?? null,
                'finished_at' => $ua->finished_at ? Carbon::parse($ua->finished_at)->format('d/m/Y H:i') : null,
            ];
        });

        // Trả view (đảm bảo truyền đầy đủ biến)
        return view('assessments.summary', compact(
            'assessment',
            'userAssessments',
            'total',
            'participants',
            'userCorrectCount',
            'userWrongCount',
            'userBlankCount',
            'userActualDuration',
            'userCorrectPercent',
            'avgScore',
            'highestScore',
            'lowestScore',
            'avgScorePercent',
            'highestScorePercent',
            'lowestScorePercent',
            'correctPercent',
            'wrongCount',
            'blankCount',
            'scoreDistribution',
            'skillStats',
            'wrongWords',
            'performanceTracking'
        ));
    }

    // Export File
    public function exportResult($id)
    {
        $assessment = Assessment::with(['details.results'])->findOrFail($id);

        $pdf = Pdf::loadView('assessments.result-pdf', compact('assessment'))
            ->setPaper('a4', 'portrait'); // A4, dọc

        return $pdf->download('ket-qua-bai-thi.pdf');
    }

}





