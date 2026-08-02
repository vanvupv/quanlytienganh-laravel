<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    protected $table = 'user_achievements';

    protected $fillable = [
        'user_id',
        'achievement_id',
        'unlocked_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    /**
     * Get the user that owns the unlocked achievement record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the achievement metadata that was unlocked.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
