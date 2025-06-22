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
        Schema::create('avaliacao_chamado', function (Blueprint $table) {
            $table->increments('avaliacao_chamado_id');
            $table->string('avaliacao_chamado_nome', 45);
            $table->text('avaliacao_chamado_imagem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_chamado');
    }
};