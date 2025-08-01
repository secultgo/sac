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
        Schema::create('config', function (Blueprint $table) {
            $table->increments('config_id');
            $table->string('config_nome_site', 200);
            $table->text('config_logo')->nullable();
            $table->text('config_analytics')->nullable();
            $table->string('config_email', 100)->nullable();
            $table->string('config_endereco', 100)->nullable();
            $table->string('config_telefone', 20)->nullable();
            $table->text('config_manutencao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};