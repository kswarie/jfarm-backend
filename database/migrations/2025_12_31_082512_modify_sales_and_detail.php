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
        Schema::table('sales', function (Blueprint $table) {
//            $table->dropColumn('sales_type');
        });

        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropColumn('item');
            $table->foreignId('stock_id')->after('site_id')->constrained('stocks')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
//            $table->string('sales_type')->nullable();
        });

        Schema::table('sales_details', function (Blueprint $table) {
            $table->string('item');
            $table->dropColumn('stock_id');
        });
    }
};
