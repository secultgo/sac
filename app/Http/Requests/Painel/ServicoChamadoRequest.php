<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class ServicoChamadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'problema_id' => 'required|exists:problema,problema_id',
            'servico_chamado_nome' => 'required|string|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'problema_id' => 'problema',
            'servico_chamado_nome' => 'nome do serviço',
        ];
    }
}
