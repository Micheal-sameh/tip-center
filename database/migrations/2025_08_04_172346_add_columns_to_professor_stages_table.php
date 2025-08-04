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
        Schema::table('professor_stages', function (Blueprint $table) {
            $table->tinyInteger('day');
            $table->time('from');
            $table->time('to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professor_stages', function (Blueprint $table) {
            $table->dropColumn('day');
            $table->dropColumn('from');
            $table->dropColumn('to');
        });
    }
};
