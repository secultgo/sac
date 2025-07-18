<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $usuarioId = $this->usuario_id ?? null;

        // Se for importação LDAP, regras diferentes
        if ($this->routeIs('usuarios.importar.ldap')) {
            return [
                'usuario_nome'     => 'required|string|max:100',
                'usuario_email'    => 'nullable|email|max:100|unique:usuario,usuario_email,' . $usuarioId . ',usuario_id',
                'usuario_cpf'      => 'nullable|string|max:14',
                'usuario_ldap'     => 'required|in:0,1',
                'status_id'        => 'required|exists:status,status_id',
                'excluido_id'      => 'required',
            ];
        }
    
        // Fluxo normal (cadastro manual)
        return [
            'usuario_nome'     => 'required|string|max:100',
            'usuario_email'    => 'required|email|max:100|unique:usuario,usuario_email,' . $usuarioId . ',usuario_id',
            'usuario_cpf'      => ['required', 'string', 'max:14', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', 'unique:usuario,usuario_cpf,' . $usuarioId . ',usuario_id'],
            'usuario_senha'    => 'required|string|min:5',
            'departamento_id'  => 'required|exists:departamento,departamento_id',
            'usuario_ldap'     => 'required|in:0,1',
        ];
    }


}
