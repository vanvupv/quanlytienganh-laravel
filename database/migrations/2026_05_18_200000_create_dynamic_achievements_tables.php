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
        // 1. Dynamic Achievements Definition Table
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon'); // e.g. "🌱", "🏆", "🔥"
            $table->string('category'); // 'vocab', 'grammar', 'review', 'streak'
            $table->string('condition_type'); // 'vocab_studied', 'vocab_mastered', 'grammar_lessons_done', 'reviews_done', 'streak_days', 'retention_rate'
            $table->integer('target_value');
            $table->timestamps();
        });

        // 2. User Unlocked Achievements Pivot Table
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained('achievements')->onDelete('cascade');
            $table->timestamp('unlocked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']); // Ensure each achievement is unlocked only once per user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
    }
};
