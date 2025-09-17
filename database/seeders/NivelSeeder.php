<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nivel')->insert([
            [
                'nivel_id' => 1,
                'nivel_nome' => 'Super Usuário',
                'nivel_slug' => 'super-usuario'
            ],
            [
                'nivel_id' => 2,
                'nivel_nome' => 'Gestor',
                'nivel_slug' => 'gestor'
            ],
            [
                'nivel_id' => 3,
                'nivel_nome' => 'Atendente',
                'nivel_slug' => 'atendente'
            ],
            [
                'nivel_id' => 4,
                'nivel_nome' => 'Usuário',
                'nivel_slug' => 'usuario'
            ]
        ]);
    }
}
