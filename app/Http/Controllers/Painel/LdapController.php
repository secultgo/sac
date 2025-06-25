<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\LdapRequest;
use App\Models\Ldap;

class LdapController extends Controller
{
    public function index()
    {
        $ldaps = Ldap::orderBy('ldap_server')->paginate(15);
        return view('painel.ldap.index', compact('ldaps'));
    }

    public function create()
    {
        return view('painel.ldap.create');
    }

    public function store(LdapRequest $request)
    {
        Ldap::create($request->validated());
        return redirect()
            ->route('ldap.index')
            ->with('success', 'Configuração LDAP criada com sucesso.');
    }

    public function edit(Ldap $ldap)
    {
        return view('painel.ldap.edit', compact('ldap'));
    }

    public function update(LdapRequest $request, Ldap $ldap)
    {
        $ldap->update($request->validated());
        return redirect()
            ->route('ldap.index')
            ->with('success', 'Configuração LDAP atualizada com sucesso.');
    }

    public function destroy(Ldap $ldap)
    {
        $ldap->delete();
        return redirect()
            ->route('ldap.index')
            ->with('success', 'Configuração LDAP removida com sucesso.');
    }
}
