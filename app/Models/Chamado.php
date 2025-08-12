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

    protected $casts = [
        'chamado_abertura' => 'datetime',
        'chamado_atendimento' => 'datetime',
        'chamado_fechado' => 'datetime',
        'chamado_resolvido' => 'datetime',
        'chamado_pendente' => 'datetime',
    ];

    // Relacionamentos
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id', 'usuario_id');
    }

    public function problema()
    {
        return $this->belongsTo(Problema::class, 'problema_id', 'problema_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }

    /**
     * Relacionamento com Departamento (Lotação)
     */
    public function departamentoLotacao()
    {
        return $this->belongsTo(Departamento::class, 'lotacao_id', 'departamento_id');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id', 'local_id');
    }

    public function servicoChamado()
    {
        return $this->belongsTo(ServicoChamado::class, 'servico_chamado_id', 'servico_chamado_id');
    }

    public function statusChamado()
    {
        return $this->belongsTo(Status::class, 'status_chamado_id', 'status_id');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioChamado::class, 'chamado_id', 'chamado_id')->orderBy('comentario_chamado_data');
    }

    public function avaliacaoChamado()
    {
        return $this->belongsTo(AvaliacaoChamado::class, 'avaliacao_chamado_id', 'avaliacao_chamado_id');
    }
}
