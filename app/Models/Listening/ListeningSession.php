<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningSession extends Model
{
    use HasFactory;

    protected $table = 'listening_sessions';

    protected $fillable = [
        'listening_id', 'user_id', 'attempt_number',
        'duration_seconds', 'is_completed', 'is_passed',
        'efficiency_score', 'listened_at',
    ];

    protected $casts = [
        'is_completed'     => 'boolean',
        'is_passed'        => 'boolean',
        'duration_seconds' => 'integer',
        'attempt_number'   => 'integer',
        'efficiency_score' => 'decimal:2',
        'listened_at'      => 'datetime',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function listening()
    {
        return $this->belongsTo(Listening::class, 'listening_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function assessment()
    {
        return $this->hasOne(ListeningAssessment::class, 'session_id');
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
