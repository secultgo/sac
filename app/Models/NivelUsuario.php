<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelUsuario extends Model
{
    protected $table = 'nivel_usuario';
    protected $primaryKey = 'nivel_usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nivel_id',
    ];
}        
