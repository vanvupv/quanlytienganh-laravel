<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarLessonProgress extends Model
{
    use HasFactory;

    protected $table = 'grammar_lesson_progress';

    protected $fillable = [
        'lesson_id',
        'knowledge_key',
        'learned_date',
        'note'
    ];

    protected $casts = [
        'learned_date' => 'date'
    ];

    public function lesson()
    {
        return $this->belongsTo(GrammarLesson::class, 'lesson_id');
    }
}
