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
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200)->unique();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('count')->nullable()->default(0);
            $table->string('series')->nullable();
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->timestamps();

            // Definimos la clave foránea para el campo material_id
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('SET NULL');
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_details');
    }
};
