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
        Schema::create('nivel_usuario', function (Blueprint $table) {
            $table->id('nivel_usuario_id');
            $table->foreignId('usuario_id')->constrained('usuario', 'usuario_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('nivel_id')->constrained('nivel', 'nivel_id')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivel_usuario');
    }
};