<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();

            // Definimos la clave foránea para el campo state_id
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technologies');
    }
};
