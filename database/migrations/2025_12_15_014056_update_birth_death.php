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
        Schema::table('birth_deaths', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_id')->nullable()->after('cage_id');
            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_deaths', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });
    }
};
