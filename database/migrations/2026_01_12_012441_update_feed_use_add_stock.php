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
        Schema::table('feed_uses', function (Blueprint $table) {
            $table->foreignId('stock_id')->after('site_id')->nullable()->constrained('stocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_uses', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });
    }
};
