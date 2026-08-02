<?php

namespace App\Models\Listening;

use App\Models\Document\Document;
use App\Models\Document\DocumentPart;
use App\Models\Document\DocumentUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listening extends Model
{
    use HasFactory;

    protected $table = 'listening';

    protected $fillable = [
        'title', 'link', 'type', 'size', 'duration',
        'level', 'score_level', 'listen_count',
        'document_id', 'unit_id', 'chapter_id', 'topic_id',
    ];

    protected $casts = [
        'duration'     => 'integer',
        'listen_count' => 'integer',
        'score_level'  => 'decimal:2',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function chapter()
    {
        return $this->belongsTo(DocumentPart::class, 'chapter_id');
    }

    public function unit()
    {
        return $this->belongsTo(DocumentUnit::class, 'unit_id');
    }

    public function topic()
    {
        return $this->belongsTo(ListeningTopic::class, 'topic_id');
    }

    public function transcripts()
    {
        return $this->hasMany(ListeningTranscript::class, 'listening_id');
    }

    public function sessions()
    {
        return $this->hasMany(ListeningSession::class, 'listening_id');
    }

    public function assessments()
    {
        return $this->hasMany(ListeningAssessment::class, 'listening_id');
    }

    public function weeklyStats()
    {
        return $this->hasMany(ListeningWeeklyStat::class, 'listening_id');
    }

    public function sentences()
    {
        return $this->hasMany(ListeningSentence::class, 'listening_id')->orderBy('order');
    }

    // =========================================================
    // Accessors & Business Logic
    // =========================================================

    /** Transcript gốc (type = original) */
    public function getOriginalTranscriptAttribute(): ?ListeningTranscript
    {
        return $this->transcripts->where('type', 'original')->first();
    }

    /**
     * Hiệu suất nghe của người dùng
     * Logic: Nghe 1 lần và hiểu hết = 100%
     * Mỗi lần thêm → giảm dần (tối thiểu 10%)
     */
    public function efficiency(?int $userId = null): float
    {
        $query = $this->sessions();
        if ($userId) $query = $query->where('user_id', $userId);
        $count = $query->count();

        if ($count === 0) return 0;
        if ($count === 1) return 100;

        // Công thức: 100 / count, tối thiểu 10%
        return max(10, round(100 / $count, 2));
    }

    /** Số lần nghe của user cụ thể */
    public function listenCountForUser(int $userId): int
    {
        return $this->sessions()->where('user_id', $userId)->count();
    }

    /** Số lần đạt của user cụ thể */
    public function passCountForUser(int $userId): int
    {
        return $this->sessions()->where('user_id', $userId)->where('is_passed', true)->count();
    }

    /** Tổng thời gian nghe của user (giây) */
    public function totalDurationForUser(int $userId): int
    {
        return $this->sessions()->where('user_id', $userId)->sum('duration_seconds') ?? 0;
    }

    /** Format thời gian dạng mm:ss */
    public function getFormattedDurationAttribute(): string
    {
        $s = $this->duration ?? 0;
        return sprintf('%d:%02d', floor($s / 60), $s % 60);
    }
}
