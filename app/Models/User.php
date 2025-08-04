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

    // Mapear o campo name para usuario_nome
    protected $appends = ['name'];
    
    protected $hidden = [
        'usuario_senha',
    ];

    public function getAuthPassword()
    {
        return $this->usuario_senha;
    }

    // Método para o AdminLTE usar como nome do usuário
    public function adminlte_desc()
    {
        return $this->usuario_nome;
    }

    public function adminlte_profile_url()
    {
        return '#';
    }

    // Getter padrão para o nome (usado pelo Laravel-AdminLTE)
    public function getNameAttribute()
    {
        return $this->usuario_nome;
    }

    // Método getName que o AdminLTE pode usar
    public function getName()
    {
        return $this->usuario_nome;
    }
    
    public function nivelUsuarios()
    {
        return $this->hasMany(NivelUsuario::class, 'usuario_id', 'usuario_id');
    }
    
}
