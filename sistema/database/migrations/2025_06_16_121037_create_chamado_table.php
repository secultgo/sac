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
        Schema::create('chamado', function (Blueprint $table) {
            $table->id('chamado_id');
            $table->text('chamado_descricao');
            $table->ipAddress('chamado_ip')->nullable();
            $table->text('chamado_anexo')->nullable();
            $table->timestamp('chamado_abertura')->useCurrent();
            $table->dateTime('chamado_atendimento')->nullable();
            $table->dateTime('chamado_fechado')->nullable();
            $table->dateTime('chamado_resolvido')->nullable();
            $table->dateTime('chamado_pendente')->nullable();
            $table->text('chamado_pendencia')->nullable();
            
            $table->foreignId('usuario_id')->constrained('usuario', 'usuario_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('responsavel_id')->nullable()->constrained('usuario', 'usuario_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('problema_id')->constrained('problema', 'problema_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('status_chamado_Id')->constrained('status_chamado', 'status_chamado_Id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('avaliacao_chamado_id')->nullable()->constrained('avalicao_chamado', 'avaliacao_chamado_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('servico_chamado_id')->constrained('servico_chamado', 'servico_chamado_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('local_id')->constrained('local', 'local_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('departamento_id')->constrained('departamento', 'departamento_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('lotacao_id')->constrained('departamento', 'departamento_id')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamado');
    }
};