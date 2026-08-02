<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_details', function (Blueprint $table) {
            $table->foreignId('grammar_id')->nullable()->after('vocabulary_id')->constrained('grammars')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_details', function (Blueprint $table) {
            $table->dropForeign(['grammar_id']);
            $table->dropColumn('grammar_id');
        });
    }
};
