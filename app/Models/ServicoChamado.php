<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicoChamado extends Model
{
    protected $table = 'servico_chamado';
    protected $primaryKey = 'servico_chamado_id';
    public $timestamps = false;

    protected $fillable = [
        'problema_id',
        'servico_chamado_nome',
    ];

    public function problema()
    {
        return $this->belongsTo(Problema::class, 'problema_id', 'problema_id');
    }
}
