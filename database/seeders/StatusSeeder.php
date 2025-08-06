<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            [
                'status_id' => 1,
                'status_nome' => 'Ativo',
                'status_slug' => 'ativo'
            ],
            [
                'status_id' => 2,
                'status_nome' => 'Inativo',
                'status_slug' => 'inativo'
            ]
        ]);
    }
}
