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
        $avaliacoes = [
            [
                'avaliacao_chamado_id' => 1,
                'avaliacao_chamado_nome' => 'Ótimo',
                'avaliacao_chamado_imagem' => '<i class="fas fa-grin-stars fa-2x" style="color: #28a745;" title="Ótimo"></i>'
            ],
            [
                'avaliacao_chamado_id' => 2,
                'avaliacao_chamado_nome' => 'Bom',
                'avaliacao_chamado_imagem' => '<i class="fas fa-smile fa-2x" style="color: #20c997;" title="Bom"></i>'
            ],
            [
                'avaliacao_chamado_id' => 3,
                'avaliacao_chamado_nome' => 'Regular',
                'avaliacao_chamado_imagem' => '<i class="fas fa-meh fa-2x" style="color: #ffc107;" title="Regular"></i>'
            ],
            [
                'avaliacao_chamado_id' => 4,
                'avaliacao_chamado_nome' => 'Ruim',
                'avaliacao_chamado_imagem' => '<i class="fas fa-frown fa-2x" style="color: #fd7e14;" title="Ruim"></i>'
            ]
        ];

        foreach ($avaliacoes as $avaliacao) {
            DB::table('avaliacao_chamado')
                ->updateOrInsert(
                    ['avaliacao_chamado_id' => $avaliacao['avaliacao_chamado_id']],
                    $avaliacao
                );
        }

        $this->command->info('Ícones de avaliação atualizados com sucesso!');
        $this->command->info('- Ótimo: Emoji com estrelas (verde)');
        $this->command->info('- Bom: Sorriso (verde água)');
        $this->command->info('- Regular: Neutro (amarelo)');
        $this->command->info('- Ruim: Triste (laranja)');
    }
}
