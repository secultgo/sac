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
            $table->id('comentario_chamado_id');
            $table->text('comentario_chamado_comentario');
            $table->dateTime('comentario_chamado_data')->nullable();
            $table->text('comentario_chamado_anexo')->nullable();
            
            $table->foreignId('chamado_id')->constrained('chamado', 'chamado_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('usuario_id')->constrained('usuario', 'usuario_id')->onDelete('no action')->onUpdate('no action');
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