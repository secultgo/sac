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
        Schema::create('servico_chamado', function (Blueprint $table) {
            $table->id('servico_chamado_id');
            $table->string('servico_chamado_nome', 100);
            $table->string('servico_chamado_sla_prazo_atendimento', 100)->nullable();
            $table->string('servico_chamado_sla_prazo_solucao', 100)->nullable();
            $table->foreignId('problema_id')->constrained('problema', 'problema_id')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servico_chamado');
    }
};