@extends('adminlte::page')

@section('title', 'Serviços')

@section('content_header')
    <h1>Serviços</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('servicos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Departamento</th>
                    <th>Problema</th>
                    <th>Serviço</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicos as $s)
                    <tr>
                        <td>{{ $s->servico_chamado_id }}</td>
                        <td>{{ $s->problema->departamento->departamento_nome }}</td>
                        <td>{{ $s->problema->problema_nome }}</td>
                        <td>{{ $s->servico_chamado_nome }}</td>
                        <td class="text-right">
                            <a href="{{ route('servicos.edit', $s) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('servicos.destroy', $s) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Deseja excluir?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum serviço cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $servicos->links() }}
    </div>
</div>
@stop