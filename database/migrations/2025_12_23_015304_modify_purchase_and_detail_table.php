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
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('purchase_type');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('item');
            $table->foreignId('product_id')->after('site_id')->constrained('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('purchase_type');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->string('item');
            $table->dropForeignIdFor('product_id');
        });
    }
};
