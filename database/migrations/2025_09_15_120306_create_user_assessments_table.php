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
        Schema::create('user_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable(); // khi user bắt đầu làm
            $table->timestamp('finished_at')->nullable(); // khi user nộp bài
            $table->timestamps();

            $table->unique(['user_id', 'assessment_id']); // mỗi user chỉ 1 lần làm
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_assessments');
    }
};
