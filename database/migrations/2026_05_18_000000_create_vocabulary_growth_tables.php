<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old basic vocabulary table safely by disabling foreign key checks temporarily
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('vocabulary');
        Schema::enableForeignKeyConstraints();

        // 1. Base Words Table
        Schema::create('vocab_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique(); // e.g. "Run", "Book", "Family"
            $table->string('uk_phonetic')->nullable();
            $table->string('us_phonetic')->nullable();
            $table->timestamps();
        });

        // 2. Growth Stages Table (e.g. Stage 1 -> Nền tảng, Stage 2 -> Phát triển, Stage 3 -> Nâng cao)
        Schema::create('vocab_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // 3. Multi-Stage Word Lexical Properties Table
        Schema::create('vocab_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_id')->constrained('vocab_words')->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('vocab_stages')->onDelete('cascade');
            $table->string('part_of_speech'); // e.g. "Noun", "Verb", "Adjective"
            $table->text('meaning'); // e.g. "Chạy", "Cuộc chạy", "Điều hành"
            $table->text('example_sentence')->nullable();
            $table->text('example_translation')->nullable();
            $table->string('topic')->nullable(); // e.g. "Sports", "Business"
            $table->string('reference')->nullable(); // e.g. "Oxford 3000"
            $table->timestamps();
        });

        // 4. Student Study Logs Table
        Schema::create('vocab_study_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('word_id')->constrained('vocab_words')->onDelete('cascade');
            $table->integer('sessions_count')->default(0);
            $table->integer('study_time_minutes')->default(0);
            $table->integer('review_time_minutes')->default(0);
            $table->timestamps();
        });

        // 5. Vocabulary Recall Quizzes/Reviews History Table
        Schema::create('vocab_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('word_id')->constrained('vocab_words')->onDelete('cascade');
            $table->integer('score');
            $table->integer('duration_seconds');
            $table->json('answers'); // stores student spelling answers, multiple meanings, and example translation answers
            $table->boolean('passed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocab_reviews');
        Schema::dropIfExists('vocab_study_logs');
        Schema::dropIfExists('vocab_properties');
        Schema::dropIfExists('vocab_stages');
        Schema::dropIfExists('vocab_words');
    }
};
