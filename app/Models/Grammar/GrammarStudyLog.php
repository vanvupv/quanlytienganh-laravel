<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarStudyLog extends Model
{
    use HasFactory;

    protected $table = 'grammar_study_logs';

    protected $fillable = [
        'lesson_id',
        'session_date',
        'study_time',
        'review_time',
        'sessions_count',
    ];

    /**
     * Relationship with the grammar lesson.
     */
    public function lesson()
    {
        return $this->belongsTo(GrammarLesson::class, 'lesson_id');
    }
}
