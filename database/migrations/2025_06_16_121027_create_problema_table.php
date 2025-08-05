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
            $table->increments('problema_id');
            $table->string('problema_nome', 150);
            $table->unsignedInteger('departamento_id');
            $table->unsignedInteger('status_id')->default(1);

            $table->foreign('departamento_id')->references('departamento_id')->on('departamento');
            $table->foreign('status_id')->references('status_id')->on('status');
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