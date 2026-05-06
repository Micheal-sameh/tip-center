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
        Schema::create('session_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained()->onDelete('cascade');
            $table->decimal('markers', 10, 2)->default('0.00');
            $table->decimal('copies', 10, 2)->default('0.00');
            $table->decimal('cafeterea', 10, 2)->default('0.00');
            $table->decimal('other', 10, 2)->default('0.00');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_extras');
    }
};
