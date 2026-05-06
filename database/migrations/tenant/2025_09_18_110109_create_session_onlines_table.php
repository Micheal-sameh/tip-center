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
        Schema::create('session_onlines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('session_id')->constrained()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('professor', 10, 2)->default(0);
            $table->decimal('center', 10, 2)->default(0);
            $table->decimal('materials', 10, 2)->default(0);

            $table->integer('stage')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_onlines');
    }
};
