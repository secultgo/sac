<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\ProblemaRequest;
use App\Models\Problema;
use App\Models\Departamento;
use App\Models\Status;

class ProblemaController extends Controller
{
    public function index()
    {
        $query = Problema::with(['departamento','status']);
        
        // Se for gestor (não super admin), filtrar apenas problemas do seu departamento
        if (auth()->user()->isGestor() && !auth()->user()->isSuperAdmin()) {
            $query->where('departamento_id', auth()->user()->departamento_id);
        }
        
        $problemas = $query->orderBy('problema_nome')->get();
        return view('painel.problemas.index', compact('problemas'));
    }

    public function create()
    {
        $query = Departamento::where('excluido_id', 2);
        
        // Se for gestor (não super admin), filtrar apenas seu departamento
        if (auth()->user()->isGestor() && !auth()->user()->isSuperAdmin()) {
            $query->where('departamento_id', auth()->user()->departamento_id);
        }
        
        $departamentos = $query->orderBy('departamento_nome')->get();
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
        $query = Departamento::where('excluido_id', 2);
        
        // Se for gestor (não super admin), filtrar apenas seu departamento
        if (auth()->user()->isGestor() && !auth()->user()->isSuperAdmin()) {
            $query->where('departamento_id', auth()->user()->departamento_id);
        }
        
        $departamentos = $query->orderBy('departamento_nome')->get();
        $statuses = Status::orderBy('status_nome')->get();
        return view('painel.problemas.edit', compact('problema','departamentos','statuses'));
    }

    public function update(ProblemaRequest $request, Problema $problema)
    {
        $problema->update($request->validated());
        return redirect()->route('problemas.index')
                         ->with('success', 'Problema atualizado com sucesso.');
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