<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuario')->insert([
            [
                'usuario_id' => 1,
                'usuario_nome' => 'Administrador',
                'usuario_usuario' => 'admin',
                'usuario_email' => 'Admin@local',
                'usuario_senha' => '21232f297a57a5a743894a0e4a801fc3', // MD5 hash da senha 'admin'
                'usuario_registro' => Carbon::now(),
                'usuario_foto' => null,
                'status_id' => 1, // Ativo
                'departamento_id' => null,
                'excluido_id' => 2, // Não excluído
                'usuario_nascimento' => null,
                'usuario_fone_residencial' => null,
                'usuario_celular' => null,
                'usuario_naturalidade' => null,
                'usuario_cpf' => null,
                'usuario_rg' => null,
                'usuario_rg_emissor' => null,
                'usuario_cep' => null,
                'usuario_estado' => null,
                'usuario_cidade' => null,
                'usuario_logradouro' => null,
                'usuario_numero' => null,
                'usuario_complemento' => null,
                'usuario_bairro' => null,
                'usuario_ldap' => false
            ]
        ]);
    }
}
