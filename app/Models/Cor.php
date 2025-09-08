<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{

    protected $table = 'cor';
    protected $primaryKey = 'cor_id';
    public $timestamps = true;

    protected $fillable = [
        'cor_nome',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,         // Model relacionada
            'cor_id',               // FK da tabela pivot para esta model
            'usuario_id'            // FK da tabela pivot para model relacionada
        );
    }
}
