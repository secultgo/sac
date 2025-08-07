<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\ServicoChamadoRequest;
use App\Models\ServicoChamado;
use App\Models\Problema;
use Illuminate\Http\Request;

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
        $problemas = Problema::orderBy('problema_nome')->get();
        return view('painel.servicos-chamado.create', compact('problemas'));
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
        $problemas = Problema::orderBy('problema_nome')->get();
        return view('painel.servicos-chamado.edit', [
            'servicosChamado' => $servicosChamado,
            'problemas'       => $problemas,
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
}
