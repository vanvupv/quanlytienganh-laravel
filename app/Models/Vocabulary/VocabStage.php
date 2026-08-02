<?php

namespace App\Models\Vocabulary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabStage extends Model
{
    use HasFactory;

    protected $table = 'vocab_stages';

    protected $fillable = [
        'name',
        'description',
        'order',
    ];

    /**
     * Get all word properties introduced in this stage.
     */
    public function properties()
    {
        return $this->hasMany(VocabProperty::class, 'stage_id');
    }
}
