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
        Schema::create('grammars', function (Blueprint $table) {
            $table->id();
            $table->string('question');  // câu hỏi ngữ pháp
            $table->string('answer');    // đáp án đúng
            $table->string('explanation')->nullable(); // giải thích (nếu cần)
            $table->string('skill')->default('grammar'); // skill để phân biệt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammars');
    }
};
