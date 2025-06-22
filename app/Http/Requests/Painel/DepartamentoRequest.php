<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class DepartamentoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'departamento_nome'    => 'required|string|max:100',
            'departamento_sigla'   => 'nullable|string|max:20',
            'departamento_chamado' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'departamento_nome'    => 'nome do departamento',
            'departamento_sigla'   => 'sigla',
            'departamento_chamado' => 'flag de chamado',
        ];
    }
}
