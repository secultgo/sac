<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusChamado extends Model
{
    // Tabela do banco
    protected $table = 'status_chamado';

    // Chave primária customizada
    protected $primaryKey = 'status_chamado_id';

    // Não há created_at/updated_at
    public $timestamps = false;

    // Campos preenchíveis
    protected $fillable = [
        'status_chamado_nome',
    ];

    /**
     * Status disponíveis para chamados
     * 1 = Aberto
     * 2 = Atendimento  
     * 3 = Fechado
     * 4 = Pendente
     * 5 = Resolvido
     * 6 = Aguardando resposta usuário
     * 7 = Cancelado
     */
    public const ABERTO = 1;
    public const ATENDIMENTO = 2;
    public const FECHADO = 3;
    public const PENDENTE = 4;
    public const RESOLVIDO = 5;
    public const AGUARDANDO_USUARIO = 6;
    public const CANCELADO = 7;

    /**
     * Um status de chamado pode ter vários chamados
     */
    public function chamados()
    {
        return $this->hasMany(Chamado::class, 'status_chamado_id', 'status_chamado_id');
    }
}
