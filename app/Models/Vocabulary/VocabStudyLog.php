<?php

namespace App\Models\Vocabulary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabStudyLog extends Model
{
    use HasFactory;

    protected $table = 'vocab_study_logs';

    protected $fillable = [
        'user_id',
        'word_id',
        'sessions_count',
        'study_time_minutes',
        'review_time_minutes',
    ];

    /**
     * Get the student user who owns this study log.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the word associated with this study log.
     */
    public function word()
    {
        return $this->belongsTo(VocabWord::class, 'word_id');
    }
}
