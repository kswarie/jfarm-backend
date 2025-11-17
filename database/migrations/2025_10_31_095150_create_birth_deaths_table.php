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
        Schema::create('birth_deaths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cage_id')->constrained('cages')->cascadeOnDelete();
            $table->date('input_date');
            $table->enum('type', ['birth', 'death']);
            $table->integer('quantity')->default(0);
            $table->string('cause', 100)->nullable();
            $table->enum('source', ['hatch', 'purchase'])->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_deaths');
    }
};
