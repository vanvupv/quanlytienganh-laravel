<?php

namespace App\Models\Vocabulary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabReview extends Model
{
    use HasFactory;

    protected $table = 'vocab_reviews';

    protected $fillable = [
        'user_id',
        'word_id',
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
     * Get the student who made this review attempt.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the vocabulary word that was reviewed.
     */
    public function word()
    {
        return $this->belongsTo(VocabWord::class, 'word_id');
    }
}
