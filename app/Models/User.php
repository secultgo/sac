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
        'usuario_id',
        'usuario_nome',
        'usuario_usuario',  
        'usuario_email',
        'departamento_id',
        'usuario_cpf',
        'usuario_celular',
        'status_id',
        'usuario_ldap',
        'nivel_id',
        'ldap_id',
        'usuario_senha',
    ];
    
    protected $hidden = [
        'usuario_senha',
    ];

    public function getAuthPassword()
    {
        return $this->usuario_senha;
    }
    public function nivelUsuarios()
    {
        return $this->hasMany(NivelUsuario::class, 'usuario_id', 'usuario_id');
    }
    
}
