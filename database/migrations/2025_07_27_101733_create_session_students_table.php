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
        Schema::create('session_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('professor_price')->default(0);
            $table->integer('center_price')->default(0);
            $table->decimal('printables', 10, 2)->default(0);
            $table->integer('to_pay')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_students');
    }
};
