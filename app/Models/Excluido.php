<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excluido extends Model
{
    // Se sua tabela não seguir o plural padrão:
    protected $table = 'excluido';

    // Chave primária customizada:
    protected $primaryKey = 'excluido_id';

    // Se não usar created_at/updated_at:
    public $timestamps = false;

    // Mass assignable:
    protected $fillable = [
        'excluido_nome',
    ];
}
