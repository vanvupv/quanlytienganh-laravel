<?php

namespace App\Models\Document;

use App\Models\Listening\Listening;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        // Nhóm 1: Thông tin cơ bản
        'name', 'description', 'skill', 'level', 'proficiency',
        // Nhóm 2: Thông tin xuất bản
        'author', 'publisher', 'published_year', 'version', 'isbn', 'language',
        // Nhóm 3: Thông tin vật lý & File
        'cover_image', 'page_count', 'dimensions', 'file_size', 'file_format', 'total_lessons',
        // Nhóm 4: Trạng thái & Quản lý
        'status', 'created_by',
        // Nhóm 5: Cấu trúc kiến thức
        'overview', 'chapter_list', 'chapter_contents', 'chapter_volumes',
        // Nhóm 6: Hành trình học
        'learning_objective', 'study_duration', 'review_duration',
        'assessment', 'completion_criteria', 'journey_status', 'is_passed',
    ];

    protected $casts = [
        'chapter_list'        => 'array',
        'chapter_contents'    => 'array',
        'chapter_volumes'     => 'array',
        'completion_criteria' => 'array',
        'is_passed'           => 'boolean',
        'published_year'      => 'integer',
        'page_count'          => 'integer',
        'total_lessons'       => 'integer',
        'study_duration'      => 'integer',
        'review_duration'     => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    /** Chương (Parts) của tài liệu */
    public function parts()
    {
        return $this->hasMany(DocumentPart::class)->orderBy('order');
    }

    /** File nghe trực tiếp dưới tài liệu */
    public function listenings()
    {
        return $this->hasMany(Listening::class, 'document_id');
    }

    /** Người tạo */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // =========================================================
    // Accessors & Helpers
    // =========================================================

    /** Tổng số file nghe của toàn bộ tài liệu */
    public function getTotalListeningsAttribute(): int
    {
        return $this->listenings()->count();
    }

    /** Tổng thời gian nghe (giây) */
    public function getTotalDurationAttribute(): int
    {
        return $this->listenings()->sum('duration') ?? 0;
    }

    /** Format tổng thời gian nghe dạng hh:mm:ss */
    public function getFormattedDurationAttribute(): string
    {
        $secs = $this->total_duration;
        $h = floor($secs / 3600);
        $m = floor(($secs % 3600) / 60);
        $s = $secs % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    /** Scope lọc theo cấp độ */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /** Scope lọc theo kỹ năng */
    public function scopeBySkill($query, string $skill)
    {
        return $query->where('skill', $skill);
    }
}
