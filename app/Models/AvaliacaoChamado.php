<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoChamado extends Model
{
    protected $table = 'avaliacao_chamado';
    protected $primaryKey = 'avaliacao_chamado_id';
    public $timestamps = false;

    protected $fillable = [
        'avaliacao_chamado_nome',
        'avaliacao_chamado_imagem',
    ];

    // Relacionamento com chamados
    public function chamados()
    {
        return $this->hasMany(Chamado::class, 'avaliacao_chamado_id', 'avaliacao_chamado_id');
    }
}
