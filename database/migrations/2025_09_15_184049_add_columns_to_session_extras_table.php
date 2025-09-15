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
        Schema::table('session_extras', function (Blueprint $table) {
            $table->integer('other_print')->default(0)->after('other');
            $table->integer('out_going')->default(0)->after('other_print');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_extras', function (Blueprint $table) {
            $table->dropColumn(['out_going', 'others_print']);
        });
    }
};
