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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->string('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'english_level')) {
                $table->string('english_level')->default('B1'); // A1, A2, B1, B2, C1, C2
            }
            if (!Schema::hasColumn('users', 'target_goal')) {
                $table->string('target_goal')->nullable();
            }
            if (!Schema::hasColumn('users', 'learning_speed')) {
                $table->string('learning_speed')->default('Trung bình'); // Nhanh, Trung bình, Chậm
            }
            if (!Schema::hasColumn('users', 'retention_rate')) {
                $table->integer('retention_rate')->default(75); // Memory retention percentage baseline
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'english_level', 'target_goal', 'learning_speed', 'retention_rate']);
        });
    }
};
