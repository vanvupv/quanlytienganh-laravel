<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningWeeklyStat extends Model
{
    use HasFactory;

    protected $table = 'listening_weekly_stats';

    protected $fillable = [
        'listening_id', 'user_id', 'week_start',
        'year', 'week_number',
        'listen_count', 'pass_count', 'total_duration',
        'avg_score', 'best_efficiency',
    ];

    protected $casts = [
        'week_start'      => 'date',
        'listen_count'    => 'integer',
        'pass_count'      => 'integer',
        'total_duration'  => 'integer',
        'avg_score'       => 'decimal:2',
        'best_efficiency' => 'decimal:2',
        'year'            => 'integer',
        'week_number'     => 'integer',
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

    // =========================================================
    // Helpers
    // =========================================================

    /**
     * Ghi nhận / cập nhật thống kê tuần sau mỗi phiên nghe
     */
    public static function recordSession(ListeningSession $session): self
    {
        $monday = now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
        $year = now()->year;
        $week = now()->isoWeek();

        $stat = self::firstOrNew([
            'listening_id' => $session->listening_id,
            'user_id'      => $session->user_id,
            'week_start'   => $monday,
        ]);

        $stat->year        = $year;
        $stat->week_number = $week;
        $stat->listen_count += 1;
        if ($session->is_passed) $stat->pass_count += 1;
        $stat->total_duration += $session->duration_seconds;

        // Tính avg_score từ assessments trong tuần
        $weekAssessments = ListeningAssessment::where('listening_id', $session->listening_id)
            ->where('user_id', $session->user_id)
            ->whereBetween('assessed_at', [$monday, now()->toDateTimeString()])
            ->get();

        if ($weekAssessments->count() > 0) {
            $stat->avg_score = round($weekAssessments->avg('score_percent'), 2);
            $stat->best_efficiency = $weekAssessments->max('efficiency_score');
        }

        $stat->save();
        return $stat;
    }

    /** Format tổng thời gian nghe trong tuần */
    public function getFormattedDurationAttribute(): string
    {
        $s = $this->total_duration;
        $h = floor($s / 3600);
        $m = floor(($s % 3600) / 60);
        return $h > 0 ? "{$h}g {$m}p" : "{$m} phút";
    }
}
