<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliacaoChamadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('avaliacao_chamado')->insert([
            [
                'avaliacao_chamado_id' => 1,
                'avaliacao_chamado_nome' => 'Ótimo',
                'avaliacao_chamado_imagem' => '<img src="https://sag.go.gov.br/Imagens/Happy.png" alt="" style="padding-top: 5px; padding-bottom: 10px;">'
            ],
            [
                'avaliacao_chamado_id' => 2,
                'avaliacao_chamado_nome' => 'Bom',
                'avaliacao_chamado_imagem' => '<i class="fa fa-smile-o fa-chamado" aria-hidden="true" style="color: #3c43ae;"></i>'
            ],
            [
                'avaliacao_chamado_id' => 3,
                'avaliacao_chamado_nome' => 'Regular',
                'avaliacao_chamado_imagem' => '<i class="fa fa-meh-o fa-chamado" aria-hidden="true" style="color: #458620;"></i>'
            ],
            [
                'avaliacao_chamado_id' => 4,
                'avaliacao_chamado_nome' => 'Ruim',
                'avaliacao_chamado_imagem' => '<i class="fa fa-frown-o fa-chamado" aria-hidden="true" style="color: #c22828;"></i>'
            ]
        ]);
    }
}
