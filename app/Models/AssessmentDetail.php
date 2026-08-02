<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'assessment_id',
        'vocabulary_id',
        'grammar_id',
        'question',
        'answer',
        'skill',
        'point'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function grammar()
    {
        return $this->belongsTo(Grammar::class);
    }

    //
    public function results()
    {
        return $this->hasMany(Result::class, 'assessment_detail_id');
    }

}
