<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\ProblemaRequest;
use App\Models\Problema;
use App\Models\Departamento;
use App\Models\Status;
use Illuminate\Http\Request;

class ProblemaController extends Controller
{
    public function index()
    {
        $problemas = Problema::with(['departamento','status'])
            ->orderBy('problema_nome')
            ->get();
        return view('painel.problemas.index', compact('problemas'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('departamento_nome')->get();
        $statuses = Status::orderBy('status_nome')->get();
        return view('painel.problemas.create', compact('departamentos','statuses'));
    }

    public function store(ProblemaRequest $request)
    {
        Problema::create($request->validated());
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema criado com sucesso.');
    }

    public function edit(Problema $problema)
    {
        $departamentos = Departamento::orderBy('departamento_nome')->get();
        $statuses = Status::orderBy('status_nome')->get();
        return view('painel.problemas.edit', compact('problema','departamentos','statuses'));
    }

    public function update(ProblemaRequest $request, Problema $problema)
    {
        $problema->update($request->validated());
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema atualizado com sucesso.');
    }

    public function destroy(Problema $problema)
    {
        $problema->delete();
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema removido com sucesso.');
    }

    /**
     * Coloca status_id = 2 (desativado)
     */
    public function desativar(Problema $problema)
    {
        $problema->status_id = 2;
        $problema->save();
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema desativado.');
    }

    /**
     * Coloca status_id = 1 (ativado)
     */
    public function ativar(Problema $problema)
    {
        $problema->status_id = 1;
        $problema->save();
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema ativado.');
    }

}