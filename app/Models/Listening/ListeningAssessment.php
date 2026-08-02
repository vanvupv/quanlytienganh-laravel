<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningAssessment extends Model
{
    use HasFactory;

    protected $table = 'listening_assessments';

    protected $fillable = [
        'listening_id', 'user_id', 'session_id',
        'attempt_number', 'submitted_transcript', 'original_transcript',
        'score_percent', 'correct_words', 'total_words',
        'duration_spent', 'is_passed', 'efficiency_score', 'assessed_at',
    ];

    protected $casts = [
        'score_percent'    => 'decimal:2',
        'efficiency_score' => 'decimal:2',
        'is_passed'        => 'boolean',
        'correct_words'    => 'integer',
        'total_words'      => 'integer',
        'duration_spent'   => 'integer',
        'attempt_number'   => 'integer',
        'assessed_at'      => 'datetime',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function listening()
    {
        return $this->belongsTo(Listening::class, 'listening_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function session()
    {
        return $this->belongsTo(ListeningSession::class, 'session_id');
    }

    // =========================================================
    // Helpers - Tính độ khớp transcript
    // =========================================================

    /**
     * So sánh transcript người dùng viết với transcript gốc
     * Trả về % độ khớp dựa trên từ
     */
    public static function calculateScore(string $submitted, string $original): array
    {
        $submittedWords = self::normalizeWords($submitted);
        $originalWords  = self::normalizeWords($original);

        $totalWords = count($originalWords);
        if ($totalWords === 0) {
            return ['score_percent' => 0, 'correct_words' => 0, 'total_words' => 0];
        }

        // Đếm từ khớp (dạng multiset matching)
        $origCounts = array_count_values($originalWords);
        $subCounts  = array_count_values($submittedWords);
        $correct = 0;
        foreach ($origCounts as $word => $count) {
            $correct += min($count, $subCounts[$word] ?? 0);
        }

        $scorePercent = round(($correct / $totalWords) * 100, 2);

        return [
            'score_percent' => $scorePercent,
            'correct_words' => $correct,
            'total_words'   => $totalWords,
        ];
    }

    /** Chuẩn hoá chuỗi thành mảng từ lowercase, bỏ dấu câu */
    private static function normalizeWords(string $text): array
    {
        $text = mb_strtolower($text);
        $text = preg_replace("/[^\p{L}\p{N}\s]/u", ' ', $text);
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        return $words ?: [];
    }
}
