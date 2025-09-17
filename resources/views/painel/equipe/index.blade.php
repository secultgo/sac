@extends('adminlte::page')

@section('title', 'Equipe')
@section('content_header')
    <h1>Equipe do Departamento</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('custom-colors.css') }}">
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Membros da Equipe</h3>
    </div>
    <div class="card-body">
        @if($usuarios->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Usuário</th>
                            <th>Nível</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                            @switch($usuario->usuario_cor_id)
                                @case(1)<span class="badge badge-primary">{{ $usuario->usuario_nome }}</span>@break
                                @case(2)<span class="badge badge-danger">{{ $usuario->usuario_nome }}</span>@break
                                @case(3)<span class="badge badge-success">{{ $usuario->usuario_nome }}</span>@break
                                @case(4)<span class="badge badge-warning">{{ $usuario->usuario_nome }}</span>@break
                                @case(5)<span class="badge badge-purple">{{ $usuario->usuario_nome }}</span>@break
                                @case(6)<span class="badge badge-orange">{{ $usuario->usuario_nome }}</span>@break
                                @case(7)<span class="badge badge-pink">{{ $usuario->usuario_nome }}</span>@break
                                @case(8)<span class="badge badge-brown">{{ $usuario->usuario_nome }}</span>@break
                                @case(9)<span class="badge badge-secondary">{{ $usuario->usuario_nome }}</span>@break
                                @case(10)<span class="badge badge-dark">{{ $usuario->usuario_nome }}</span>@break
                                @case(11)<span class="badge badge-light">{{ $usuario->usuario_nome }}</span>@break
                                @case(12)<span class="badge badge-info">{{ $usuario->usuario_nome }}</span>@break
                                @case(13)<span class="badge badge-primary">{{ $usuario->usuario_nome }}</span>@break
                                @case(14)<span class="badge badge-success">{{ $usuario->usuario_nome }}</span>@break
                                @case(15)<span class="badge badge-success">{{ $usuario->usuario_nome }}</span>@break
                                @case(16)<span class="badge badge-warning">{{ $usuario->usuario_nome }}</span>@break
                                @case(17)<span class="badge badge-secondary">{{ $usuario->usuario_nome }}</span>@break
                                @case(18)<span class="badge badge-light">{{ $usuario->usuario_nome }}</span>@break
                                @case(19)<span class="badge badge-info">{{ $usuario->usuario_nome }}</span>@break
                                @case(20)<span class="badge badge-purple">{{ $usuario->usuario_nome }}</span>@break
                                @default<span class="badge badge-secondary">{{ $usuario->usuario_nome }}</span>
                            @endswitch
                            </td>
                            <td>{{ $usuario->usuario_email }}</td>
                            <td>{{ $usuario->usuario_usuario }}</td>
                            <td>
                                @foreach($usuario->nivelUsuarios as $nivelUsuario)
                                    @switch($nivelUsuario->nivel_id)
                                        @case(1)
                                            <span class="badge badge-danger">Super Usuário</span>
                                            @break
                                        @case(2)
                                            <span class="badge badge-warning">Gestor</span>
                                            @break
                                        @case(3)
                                            <span class="badge badge-info">Atendente</span>
                                            @break
                                        @case(4)
                                            <span class="badge badge-secondary">Usuário</span>
                                            @break
                                        @default
                                            <span class="badge badge-dark">Nível {{ $nivelUsuario->nivel_id }}</span>
                                    @endswitch
                                @endforeach
                            </td>
                            <td>
                                @if($usuario->usuario_id !== auth()->user()->usuario_id)
                                    <a href="{{ route('usuarios.edit_nivel', $usuario->usuario_id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Alterar Nível">
                                        <i class="fas fa-user-cog"></i> Nível
                                    </a>
                                    <a href="{{ route('usuarios.edit_cor', $usuario->usuario_id) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Alterar Cor">
                                        <i class="fas fa-user-cog"></i> Cor do Usuário
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <h4><i class="fas fa-info-circle"></i> Nenhum membro encontrado</h4>
                <p>Não há membros da equipe cadastrados neste departamento.</p>
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
<style>
.table td {
    vertical-align: middle;
}
</style>
@stop
