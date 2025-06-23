<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // Tabela existente no banco
    protected $table = 'status';

    // Chave primária customizada
    protected $primaryKey = 'status_id';

    // Não há created_at/updated_at
    public $timestamps = false;

    // Campos preenchíveis
    protected $fillable = [
        'status_nome',
        'status_slug',
    ];

    /**
     * Um status pode pertencer a vários problemas.
     */
    public function problemas()
    {
        return $this->hasMany(Problema::class, 'status_id', 'status_id');
    }

    /**
     * Um status pode pertencer a vários usuários.
     */
    //public function usuarios()
    //{
        //return $this->hasMany(Usuario::class, 'status_id', 'status_id');
    //}
}
