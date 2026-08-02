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
        Schema::create('grammar_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('grammar_lessons')->onDelete('cascade');
            $table->string('knowledge_key'); // e.g. 'formula:affirmative', 'usage:0', 'example:2'
            $table->date('learned_date');
            $table->text('note')->nullable();
            $table->timestamps();

            // Avoid duplicate progress logs for the same knowledge item in the same lesson
            $table->unique(['lesson_id', 'knowledge_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_lesson_progress');
    }
};
