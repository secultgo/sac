@extends('adminlte::page')

@section('title', 'Locais')

@section('content_header')
    <h1>Locais</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('locais.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locais as $l)
                    <tr>
                        <td>{{ $l->local_id }}</td>
                        <td>{{ $l->local_nome }}</td>
                        <td>{{ $l->local_sigla }}</td>
                        <td>{{ $l->local_endereco }}</td>
                        <td>{{ $l->local_telefone }}</td>
                        <td class="text-right">
                            <a href="{{ route('locais.edit', $l) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('locais.destroy', $l) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Deseja excluir este local?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum local cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $locais->links() }}
    </div>
</div>
@stop