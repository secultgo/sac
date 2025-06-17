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
            $table->id('usuario_id');
            $table->string('usuario_nome', 100);
            $table->string('usuario_usuario', 50);
            $table->string('usuario_email', 100);
            $table->string('usuario_senha', 64)->nullable();
            // Corrigido erro de digitação 'usario_registro' e usando o padrão do Laravel
            $table->timestamp('usuario_registro')->useCurrent();
            $table->string('usuario_foto', 255)->nullable();
            $table->boolean('usuario_ldap')->default(false);
            
            // Recomenda-se usar timestamps() para created_at e updated_at
            // $table->timestamps();

            $table->string('usuario_nascimento', 45)->nullable();
            $table->string('usuario_fone_residencial', 45)->nullable(); // Alterado para string
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
            $table->string('usuario_atividade', 45)->nullable();

            $table->foreignId('status_id')->constrained('status', 'status_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('departamento_id')->nullable()->constrained('departamento', 'departamento_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('excluido_id')->constrained('excluido', 'excluido_id')->onDelete('no action')->onUpdate('no action');
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