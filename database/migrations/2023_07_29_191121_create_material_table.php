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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200)->nullable(false);
            $table->string('name');
            $table->integer('stock')->nullable(false)->default(0);
            $table->boolean('has_series')->default(false);
            $table->unsignedBigInteger('technology_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();

            // Definimos la clave foránea para el campo technology_id
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('SET NULL');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
