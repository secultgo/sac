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
        Schema::create('comentario_chamado', function (Blueprint $table) {
            $table->increments('comentario_chamado_id');
            $table->text('comentario_chamado_comentario');
            $table->dateTime('comentario_chamado_data')->nullable();
            $table->unsignedInteger('chamado_id');
            $table->text('comentario_chamado_anexo')->nullable();
            $table->unsignedInteger('usuario_id');

            $table->foreign('chamado_id')->references('chamado_id')->on('chamado');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentario_chamado');
    }
};