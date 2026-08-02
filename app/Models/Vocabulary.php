<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    // nếu bảng là 'vocabulary' (không theo plural), khai báo rõ
    protected $table = 'vocabulary';

    protected $fillable = [
        'word',
        'meaning',
        'part_of_speech',
        'pronunciation',
        'example',
        // 'code',                   
        'level',
    ];

    /**
     * Quan hệ với assessment details (nếu bạn dùng bảng assessment_details.vocabulary_id)
     */
    public function assessmentDetails()
    {
        return $this->hasMany(\App\Models\AssessmentDetail::class, 'vocabulary_id');
    }
}
