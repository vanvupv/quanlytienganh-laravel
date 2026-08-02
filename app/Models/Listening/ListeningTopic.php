<?php

namespace App\Models\Listening;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningTopic extends Model
{
    use HasFactory;

    protected $table = 'listening_topics';

    protected $fillable = ['name', 'slug', 'description'];

    public function listenings()
    {
        return $this->hasMany(Listening::class, 'topic_id');
    }
}
