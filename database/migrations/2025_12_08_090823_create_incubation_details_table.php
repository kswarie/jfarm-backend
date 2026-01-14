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
        Schema::create('incubation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incubation_id');
            $table->integer('egg_qty')->default(0);
            $table->integer('hatch_qty')->default(0);
            $table->integer('damage_qty')->default(0);
            $table->text('remarks')->nullable();
            $table->foreign('incubation_id')->references('id')->on('incubations')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incubation_details');
    }
};
