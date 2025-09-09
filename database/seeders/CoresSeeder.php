<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cor')->insert([
            ['cor_id' => 1, 'cor_nome' => 'azul'],
            ['cor_id' => 2, 'cor_nome' => 'vermelho'],
            ['cor_id' => 3, 'cor_nome' => 'verde'],
            ['cor_id' => 4, 'cor_nome' => 'amarelo'],
            ['cor_id' => 5, 'cor_nome' => 'roxo'],
            ['cor_id' => 6, 'cor_nome' => 'laranja'],
            ['cor_id' => 7, 'cor_nome' => 'rosa'],
            ['cor_id' => 8, 'cor_nome' => 'marrom'],
            ['cor_id' => 9, 'cor_nome' => 'cinza'],
            ['cor_id' => 10, 'cor_nome' => 'preto'],
            ['cor_id' => 11, 'cor_nome' => 'branco'],
            ['cor_id' => 12, 'cor_nome' => 'azul-claro'],
            ['cor_id' => 13, 'cor_nome' => 'azul-escuro'],
            ['cor_id' => 14, 'cor_nome' => 'verde-claro'],
            ['cor_id' => 15, 'cor_nome' => 'verde-escuro'],
            ['cor_id' => 16, 'cor_nome' => 'dourado'],
            ['cor_id' => 17, 'cor_nome' => 'prata'],
            ['cor_id' => 18, 'cor_nome' => 'bege'],
            ['cor_id' => 19, 'cor_nome' => 'turquesa'],
            ['cor_id' => 20, 'cor_nome' => 'lilás'],
        ]);
    }

}
