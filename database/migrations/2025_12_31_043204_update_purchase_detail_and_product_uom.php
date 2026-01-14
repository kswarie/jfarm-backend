<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('uom', 10)->after('name')->nullable();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->integer('age')->after('quantity')->nullable();
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->integer('age')->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('uom');
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('age');
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropColumn('age');
        });
    }
};
