<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng đánh giá: Nghe và viết lại Transcript
     */
    public function up(): void
    {
        Schema::create('listening_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listening_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('session_id')->nullable()->comment('FK → listening_sessions');

            // Thông tin bài nộp
            $table->integer('attempt_number')->default(1)->comment('Lần nộp thứ mấy');
            $table->longText('submitted_transcript')->nullable()->comment('Transcript người dùng viết lại');
            $table->longText('original_transcript')->nullable()->comment('Transcript gốc để đối chiếu');

            // Kết quả đánh giá
            $table->decimal('score_percent', 5, 2)->default(0)->comment('Điểm % độ khớp transcript');
            $table->integer('correct_words')->default(0)->comment('Số từ đúng');
            $table->integer('total_words')->default(0)->comment('Tổng số từ của transcript gốc');
            $table->integer('duration_spent')->default(0)->comment('Thời gian làm bài (giây)');
            $table->boolean('is_passed')->default(false)->comment('Đạt hay không (>= ngưỡng)');
            $table->decimal('efficiency_score', 5, 2)->nullable()->comment('Hiệu suất tổng (100% nếu lần 1)');

            $table->timestamp('assessed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('listening_id')->references('id')->on('listening')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('session_id')->references('id')->on('listening_sessions')->nullOnDelete();

            $table->index(['listening_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_assessments');
    }
};
