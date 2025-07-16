@extends('adminlte::page')

@section('title', 'Problemas')

@section('content_header')
    <h1>Problemas</h1>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"> 
    <div class="card-header">
        <a href="{{ route('problemas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Departamento</th>
                    <th>Status</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($problemas as $p)
                    <tr>
                        <td>{{ $p->problema_id }}</td>
                        <td>{{ $p->problema_nome }}</td>
                        <td>{{ $p->departamento->departamento_nome }}</td>
                        <td>{{ $p->status->status_nome }}</td>
                        <td class="text-right">
                            @if($p->status_id == 1)
                                <form action="{{ route('problemas.desativar', $p) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-default" title="Desativar">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('problemas.ativar', $p) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-default" title="Ativar">
                                        <i class="fas fa-toggle-off"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('problemas.edit', $p) }}" class="btn btn-sm btn-info"><i
                                    class="fas fa-edit"></i></a>
                            <form action="{{ route('problemas.destroy', $p) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Deseja realmente excluir?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum problema cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $problemas->links() }}</div>
</div>
@stop