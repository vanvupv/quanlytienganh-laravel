<?php

namespace App\Models\Vocabulary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabWord extends Model
{
    use HasFactory;

    protected $table = 'vocab_words';

    protected $fillable = [
        'word',
        'uk_phonetic',
        'us_phonetic',
    ];

    /**
     * Get all lexical growth properties associated with this word.
     */
    public function properties()
    {
        return $this->hasMany(VocabProperty::class, 'word_id');
    }

    /**
     * Get the student study tracking logs for this word.
     */
    public function studyLogs()
    {
        return $this->hasMany(VocabStudyLog::class, 'word_id');
    }

    /**
     * Get all recall review attempts for this word.
     */
    public function reviews()
    {
        return $this->hasMany(VocabReview::class, 'word_id');
    }
}
