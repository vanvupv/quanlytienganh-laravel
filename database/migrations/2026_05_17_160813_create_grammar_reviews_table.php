<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create grammar_reviews table
        Schema::create('grammar_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('grammar_lessons')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->json('answers'); // JSON payload containing detailed student answers, actual answers, and correctness marks.
            $table->boolean('passed')->default(false);
            $table->timestamps();
        });

        // 2. Add customizable section timers & targets to grammar_lessons
        Schema::table('grammar_lessons', function (Blueprint $table) {
            $table->integer('formula_study_time')->default(15); // in minutes
            $table->integer('formula_min_review_time')->default(10); // in minutes
            $table->integer('usage_study_time')->default(20); // in minutes
            $table->integer('usage_min_review_time')->default(15); // in minutes
            $table->integer('example_study_time')->default(10); // in minutes
            $table->integer('example_min_review_time')->default(10); // in minutes
            $table->integer('passing_score')->default(80); // e.g. 80 out of 100
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grammar_lessons', function (Blueprint $table) {
            $table->dropColumn([
                'formula_study_time',
                'formula_min_review_time',
                'usage_study_time',
                'usage_min_review_time',
                'example_study_time',
                'example_min_review_time',
                'passing_score'
            ]);
        });

        Schema::dropIfExists('grammar_reviews');
    }
};
