<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class LocalRequest extends FormRequest
{
    public function authorize()
    {
        return true; // sem auth por enquanto
    }

    public function rules()
    {
        return [
            'local_nome'     => 'required|string|max:50',
            'local_sigla'    => 'nullable|string|max:10',
            'local_endereco' => 'nullable|string',
            'local_telefone' => 'nullable|string|max:20',
        ];
    }

    public function attributes()
    {
        return [
            'local_nome'     => 'nome do local',
            'local_sigla'    => 'sigla',
            'local_endereco' => 'endereço',
            'local_telefone' => 'telefone',
        ];
    }
}
