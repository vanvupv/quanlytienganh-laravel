<?php

namespace App\Models\Reading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReadingFilterSetting extends Model
{
    use HasFactory;

    protected $table = 'reading_filter_settings';

    protected $fillable = [
        'name',
        'language',
        'topic',
        'category',
        'context',
        'ignored_words',
        'is_active',
    ];

    protected $casts = [
        'ignored_words' => 'array',
        'is_active'     => 'boolean',
    ];

    /**
     * Lịch sử phân tích đã dùng bộ lọc này
     */
    public function analyzerHistories(): HasMany
    {
        return $this->hasMany(ReadingAnalyzerHistory::class, 'filter_id');
    }

    /**
     * Lấy danh sách từ bỏ qua đã được chuẩn hóa (lowercase, trimmed)
     */
    public function getNormalizedIgnoredWords(): array
    {
        $words = $this->ignored_words ?? [];
        return array_map(fn($w) => strtolower(trim($w)), $words);
    }
}
