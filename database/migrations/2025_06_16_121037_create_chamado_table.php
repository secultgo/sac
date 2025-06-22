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
            $table->increments('chamado_id');
            $table->text('chamado_descricao');
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('responsavel_id')->nullable();
            $table->unsignedInteger('problema_id');
            $table->string('chamado_ip', 15)->nullable();
            $table->text('chamado_anexo')->nullable();
            $table->unsignedInteger('status_chamado_id');
            $table->timestamp('chamado_abertura')->useCurrent();
            $table->dateTime('chamado_atendimento')->nullable();
            $table->dateTime('chamado_fechado')->nullable();
            $table->dateTime('chamado_resolvido')->nullable();
            $table->unsignedInteger('avaliacao_chamado_id')->nullable();
            $table->dateTime('chamado_pendente')->nullable();
            $table->text('chamado_pendencia')->nullable();
            $table->unsignedInteger('servico_chamado_id');
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('departamento_id');
            $table->unsignedInteger('lotacao_id');

            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->foreign('responsavel_id')->references('usuario_id')->on('usuario');
            $table->foreign('problema_id')->references('problema_id')->on('problema');
            $table->foreign('status_chamado_id')->references('status_chamado_id')->on('status_chamado');
            $table->foreign('avaliacao_chamado_id')->references('avaliacao_chamado_id')->on('avaliacao_chamado');
            $table->foreign('servico_chamado_id')->references('servico_chamado_id')->on('servico_chamado');
            $table->foreign('local_id')->references('local_id')->on('local');
            $table->foreign('departamento_id')->references('departamento_id')->on('departamento');
            $table->foreign('lotacao_id')->references('departamento_id')->on('departamento');
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