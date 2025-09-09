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
        'usuario_cor_id',
        'usuario_usuario',  
        'usuario_email',
        'departamento_id',
        'usuario_cpf',
        'usuario_celular',
        'usuario_fone_residencial',
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

    /**
     * Verifica se o usuário tem permissão para atendimentos (nível 1, 2 ou 3)
     */
    public function podeAtender()
    {
        return \DB::table('nivel_usuario')
            ->where('usuario_id', $this->usuario_id)
            ->whereIn('nivel_id', [1, 2, 3])
            ->exists();
    }

    /**
     * Verifica se o usuário é gestor (nível 1 ou 2)
     */
    public function isGestor()
    {
        return \DB::table('nivel_usuario')
            ->where('usuario_id', $this->usuario_id)
            ->whereIn('nivel_id', [1, 2])
            ->exists();
    }

    /**
     * Verifica se o usuário é super admin (nível 1)
     */
    public function isSuperAdmin()
    {
        return \DB::table('nivel_usuario')
            ->where('usuario_id', $this->usuario_id)
            ->where('nivel_id', 1)
            ->exists();
    }

    /**
     * Verifica se o usuário é apenas usuário comum (nível 4)
     */
    public function isUsuarioComum()
    {
        return \DB::table('nivel_usuario')
            ->where('usuario_id', $this->usuario_id)
            ->where('nivel_id', 4)
            ->exists();
    }

    /**
     * Verifica se o usuário tem departamento cadastrado
     */
    public function temDepartamento()
    {
        return !empty($this->departamento_id);
    }

    /**
     * Verifica se o perfil do usuário está completo (departamento e telefone)
     */
    public function perfilCompleto()
    {
        return !empty($this->departamento_id) && !empty($this->usuario_fone_residencial);
    }

    /**
     * Relação com departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }
    
}
