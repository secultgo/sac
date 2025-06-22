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
            $table->increments('departamento_id');
            $table->string('departamento_nome', 100);
            $table->string('departamento_sigla', 20)->nullable();
            $table->boolean('departamento_chamado')->default(false);
            $table->unsignedInteger('excluido_id')->default(2);
            $table->foreign('excluido_id')->references('excluido_id')->on('excluido');
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