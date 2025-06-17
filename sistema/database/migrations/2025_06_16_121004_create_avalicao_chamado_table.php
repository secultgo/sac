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
        Schema::create('avalicao_chamado', function (Blueprint $table) {
            $table->id('avaliacao_chamado_id');
            $table->string('avalicao_chamado_nome', 45);
            $table->text('avalicao_chamado_imagem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avalicao_chamado');
    }
};