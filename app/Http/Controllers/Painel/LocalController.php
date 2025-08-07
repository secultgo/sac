<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\LocalRequest;
use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function index()
    {
        $locais = Local::orderBy('local_nome')->get();
        return view('painel.locais.index', compact('locais'));
    }

    public function create()
    {
        return view('painel.locais.create');
    }

    public function store(LocalRequest $request)
    {
        Local::create($request->validated());
        return redirect()
            ->route('locais.index')
            ->with('success', 'Local criado com sucesso.');
    }

    public function edit(Local $local)
    {
        return view('painel.locais.edit', compact('local'));
    }

    public function update(LocalRequest $request, Local $local)
    {
        $local->update($request->validated());
        return redirect()
            ->route('locais.index')
            ->with('success', 'Local atualizado com sucesso.');
    }

    public function destroy(Local $local)
    {
        $local->delete();
        return redirect()
            ->route('locais.index')
            ->with('success', 'Local removido com sucesso.');
    }
}
