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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200)->unique();
            $table->date('date')->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('technology_id')->nullable();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('creator_user_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();

            // Definimos la clave foránea para el campo event_id
            $table->foreign('event_id')->references('id')->on('events')->onDelete('SET NULL');
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('SET NULL');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
