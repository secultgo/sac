<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExcluidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('excluido')->insert([
            [
                'excluido_id' => 1,
                'excluido_nome' => 'yes'
            ],
            [
                'excluido_id' => 2,
                'excluido_nome' => 'no'
            ]
        ]);
    }
}
