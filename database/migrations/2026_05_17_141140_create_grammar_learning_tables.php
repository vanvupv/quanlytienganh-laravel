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
        Schema::create('grammar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('grammar_categories')->onDelete('cascade');
        });

        Schema::create('grammar_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('grammar_categories')->onDelete('cascade');
        });

        Schema::create('grammar_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->string('title');
            $table->longText('content')->nullable(); // JSON containing formula, usage
            $table->longText('example')->nullable(); // JSON containing examples
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('grammar_topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_lessons');
        Schema::dropIfExists('grammar_topics');
        Schema::dropIfExists('grammar_categories');
    }
};
