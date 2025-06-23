<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class ProblemaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'problema_nome'     => 'required|string|max:50',
            'departamento_id'   => 'required|exists:departamento,departamento_id',
        ];
    }

    public function attributes()
    {
        return [
            'problema_nome'   => 'nome do problema',
            'departamento_id' => 'departamento',
        ];
    }
}
