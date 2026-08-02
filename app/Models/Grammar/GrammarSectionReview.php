<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarSectionReview extends Model
{
    use HasFactory;

    protected $table = 'grammar_section_reviews';

    protected $fillable = [
        'lesson_id',
        'section_name',
        'study_time',
        'min_review_time',
        'review_count',
        'review_time',
        'review_score',
        'status',
    ];

    /**
     * Relationship with the grammar lesson.
     */
    public function lesson()
    {
        return $this->belongsTo(GrammarLesson::class, 'lesson_id');
    }
}
