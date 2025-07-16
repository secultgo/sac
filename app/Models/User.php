<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_nome',
        'usuario_email',
        'usuario_senha',
        'departamento_id',
        'excluido_id',
        'usuario_ldap',
        'status_id'
    ];

    protected $hidden = [
        'usuario_senha',
    ];

    public function getAuthPassword()
    {
        return $this->usuario_senha;
    }
    
}
