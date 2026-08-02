<?php

namespace App\Models\Reading;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingAnalyzerHistory extends Model
{
    protected $table = 'reading_analyzer_histories';

    protected $fillable = [
        'file_name',
        'file_ext',
        'filter_id',
        'filter_name_snapshot',
        'total_words',
        'new_count',
        'known_count',
        'comprehension_pct',
    ];

    protected $casts = [
        'total_words'       => 'integer',
        'new_count'         => 'integer',
        'known_count'       => 'integer',
        'comprehension_pct' => 'integer',
    ];

    /**
     * Bộ lọc liên quan (nullable — có thể bị xóa sau)
     */
    public function filter(): BelongsTo
    {
        return $this->belongsTo(ReadingFilterSetting::class, 'filter_id');
    }

    /**
     * Tên bộ lọc hiển thị (ưu tiên snapshot nếu filter đã bị xóa)
     */
    public function getDisplayFilterNameAttribute(): string
    {
        if ($this->filter) {
            return $this->filter->name;
        }
        return $this->filter_name_snapshot ?? '— Mặc định';
    }

    /**
     * Icon theo loại file
     */
    public function getFileIconAttribute(): string
    {
        return match(strtolower($this->file_ext)) {
            'pdf'          => '📄',
            'docx', 'doc'  => '📝',
            default        => '📃',
        };
    }
}
