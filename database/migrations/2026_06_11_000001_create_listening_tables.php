<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng listening, listening_topics, listening_transcripts
     * với đầy đủ trường thông tin file nghe
     */
    public function up(): void
    {
        // =========================================================
        // Bảng: listening_topics (Chủ đề)
        // =========================================================
        Schema::create('listening_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên chủ đề');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // =========================================================
        // Bảng: listening (File nghe)
        // =========================================================
        Schema::create('listening', function (Blueprint $table) {
            $table->id();

            // --- Thông tin cơ bản ---
            $table->string('title')->comment('Tên file / tiêu đề bài nghe');
            $table->string('link')->nullable()->comment('Đường dẫn file audio');
            $table->string('type')->nullable()->comment('Định dạng: mp3, wav, aac...');
            $table->string('size')->nullable()->comment('Dung lượng VD: 5.2 MB');
            $table->integer('duration')->nullable()->comment('Thời gian (giây)');

            // --- Phân loại ---
            $table->string('level')->nullable()->comment('A1, A2, B1, B2, C1, C2');
            $table->decimal('score_level', 5, 2)->nullable()->comment('Mức điểm tối đa (0–100)');
            $table->integer('listen_count')->default(0)->comment('Tổng số lần đã nghe');

            // --- Liên kết cấu trúc tài liệu ---
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable()->comment('FK → document_units');
            $table->unsignedBigInteger('chapter_id')->nullable()->comment('FK → document_parts');

            // --- Chủ đề ---
            $table->unsignedBigInteger('topic_id')->nullable();

            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
            $table->foreign('unit_id')->references('id')->on('document_units')->nullOnDelete();
            $table->foreign('chapter_id')->references('id')->on('document_parts')->nullOnDelete();
            $table->foreign('topic_id')->references('id')->on('listening_topics')->nullOnDelete();
        });

        // =========================================================
        // Bảng: listening_transcripts (Transcript)
        // =========================================================
        Schema::create('listening_transcripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listening_id');

            // Phân loại
            $table->string('type')->default('original')->comment('original, translation, subtitle');
            $table->string('language_code')->default('en')->comment('en, vi, ...');

            // Nội dung
            $table->longText('transcript')->nullable()->comment('Nội dung transcript');
            $table->integer('sentence_count')->nullable();
            $table->integer('word_count')->nullable();

            $table->timestamps();

            $table->foreign('listening_id')->references('id')->on('listening')->cascadeOnDelete();
            $table->index(['listening_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_transcripts');
        Schema::dropIfExists('listening');
        Schema::dropIfExists('listening_topics');
    }
};
