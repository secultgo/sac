<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusChamadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_chamado')->insert([
            [
                'status_chamado_id' => 1,
                'status_chamado_nome' => 'Aberto'
            ],
            [
                'status_chamado_id' => 2,
                'status_chamado_nome' => 'Atendimento'
            ],
            [
                'status_chamado_id' => 3,
                'status_chamado_nome' => 'Fechado'
            ],
            [
                'status_chamado_id' => 4,
                'status_chamado_nome' => 'Pendente'
            ],
            [
                'status_chamado_id' => 5,
                'status_chamado_nome' => 'Resolvido'
            ],
            [
                'status_chamado_id' => 6,
                'status_chamado_nome' => 'Aguardando resposta usuário'
            ],
            [
                'status_chamado_id' => 7,
                'status_chamado_nome' => 'Cancelado'
            ]
        ]);
    }
}
