<?php

use App\Enums\SessionType;
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
        Schema::table('professors', function (Blueprint $table) {
            $table->integer('type')->default(SessionType::OFFLINE);
            $table->integer('balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->dropColumn('type', 'balance');
        });
    }
};
