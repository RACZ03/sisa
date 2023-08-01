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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Definimos la clave foránea para el campo State
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
