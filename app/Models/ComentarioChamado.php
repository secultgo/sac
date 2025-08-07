<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioChamado extends Model
{
    protected $table = 'comentario_chamado';
    protected $primaryKey = 'comentario_chamado_id';
    public $timestamps = false;

    protected $fillable = [
        'comentario_chamado_comentario',
        'comentario_chamado_data',
        'chamado_id',
        'comentario_chamado_anexo',
        'usuario_id'
    ];

    protected $dates = [
        'comentario_chamado_data'
    ];

    /**
     * Relacionamento com o chamado
     */
    public function chamado()
    {
        return $this->belongsTo(Chamado::class, 'chamado_id', 'chamado_id');
    }

    /**
     * Relacionamento com o usuário que fez o comentário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }
}
