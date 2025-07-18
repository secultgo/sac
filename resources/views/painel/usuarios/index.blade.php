@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>Usuários</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Usuário
            </a>
            <a href="{{ route('usuarios.importar.ldap') }}" class="btn btn-dark">
                LDAP
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Departamento</th>
                        <th>Email</th>
                        <th>Nível</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td>{{ $u->usuario_id }}</td>
                            <td>{{ $u->usuario_nome }}</td>
                            <td>
                                {{ $departamentos->firstWhere('departamento_id', $u->departamento_id)->departamento_nome ?? 'Sem departamento' }}
                            </td>
                            <td>{{ $u->usuario_email }}</td>
                            <td>
                                @php
                                    $nivelUser = $nivel_usuarios->firstWhere('usuario_id', $u->usuario_id);
                                    $nivelNome = 'Sem nível';
                                    if ($nivelUser) {
                                        $nivel = $niveis->firstWhere('nivel_id', $nivelUser->nivel_id);
                                        $nivelNome = $nivel->nivel_nome ?? 'Sem nível';
                                    }
                                @endphp
                                {{ $nivelNome }}
                            </td>
                            <td class="text-right">
                                @if($u->status_id == 1)
                                    <form action="{{ route('usuarios.desativar', $u->usuario_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-default" title="Desativar">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('usuarios.ativar', $u->usuario_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-default" title="Ativar">
                                            <i class="fas fa-toggle-off"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('usuarios.edit_nivel', $u->usuario_id) }}" class="btn btn-sm btn-secondary" title="Editar Nível">
                                    <i class="fas fa-user-shield"></i>
                                </a>

                                <a href="{{ route('usuarios.edit', $u->usuario_id) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('usuarios.destroy', $u->usuario_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum usuário cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($usuarios, 'links'))
            <div class="card-footer">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
@stop
