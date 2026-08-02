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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // tên buổi đánh giá
            $table->enum('type', ['vocabulary', 'grammar', 'mixed']); // loại đánh giá
            $table->dateTime('start_time'); // thời gian bắt đầu
            $table->dateTime('end_time');   // thời gian kết thúc
            $table->integer('duration')->comment('Tổng số phút');
            $table->string('location')->nullable(); // địa điểm

            // Bổ sung 24/09/2025    
            $table->decimal('min_score', 5, 2)->default(0)->comment('Điểm tối thiểu để qua');
            $table->decimal('min_performance', 5, 2)->default(0)->comment('Hiệu suất tối thiểu (%)');

            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
