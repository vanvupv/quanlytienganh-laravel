<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng lưu trữ từng phiên nghe của người dùng
     */
    public function up(): void
    {
        Schema::create('listening_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listening_id');
            $table->unsignedBigInteger('user_id')->nullable();

            // Thông tin phiên nghe
            $table->integer('attempt_number')->default(1)->comment('Lần nghe thứ mấy');
            $table->integer('duration_seconds')->default(0)->comment('Thực tế nghe bao nhiêu giây');
            $table->boolean('is_completed')->default(false)->comment('Đã nghe hết chưa');
            $table->boolean('is_passed')->default(false)->comment('Đạt sau phiên này chưa');
            $table->decimal('efficiency_score', 5, 2)->nullable()->comment('Hiệu suất % của phiên này');
            $table->timestamp('listened_at')->useCurrent();

            $table->timestamps();

            $table->foreign('listening_id')->references('id')->on('listening')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['listening_id', 'user_id']);
            $table->index('listened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_sessions');
    }
};
