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
            $table->foreignId('session_student_id')->nullable()->constrained('session_students')->nullOnDelete();
            $table->foreignId('settled_in_session_id')->nullable()->constrained('sessions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_settlements', function (Blueprint $table) {
            $table->dropForeign(['session_student_id']);
            $table->dropForeign(['settled_in_session_id']);
            $table->dropColumn(['session_student_id', 'settled_in_session_id']);
        });
    }
};
