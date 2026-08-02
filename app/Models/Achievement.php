<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $table = 'achievements';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'category',
        'condition_type',
        'target_value',
    ];

    /**
     * Get all users who have unlocked this achievement.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }
}
