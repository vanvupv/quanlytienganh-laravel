<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_analyzer_histories', function (Blueprint $table) {
            $table->id();

            // Thông tin file
            $table->string('file_name');                         // Tên file gốc
            $table->string('file_ext', 10)->default('txt');      // Định dạng: pdf, docx, txt

            // Bộ lọc đã dùng (nullable — có thể không chọn bộ lọc)
            $table->foreignId('filter_id')
                  ->nullable()
                  ->constrained('reading_filter_settings')
                  ->nullOnDelete();
            $table->string('filter_name_snapshot')->nullable();  // Tên bộ lọc tại thời điểm phân tích

            // Thống kê kết quả
            $table->unsignedInteger('total_words')->default(0);
            $table->unsignedInteger('new_count')->default(0);
            $table->unsignedInteger('known_count')->default(0);
            $table->unsignedTinyInteger('comprehension_pct')->default(0); // 0-100

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_analyzer_histories');
    }
};
