<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->string('password')->nullable(false);
            $table->string('phone')->nullable(false);
            $table->unsignedBigInteger('role_id')->nullable(); // Campo role_id
            $table->unsignedBigInteger('state_id')->nullable(); // Campo state_id
            $table->rememberToken();
            $table->timestamps();

            // Definimos la clave foránea para el campo role_id
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('SET NULL');
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
        Schema::dropIfExists('users');
    }
}
