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
        Schema::create('problema', function (Blueprint $table) {
            $table->id('problema_id');
            $table->string('problema_nome', 50)->nullable();
            $table->foreignId('departamento_id')->constrained('departamento', 'departamento_id')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('status_id')->constrained('status', 'status_id')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problema');
    }
};