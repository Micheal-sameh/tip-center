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
        Schema::create('professor_stage_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('professors')->onDelete('cascade');
            $table->integer('stage');
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('materials_balance', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['professor_id', 'stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_stage_balances');
    }
};
