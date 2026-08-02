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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_detail_id')->constrained()->onDelete('cascade');
            $table->string('user_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
