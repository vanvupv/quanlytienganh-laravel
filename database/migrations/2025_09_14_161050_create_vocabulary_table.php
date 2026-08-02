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
        Schema::create('vocabulary', function (Blueprint $table) {
            $table->id();
            $table->string('word'); // từ vựng
            $table->text('meaning'); // nghĩa
            $table->enum('part_of_speech', ['noun', 'verb', 'adj', 'adv', 'other'])->nullable();
            $table->string('pronunciation')->nullable(); // phiên âm
            $table->text('example')->nullable(); // ví dụ
            $table->enum('level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])->nullable(); // cấp độ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary');
    }
};
