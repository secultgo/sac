<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\ServicoChamadoRequest;
use App\Models\ServicoChamado;
use App\Models\Problema;
use App\Models\Departamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServicoChamadoController extends Controller
{
    public function index()
    {
        $servicos = ServicoChamado::with('problema')
            ->orderBy('servico_chamado_nome')
            ->get();

        return view('painel.servicos-chamado.index', compact('servicos'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // Super admin pode escolher qualquer departamento que atende chamados
            $departamentos = Departamento::where('excluido_id', 2)
                                       ->where('departamento_chamado', true)
                                       ->orderBy('departamento_nome')
                                       ->get();
            $problemas = Problema::where('status_id', 1)->orderBy('problema_nome')->get();
        } else {
            // Gestor ou outros usuários: apenas departamento do usuário (se atende chamados)
            $departamentos = Departamento::where('departamento_id', $user->departamento_id)
                                       ->where('excluido_id', 2)
                                       ->where('departamento_chamado', true)
                                       ->get();
            $problemas = Problema::where('departamento_id', $user->departamento_id)
                               ->where('status_id', 1)
                               ->orderBy('problema_nome')
                               ->get();
        }
        
        return view('painel.servicos-chamado.create', compact('problemas', 'departamentos'));
    }

    public function store(ServicoChamadoRequest $request)
    {
        ServicoChamado::create($request->validated());
        return redirect()
            ->route('servicos.index')
            ->with('success', 'Serviço criado com sucesso.');
    }

    public function edit(ServicoChamado $servicosChamado)
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // Super admin pode escolher qualquer departamento que atende chamados
            $departamentos = Departamento::where('excluido_id', 2)
                                       ->where('departamento_chamado', true)
                                       ->orderBy('departamento_nome')
                                       ->get();
            $problemas = Problema::where('status_id', 1)->orderBy('problema_nome')->get();
        } else {
            // Gestor ou outros usuários: apenas departamento do usuário (se atende chamados)
            $departamentos = Departamento::where('departamento_id', $user->departamento_id)
                                       ->where('excluido_id', 2)
                                       ->where('departamento_chamado', true)
                                       ->get();
            $problemas = Problema::where('departamento_id', $user->departamento_id)
                               ->where('status_id', 1)
                               ->orderBy('problema_nome')
                               ->get();
        }
        
        return view('painel.servicos-chamado.edit', [
            'servicosChamado' => $servicosChamado,
            'problemas'       => $problemas,
            'departamentos'   => $departamentos,
        ]);
    }

    public function update(ServicoChamadoRequest $request, ServicoChamado $servicosChamado)
    {
        $servicosChamado->update($request->validated());
        return redirect()
            ->route('servicos.index')
            ->with('success', 'Serviço atualizado com sucesso.');
    }

    public function destroy(ServicoChamado $servicosChamado)
    {
        $servicosChamado->delete();
        return redirect()
            ->route('servicos.index')
            ->with('success', 'Serviço removido com sucesso.');
    }

    /**
     * Retorna os problemas de um departamento (AJAX)
     */
    public function problemasPorDepartamento($departamentoId)
    {
        $problemas = Problema::where('departamento_id', $departamentoId)
                           ->where('status_id', 1)
                           ->orderBy('problema_nome')
                           ->get(['problema_id', 'problema_nome']);
        return response()->json($problemas);
    }
}
