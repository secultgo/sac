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
        Schema::create('departamento', function (Blueprint $table) {
            $table->id('departamento_id');
            $table->string('departamento_nome', 100);
            $table->string('departamento_sigla', 20);
            $table->tinyInteger('departamento_chamado');
            $table->foreignId('excluido_id')->constrained('excluido', 'excluido_id')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};