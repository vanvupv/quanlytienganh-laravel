<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarCategory extends Model
{
    use HasFactory;

    protected $table = 'grammar_categories';

    protected $fillable = ['name', 'description', 'parent_id', 'order'];

    public function parent()
    {
        return $this->belongsTo(GrammarCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(GrammarCategory::class, 'parent_id');
    }

    public function topics()
    {
        return $this->hasMany(GrammarTopic::class, 'category_id');
    }
}
