<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningTranscript extends Model
{
    use HasFactory;

    protected $table = 'listening_transcripts';

    protected $fillable = [
        'listening_id', 'type', 'language_code', 'transcript',
        'sentence_count', 'word_count',
    ];

    protected $casts = [
        'sentence_count' => 'integer',
        'word_count'     => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function listening()
    {
        return $this->belongsTo(Listening::class, 'listening_id');
    }
}
