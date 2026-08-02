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
        Schema::create('hierarchy_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('hierarchy_trees')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('note')->nullable(); // Description of changes in this version
            $table->json('nodes_snapshot'); // Complete JSON snapshot of all nodes in this version
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchy_versions');
    }
};
