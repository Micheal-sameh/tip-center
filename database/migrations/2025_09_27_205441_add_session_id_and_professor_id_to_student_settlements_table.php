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
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('professors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_settlements', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropForeign(['professor_id']);
            $table->dropColumn(['session_id', 'professor_id']);
        });
    }
};
