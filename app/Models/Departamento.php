<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    // Se sua tabela for nomeada diferente de "departamentos", informe explicitamente:
    protected $table = 'departamento';

    // A chave primária não é "id"
    protected $primaryKey = 'departamento_id';

    // Se não usar timestamps created_at/updated_at, comente estas linhas:
    public $timestamps = false;

    protected $attributes = [
        'excluido_id' => 2,
    ];

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        'departamento_nome',
        'departamento_sigla',
        'departamento_chamado',
    ];

    /**
     * Relação com a tabela de status de exclusão.
     */
    public function excluido()
    {
        return $this->belongsTo(Excluido::class, 'excluido_id', 'excluido_id');
    }

    /**
     * Chamados alocados a este departamento (caso deseje usar).
     */
    //public function chamados()
    //{
        //return $this->hasMany(Chamado::class, 'departamento_id', 'departamento_id');
    //}
}
