@extends('adminlte::page')

@section('title', 'Equipe')
@section('content_header')
    <h1>Equipe do Departamento</h1>
@stop

@section('content')
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->usuario_nome }}</td>
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
                                @if($usuario->status_id == 1)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Inativo</span>
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
