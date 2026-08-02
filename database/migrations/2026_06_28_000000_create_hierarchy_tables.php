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
        // 1. Table for trees / templates
        Schema::create('hierarchy_trees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Lộ trình Ngữ pháp Giai đoạn 1"
            $table->string('code')->unique(); // e.g. "grammar_stage_1"
            $table->string('module'); // e.g. "grammar", "vocabulary", "listening", "custom"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Table for nodes (Recursive & Polymorphic)
        Schema::create('hierarchy_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('hierarchy_trees')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('hierarchy_nodes')->onDelete('cascade');
            $table->string('title');
            $table->string('icon')->nullable(); // Emoji or line-icons class
            $table->string('nodeable_type')->nullable(); // Laravel Polymorphic relation type
            $table->unsignedBigInteger('nodeable_id')->nullable(); // Laravel Polymorphic relation ID
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchy_nodes');
        Schema::dropIfExists('hierarchy_trees');
    }
};
