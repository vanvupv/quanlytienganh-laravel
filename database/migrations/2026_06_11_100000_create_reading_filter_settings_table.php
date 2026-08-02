<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_filter_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Tên bộ lọc
            $table->string('language')->default('en');       // Ngôn ngữ (en, vi, ...)
            $table->string('topic')->nullable();             // Chủ đề tài liệu
            $table->string('category')->nullable();          // Danh mục
            $table->string('context')->nullable();           // Ngữ cảnh (academic, business, ...)
            $table->json('ignored_words')->nullable();       // Danh sách từ bỏ qua
            $table->boolean('is_active')->default(true);    // Trạng thái kích hoạt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_filter_settings');
    }
};
