<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $table = 'local';
    protected $primaryKey = 'local_id';
    public $timestamps = false;

    protected $fillable = [
        'local_nome',
        'local_sigla',
        'local_endereco',
        'local_telefone',
    ];
}
