<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class LdapRequest extends FormRequest
{
    public function authorize()
    {
        return true; // sem auth por enquanto
    }

    public function rules()
    {
        return [
            'ldap_server' => 'required|string|max:120',
            'ldap_user'   => 'required|string|max:120',
            'ldap_pass'   => 'required|string|max:120',
            'ldap_tree'   => 'required|string|max:120',
            'ldap_filter' => 'required|string|max:200',
        ];
    }

    public function attributes()
    {
        return [
            'ldap_server' => 'servidor LDAP',
            'ldap_user'   => 'usuário LDAP',
            'ldap_pass'   => 'senha LDAP',
            'ldap_tree'   => 'base (tree) LDAP',
            'ldap_filter' => 'filtro LDAP',
        ];
    }
}
