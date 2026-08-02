<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'title',
        'type',
        'method',
        'quantity',
        'start_time',
        'end_time',
        'duration',
        'location',
        'note',
        'min_score',
        'min_performance',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Chi tiết đề đánh giá                    
    public function details()
    {
        return $this->hasMany(AssessmentDetail::class);
    }

    // Kết quả đề đánh giá  
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    //
    public function userAssessments()
    {
        return $this->hasMany(UserAssessment::class);
    }

    //

}
