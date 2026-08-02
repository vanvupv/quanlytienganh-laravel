<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarReview extends Model
{
    use HasFactory;

    protected $table = 'grammar_reviews';

    protected $fillable = [
        'lesson_id',
        'score',
        'duration_seconds',
        'answers',
        'passed',
    ];

    protected $casts = [
        'answers' => 'array',
        'passed' => 'boolean',
    ];

    /**
     * Relationship with the grammar lesson.
     */
    public function lesson()
    {
        return $this->belongsTo(GrammarLesson::class, 'lesson_id');
    }
}
