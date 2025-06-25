@extends('adminlte::page')

@section('title', 'LDAP')

@section('content_header')
    <h1>Configurações LDAP</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('ldap.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servidor</th>
                    <th>Usuário</th>
                    <th>Base</th>
                    <th>Filtro</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ldaps as $l)
                    <tr>
                        <td>{{ $l->ldap_id }}</td>
                        <td>{{ $l->ldap_server }}</td>
                        <td>{{ $l->ldap_user }}</td>
                        <td>{{ $l->ldap_tree }}</td>
                        <td>{{ $l->ldap_filter }}</td>
                        <td class="text-right">
                            <a href="{{ route('ldap.edit', $l) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('ldap.destroy', $l) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Deseja excluir esta configuração?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma configuração LDAP cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $ldaps->links() }}
    </div>
</div>
@stop