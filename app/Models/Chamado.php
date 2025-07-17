<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    protected $table = 'chamado';
    protected $primaryKey = 'chamado_id';
    public $timestamps = false;

    protected $fillable = [
        'chamado_descricao',
        'usuario_id',
        'responsavel_id',
        'problema_id',
        'chamado_ip',
        'chamado_anexo',
        'status_chamado_id',
        'chamado_abertura',
        'chamado_atendimento',
        'chamado_fechado',
        'chamado_resolvido',
        'avaliacao_chamado_id',
        'chamado_pendente',
        'chamado_pendencia',
        'servico_chamado_id',
        'local_id',
        'departamento_id',
        'lotacao_id',
    ];
}
