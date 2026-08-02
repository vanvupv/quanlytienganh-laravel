<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningSentence extends Model
{
    use HasFactory;

    protected $table = 'listening_sentences';

    protected $fillable = [
        'listening_id', 'sentence_text', 'start_time', 'end_time', 'order'
    ];

    protected $casts = [
        'start_time' => 'float',
        'end_time'   => 'float',
        'order'      => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function listening()
    {
        return $this->belongsTo(Listening::class, 'listening_id');
    }
}
