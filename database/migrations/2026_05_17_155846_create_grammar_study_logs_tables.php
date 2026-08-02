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
        // 1. Table for overall lesson study stats
        Schema::create('grammar_study_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('grammar_lessons')->onDelete('cascade');
            $table->date('session_date');
            $table->integer('study_time')->default(0); // in minutes
            $table->integer('review_time')->default(0); // in minutes
            $table->integer('sessions_count')->default(0);
            $table->timestamps();
        });

        // 2. Table for detailed sections (Formula, Usage, Examples)
        Schema::create('grammar_section_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('grammar_lessons')->onDelete('cascade');
            $table->string('section_name'); // 'Formula', 'Usage', 'Examples'
            $table->integer('study_time')->default(0); // in minutes
            $table->integer('min_review_time')->default(10); // minimum required review time (e.g. 10 minutes)
            $table->integer('review_count')->default(0); // number of review attempts
            $table->integer('review_time')->default(0); // total time reviewed (in minutes)
            $table->integer('review_score')->default(0); // overall/last score (0 - 100)
            $table->string('status')->default('Chưa đạt'); // 'Đạt' or 'Chưa đạt'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_section_reviews');
        Schema::dropIfExists('grammar_study_logs');
    }
};
