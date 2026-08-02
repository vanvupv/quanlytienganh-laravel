<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng thống kê nghe theo tuần (timeline)
     */
    public function up(): void
    {
        Schema::create('listening_weekly_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listening_id');
            $table->unsignedBigInteger('user_id')->nullable();

            // Xác định tuần
            $table->date('week_start')->comment('Ngày đầu tuần (Monday)');
            $table->integer('year')->comment('Năm');
            $table->integer('week_number')->comment('Tuần thứ mấy trong năm (ISO)');

            // Thống kê
            $table->integer('listen_count')->default(0)->comment('Số lần nghe trong tuần');
            $table->integer('pass_count')->default(0)->comment('Số lần đạt trong tuần');
            $table->integer('total_duration')->default(0)->comment('Tổng thời gian nghe (giây)');
            $table->decimal('avg_score', 5, 2)->nullable()->comment('Điểm trung bình trong tuần');
            $table->decimal('best_efficiency', 5, 2)->nullable()->comment('Hiệu suất tốt nhất trong tuần');

            $table->timestamps();

            $table->foreign('listening_id')->references('id')->on('listening')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            // Unique per listening + user + week
            $table->unique(['listening_id', 'user_id', 'week_start'], 'unique_listening_user_week');
            $table->index(['listening_id', 'year', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_weekly_stats');
    }
};
