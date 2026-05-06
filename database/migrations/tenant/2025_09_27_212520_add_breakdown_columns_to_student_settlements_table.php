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
        Schema::table('student_settlements', function (Blueprint $table) {
            $table->decimal('center', 10, 2)->default(0);
            $table->decimal('professor_amount', 10, 2)->default(0);
            $table->decimal('materials', 10, 2)->default(0);
            $table->decimal('printables', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_settlements', function (Blueprint $table) {
            $table->dropColumn(['center', 'professor_amount', 'materials', 'printables']);
        });
    }
};
