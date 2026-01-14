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
        Schema::table('incubations', function (Blueprint $table) {
            $table->foreignId('incubator_id')->after('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tray_id')->after('incubator_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incubations', function (Blueprint $table) {
            $table->dropForeign('incubations_incubator_id_foreign');
            $table->dropForeign('incubations_tray_id_foreign');
        });
    }
};
