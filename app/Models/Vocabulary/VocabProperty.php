<?php

namespace App\Models\Vocabulary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabProperty extends Model
{
    use HasFactory;

    protected $table = 'vocab_properties';

    protected $fillable = [
        'word_id',
        'stage_id',
        'part_of_speech',
        'meaning',
        'example_sentence',
        'example_translation',
        'topic',
        'reference',
    ];

    /**
     * Get the base word that owns this property.
     */
    public function word()
    {
        return $this->belongsTo(VocabWord::class, 'word_id');
    }

    /**
     * Get the growth stage at which this property was introduced.
     */
    public function stage()
    {
        return $this->belongsTo(VocabStage::class, 'stage_id');
    }
}
