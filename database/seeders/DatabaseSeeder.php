<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Executar os seeders de dados básicos do sistema
        $this->call([
            StatusSeeder::class,
            ExcluidoSeeder::class,
            NivelSeeder::class,
            StatusChamadoSeeder::class,
            AvaliacaoChamadoSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}
