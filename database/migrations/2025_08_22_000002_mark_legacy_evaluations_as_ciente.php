<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Marca como ciente todas as avaliações antigas (3 e 4) existentes antes desta feature
        DB::table('chamado')
            ->whereIn('avaliacao_chamado_id', [3,4])
            ->whereNotNull('avaliacao_chamado_id')
            ->update(['chamado_ciente_gestor' => 1]);
    }

    public function down(): void
    {
        // Não é seguro reverter, então deixamos como no-op
    }
};
