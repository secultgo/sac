@extends('adminlte::page')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Meus Atendimentos')
@section('content_header')
    <h1>Meus Atendimentos</h1>
@stop

@section('content')
<style>
.descricao-limitada {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3em;
  max-height: 3.5em; /* 1.3em * 2 linhas */
  max-width: 400px;  /* largura fixa */
  word-break: break-word; /* para evitar overflow */
}
.bg-orange {
    background-color: #FF851B !important; /* laranja vibrante */
    color: white !important; /* texto branco */
}

.bg-orange .inner,
.bg-orange h3,
.bg-orange p {
    color: white !important; /* garantir texto branco */
}

.bg-orange .small-box-footer {
    color: white !important; /* texto do footer branco */
}
</style>

<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $contadores['atendimento'] + $contadores['fechados'] + $contadores['pendentes'] + $contadores['resolvidos'] + $contadores['aguardando_usuario'] }}</h3>
                <p>Total de Atendimentos</p>
            </div>
            <div class="icon"><i class="fas fa-headset"></i></div>
            <a href="{{ route('meus-atendimentos.index') }}" class="small-box-footer">
                Ver todos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $contadores['atendimento'] }}</h3>
                <p>Em Atendimento</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="{{ route('meus-atendimentos.index', ['status' => 2]) }}" class="small-box-footer">
                Ver em atendimento <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $contadores['aguardando_usuario'] }}</h3>
                <p>Aguardando Usuário</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="{{ route('meus-atendimentos.index', ['status' => 6]) }}" class="small-box-footer">
                Ver aguardando usuário <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $contadores['pendentes'] }}</h3>
                <p>Atendimentos Pendentes</p>
            </div>
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            <a href="{{ route('meus-atendimentos.index', ['status' => 4]) }}" class="small-box-footer">
                Ver pendentes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <!-- Tabela de Chamados -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center flex-wrap">
        <h3 class="card-title mb-2 mb-md-0">Meus Atendimentos</h3>

        <div class="d-flex flex-wrap justify-content-start">
            <a href="{{ route('meus-atendimentos.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 mr-2 mb-2 {{ !request('status') ? 'active' : '' }}">
                Todos
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 2]) }}" class="btn btn-sm btn-warning rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 2 ? 'active' : '' }}">
                Atendimento <span class="badge badge-light ml-1">{{ $contadores['atendimento'] }}</span>
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 6]) }}" class="btn btn-sm btn-secondary rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 6 ? 'active' : '' }}">
                Aguardando Usuário <span class="badge badge-light ml-1">{{ $contadores['aguardando_usuario'] }}</span>
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 4]) }}" class="btn btn-sm bg-orange text-white rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 4 ? 'active' : '' }}">
                Pendentes <span class="badge badge-light ml-1">{{ $contadores['pendentes'] }}</span>
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 5]) }}" class="btn btn-sm btn-info rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 5 ? 'active' : '' }}">
                Resolvidos <span class="badge badge-light ml-1">{{ $contadores['resolvidos'] }}</span>
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 3]) }}" class="btn btn-sm btn-success rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 3 ? 'active' : '' }}">
                Fechados <span class="badge badge-light ml-1">{{ $contadores['fechados'] }}</span>
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if($chamados->count() > 0)
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Solicitante</th>
                        <th>Departamento</th>
                        <th>Local</th>
                        <th>Problema</th>
                        <th>Data de Criação</th>
                        <th>Data Atendimento</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chamados as $chamado)
                    <tr>
                        <td>{{ $chamado->chamado_id }}</td>
                        <td class="descricao-limitada">
                            {{ $chamado->chamado_descricao }}
                        </td>
                        <td>{{ $chamado->usuario->usuario_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->departamento->departamento_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->local->local_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->problema->problema_nome ?? 'N/A' }}</td>
                        <td>
                            @if($chamado->chamado_abertura)
                                {{ is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : $chamado->chamado_abertura->format('d/m/Y H:i:s') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($chamado->chamado_atendimento)
                                {{ is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : $chamado->chamado_atendimento->format('d/m/Y H:i:s') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @switch($chamado->status_chamado_id)
                                @case(1)
                                    <span class="badge badge-danger">Aberto</span>
                                    @break
                                @case(2)
                                    <span class="badge badge-warning">Atendimento</span>
                                    @break
                                @case(3)
                                    <span class="badge badge-success">Fechado</span>
                                    @break
                                @case(4)
                                    <span class="badge bg-orange">Pendente</span>
                                    @break
                                @case(5)
                                    <span class="badge badge-info">Resolvido</span>
                                    @break
                                @case(6)
                                    <span class="badge badge-secondary">Aguardando Usuário</span>
                                    @break
                                @default
                                    <span class="badge badge-dark">Status {{ $chamado->status_chamado_id }}</span>
                            @endswitch
                        </td>
                        <td class="text-right">
                            <div class="d-flex flex-wrap justify-content-end">
                                <!-- Botão Ver (sempre visível) -->
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-primary mr-1 mb-1" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Iniciar Atendimento - apenas para chamados ABERTOS -->
                                @if($chamado->status_chamado_id == 1)
                                <form action="{{ route('chamados.iniciar', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning mr-1 mb-1" title="Iniciar Atendimento">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- Atender Chamado - apenas para chamados PENDENTES -->
                                @if($chamado->status_chamado_id == 4)
                                <form action="{{ route('chamados.atender', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning mr-1 mb-1" title="Atender Chamado">
                                        <i class="fas fa-user-cog"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- Adicionar Comentário - para chamados que não estão fechados, abertos ou resolvidos -->
                                @if(!in_array($chamado->status_chamado_id, [3, 1, 5]) && $chamado->status_chamado_id != 6)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-secondary mr-1 mb-1" title="Adicionar Comentário">
                                    <i class="fas fa-comment-dots"></i>
                                </a>
                                @endif

                                <!-- Colocar em Pendência - apenas para chamados em ATENDIMENTO (2) -->
                                @if($chamado->status_chamado_id == 2)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-info mr-1 mb-1" title="Colocar em Pendência">
                                    <i class="fas fa-hourglass-half"></i>
                                </a>
                                @endif

                                <!-- Devolver ao Usuário - para chamados em ATENDIMENTO (2) ou PENDENTE (4) -->
                                @if(in_array($chamado->status_chamado_id, [2, 4]))
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-warning mr-1 mb-1" title="Devolver ao Usuário">
                                    <i class="fas fa-undo"></i>
                                </a>
                                @endif

                                <!-- Resolver Chamado - para ATENDIMENTO (2), PENDENTE (4) e AGUARDANDO_USUARIO (6) -->
                                @if(in_array($chamado->status_chamado_id, [2, 4, 6]))
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-success mr-1 mb-1" title="Resolver Chamado">
                                    <i class="fas fa-check"></i>
                                </a>
                                @endif

                                <!-- Transferir Departamento - não disponível para FECHADO (3) e RESOLVIDO (5) -->
                                @if(!in_array($chamado->status_chamado_id, [3, 5]))
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-dark mr-1 mb-1" title="Transferir Departamento">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                @endif

                                <!-- Alterar Responsável - não disponível para FECHADO (3), ABERTO (1) e RESOLVIDO (5) -->
                                @if(!in_array($chamado->status_chamado_id, [3, 1, 5]))
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-outline-primary mr-1 mb-1" title="Alterar Responsável">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="card-body text-center">
                <div class="alert alert-info">
                    <h4><i class="fas fa-info-circle"></i> Nenhum atendimento encontrado</h4>
                    <p>
                        @switch($statusFiltro)
                            @case(2)
                                Você não possui chamados em atendimento no momento.
                                @break
                            @case(3)
                                Você não possui chamados fechados no momento.
                                @break
                            @case(4)
                                Você não possui chamados pendentes no momento.
                                @break
                            @case(5)
                                Você não possui chamados resolvidos no momento.
                                @break
                            @case(6)
                                Você não possui chamados aguardando usuário no momento.
                                @break
                            @default
                                Você não possui chamados em atendimento no momento.
                        @endswitch
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
.btn.active {
    box-shadow: 0 0 0 2px rgba(0,123,255,.5);
    transform: scale(1.05);
}
</style>
@stop

@section('js')
<script>
console.log('Meus Atendimentos carregado');
</script>
@stop
