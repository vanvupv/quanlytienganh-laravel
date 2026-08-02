<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo các bảng Document: documents, document_parts, document_units
     * Bao gồm đầy đủ 6 nhóm thông tin tài liệu Listening
     */
    public function up(): void
    {
        // =========================================================
        // Bảng: documents (Tài liệu chính)
        // =========================================================
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // --- NHÓM 1: Thông tin cơ bản ---
            $table->string('name')->comment('Tên tài liệu');
            $table->text('description')->nullable()->comment('Mô tả ngắn');
            $table->string('skill')->nullable()->comment('listening, reading, speaking, writing');
            $table->string('level')->nullable()->comment('A1, A2, B1, B2, C1, C2');
            $table->string('proficiency')->nullable()->comment('Beginner, Elementary, Pre-Intermediate, Intermediate, Upper-Intermediate, Advanced');

            // --- NHÓM 2: Thông tin xuất bản ---
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();
            $table->string('version')->nullable();
            $table->string('isbn')->nullable();
            $table->string('language')->default('en')->comment('en, vi, ...');

            // --- NHÓM 3: Thông tin vật lý & File ---
            $table->string('cover_image')->nullable()->comment('Đường dẫn ảnh bìa');
            $table->integer('page_count')->nullable();
            $table->string('dimensions')->nullable()->comment('VD: 21x29.7 cm');
            $table->string('file_size')->nullable()->comment('VD: 250 MB');
            $table->string('file_format')->nullable()->comment('PDF, MP3, ZIP...');
            $table->integer('total_lessons')->nullable()->comment('Tổng số bài học');

            // --- NHÓM 4: Trạng thái & Quản lý ---
            $table->string('status')->default('active')->comment('active, inactive, archived');
            $table->unsignedBigInteger('created_by')->nullable();

            // --- NHÓM 5: Cấu trúc kiến thức ---
            $table->text('overview')->nullable()->comment('Tổng quan tài liệu');
            $table->json('chapter_list')->nullable()->comment('Danh sách các chương');
            $table->json('chapter_contents')->nullable()->comment('Nội dung chính từng chương');
            $table->json('chapter_volumes')->nullable()->comment('Khối lượng kiến thức từng chương');

            // --- NHÓM 6: Hành trình học ---
            $table->text('learning_objective')->nullable()->comment('Mục tiêu học tập');
            $table->integer('study_duration')->nullable()->comment('Thời gian học (phút/ngày)');
            $table->integer('review_duration')->nullable()->comment('Thời gian ôn (phút/ngày)');
            $table->text('assessment')->nullable()->comment('Mô tả phương pháp đánh giá');
            $table->json('completion_criteria')->nullable()->comment('Tiêu chí hoàn thành: {min_lessons, min_score}');
            $table->string('journey_status')->default('not_started')->comment('not_started, in_progress, completed');
            $table->boolean('is_passed')->default(false);

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // =========================================================
        // Bảng: document_parts (Chương / Part)
        // =========================================================
        Schema::create('document_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->string('name')->comment('Tên chương');
            $table->integer('order')->default(0)->comment('Thứ tự chương');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete();
        });

        // =========================================================
        // Bảng: document_units (Bài / Unit trong chương)
        // =========================================================
        Schema::create('document_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            $table->string('name')->comment('Tên bài');
            $table->integer('order')->default(0)->comment('Thứ tự bài trong chương');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('document_parts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_units');
        Schema::dropIfExists('document_parts');
        Schema::dropIfExists('documents');
    }
};
