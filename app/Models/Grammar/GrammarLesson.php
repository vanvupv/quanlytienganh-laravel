<?php

namespace App\Models\Grammar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrammarLesson extends Model
{
    use HasFactory;

    protected $table = 'grammar_lessons';

    protected $fillable = [
        'topic_id', 
        'title', 
        'content', 
        'example', 
        'order',
        'formula_study_time',
        'formula_min_review_time',
        'usage_study_time',
        'usage_min_review_time',
        'example_study_time',
        'example_min_review_time',
        'passing_score'
    ];

    public function topic()
    {
        return $this->belongsTo(GrammarTopic::class, 'topic_id');
    }

    // tiện lợi khi cần lấy category thông qua topic
    public function category()
    {
        return $this->hasOneThrough(
            GrammarCategory::class,
            GrammarTopic::class,
            'id',           // Foreign key on grammar_topics
            'id',           // Foreign key on grammar_categories
            'topic_id',     // Local key on grammar_lessons
            'category_id'   // Local key on grammar_topics
        );
    }

    /**
     * Relationship with overall study logs.
     */
    public function studyLogs()
    {
        return $this->hasMany(GrammarStudyLog::class, 'lesson_id');
    }

    /**
     * Relationship with detailed section reviews.
     */
    public function sectionReviews()
    {
        return $this->hasMany(GrammarSectionReview::class, 'lesson_id');
    }

    /**
     * Relationship with student review test history.
     */
    public function reviews()
    {
        return $this->hasMany(GrammarReview::class, 'lesson_id');
    }

    /**
     * Relationship with detailed learning progress per knowledge key.
     */
    public function progress()
    {
        return $this->hasMany(GrammarLessonProgress::class, 'lesson_id');
    }
}





