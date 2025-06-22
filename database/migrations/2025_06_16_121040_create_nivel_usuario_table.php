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
        Schema::create('nivel_usuario', function (Blueprint $table) {
            $table->increments('nivel_usuario_id');
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('nivel_id');

            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->foreign('nivel_id')->references('nivel_id')->on('nivel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivel_usuario');
    }
};