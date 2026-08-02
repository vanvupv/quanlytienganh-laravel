<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'user_id',
        'assessment_id',
        'assessment_detail_id',
        'user_answer',
        'is_correct',
    ];

    // Quan hệ nếu cần
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function detail()
    {
        return $this->belongsTo(AssessmentDetail::class, 'assessment_detail_id');
    }
}
