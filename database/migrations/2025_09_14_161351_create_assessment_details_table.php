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
        Schema::create('assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('vocabulary_id')->nullable()->constrained('vocabulary')->onDelete('set null');
            $table->text('question')->nullable();   // câu hỏi
            $table->text('answer')->nullable();     // đáp án đúng
            $table->string('skill')->nullable();    // ví dụ: listening, reading
            $table->integer('point')->default(1);   // điểm cho câu này
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_details');
    }
};
