<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarTopic extends Model
{
    use HasFactory;

    protected $table = 'grammar_topics';

    protected $fillable = ['category_id', 'title', 'description', 'order'];


    public function category()
    {
        return $this->belongsTo(GrammarCategory::class, 'category_id');
    }

    public function lessons()
    {
        return $this->hasMany(GrammarLesson::class, 'topic_id');
    }
}
