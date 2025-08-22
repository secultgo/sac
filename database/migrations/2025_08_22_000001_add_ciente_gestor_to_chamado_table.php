<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('chamado', function (Blueprint $table) {
            $table->boolean('chamado_ciente_gestor')->default(0)->after('avaliacao_chamado_id');
            $table->unsignedInteger('chamado_ciente_gestor_id')->nullable()->after('chamado_ciente_gestor');
            $table->foreign('chamado_ciente_gestor_id')->references('usuario_id')->on('usuario')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('chamado', function (Blueprint $table) {
            $table->dropForeign(['chamado_ciente_gestor_id']);
            $table->dropColumn(['chamado_ciente_gestor', 'chamado_ciente_gestor_id']);
        });
    }
};
