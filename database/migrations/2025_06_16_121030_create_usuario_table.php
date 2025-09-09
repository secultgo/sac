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
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('usuario_id');
            $table->string('usuario_nome', 100);
            $table->unsignedBigInteger('usuario_cor_id')->nullable();
            $table->string('usuario_usuario', 50)->nullable();
            $table->string('usuario_email', 100);
            $table->string('usuario_senha', 64)->nullable();
            $table->timestamp('usuario_registro')->useCurrent();
            $table->string('usuario_foto', 255)->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedInteger('departamento_id')->nullable();
            $table->unsignedInteger('excluido_id')->default(2);
            $table->string('usuario_nascimento', 45)->nullable();
            $table->string('usuario_fone_residencial', 45)->nullable();
            $table->string('usuario_celular', 45)->nullable();
            $table->string('usuario_naturalidade', 45)->nullable();
            $table->string('usuario_cpf', 45)->nullable();
            $table->string('usuario_rg', 45)->nullable();
            $table->string('usuario_rg_emissor', 45)->nullable();
            $table->string('usuario_cep', 45)->nullable();
            $table->string('usuario_estado', 45)->nullable();
            $table->string('usuario_cidade', 45)->nullable();
            $table->string('usuario_logradouro', 45)->nullable();
            $table->string('usuario_numero', 45)->nullable();
            $table->string('usuario_complemento', 45)->nullable();
            $table->string('usuario_bairro', 45)->nullable();
            $table->boolean('usuario_ldap')->default(false);

            $table->foreign('usuario_cor_id')->references('cor_id')->on('cor');
            $table->foreign('status_id')->references('status_id')->on('status');
            $table->foreign('departamento_id')->references('departamento_id')->on('departamento');
            $table->foreign('excluido_id')->references('excluido_id')->on('excluido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};