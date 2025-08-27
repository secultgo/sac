<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\DepartamentoRequest;
use App\Models\Departamento;
use App\Models\Excluido;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Lista todos os departamentos.
     */
    public function index()
    {
        $departamentos = Departamento::where('excluido_id', 2)
                                   ->orderBy('departamento_nome')
                                   ->get();
        return view('painel.departamentos.index', compact('departamentos'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        $excluidos = Excluido::all();
        return view('painel.departamentos.create', compact('excluidos'));
    }

    /**
     * Armazena um novo departamento.
     */
    public function store(DepartamentoRequest $request)
    {
        Departamento::create($request->validated());
        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento criado com sucesso.');
    }

    /**
     * Exibe detalhes de um departamento (opcional).
     */
    public function show(Departamento $departamento)
    {
        return view('painel.departamentos.show', compact('departamento'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Departamento $departamento)
    {
        $excluidos = Excluido::all();
        return view('painel.departamentos.edit', compact('departamento','excluidos'));
    }

    /**
     * Atualiza um departamento existente.
     */
    public function update(DepartamentoRequest $request, Departamento $departamento)
    {
        $departamento->update($request->validated());
        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento atualizado com sucesso.');
    }

    /**
     * Remove (soft delete) um departamento.
     */
    public function destroy(Departamento $departamento)
    {
        // Exclusão lógica: marca como excluído (excluido_id = 1)
        $departamento->update(['excluido_id' => 1]);
        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento removido com sucesso.');
    }
}
