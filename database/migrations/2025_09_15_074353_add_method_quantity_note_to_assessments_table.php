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
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('method')->nullable()->after('type');   // phương pháp
            $table->integer('quantity')->default(1)->after('method'); // số lượng câu hỏi
            $table->text('note')->nullable()->after('location');   // ghi chú
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['method', 'quantity', 'note']);
        });
    }
};
