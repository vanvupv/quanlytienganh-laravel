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
        Schema::create('listening_sentences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listening_id');
            
            // Thông tin câu thoại & phân đoạn
            $table->text('sentence_text')->comment('Nội dung câu');
            $table->decimal('start_time', 8, 2)->default(0.00)->comment('Thời gian bắt đầu (giây)');
            $table->decimal('end_time', 8, 2)->default(0.00)->comment('Thời gian kết thúc (giây)');
            $table->integer('order')->default(1)->comment('Thứ tự câu trong bài');
            
            $table->timestamps();

            $table->foreign('listening_id')->references('id')->on('listening')->cascadeOnDelete();
            $table->index(['listening_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_sentences');
    }
};
