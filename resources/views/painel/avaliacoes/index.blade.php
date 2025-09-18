@extends('adminlte::page')

@section('title', 'Avaliações')
@section('content_header')
    <h1>Painel de Avaliações</h1>
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i> Avaliações dos Atendimentos
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-star"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total de Avaliações</span>
                                <span class="info-box-number">{{ $totalAvaliacoes ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-meh"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avaliações Regulares</span>
                                <span class="info-box-number">{{ $totalRegulares ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-frown"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avaliações Ruins</span>
                                <span class="info-box-number">{{ $totalRuins ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center flex-wrap">
    <h3 class="card-title mb-2 mb-md-0">Chamados</h3>
        <a href="{{ route('avaliacoes.cientes') }}" class="btn btn-sm btn-primary rounded-pill px-3 mr-2 mb-2">
            Cientes <span class="badge badge-light ml-1">{{ $totalCientes ?? 0 }}</span>
        </a>      
</div>

<div class="card mt-3">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Chamados Avaliados (Ruim/Regular) pendentes de ciência
        </h3>
    </div>
    <div class="card-body">
        @if(isset($chamadosAvaliados) && $chamadosAvaliados->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Solicitante</th>
                            <th>Lotação</th>
                            <th>Atendente</th>
                            <th>Data de Criação</th>
                            <th>Data Atendimento</th>
                            <th>Avaliação</th>
                            <th>Motivo da Avaliação</th>
                            <th>Ciência</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chamadosAvaliados as $chamado)
                        <tr>
                            <td>{{ $chamado->chamado_id }}</td>
                            <td>{{ Str::limit($chamado->chamado_descricao, 50) }}</td>
                            <td>{{ $chamado->usuario->usuario_nome ?? '-' }}</td>
                            <td>{{ $chamado->departamentoLotacao->departamento_nome ?? '-' }}</td>
                            <td>{{ $chamado->responsavel->usuario_nome ?? '-' }}</td>
                            <td>{{ $chamado->chamado_abertura ? $chamado->chamado_abertura->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $chamado->chamado_atendimento ? $chamado->chamado_atendimento->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span class="badge {{ $chamado->avaliacao_chamado_id == 4 ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $chamado->avaliacaoChamado->avaliacao_chamado_nome ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $comentarioAvaliacao = $chamado->comentarios->first();
                                    $motivoAvaliacao = $comentarioAvaliacao ? str_replace('Avaliação do usuário: ', '', $comentarioAvaliacao->comentario_chamado_comentario) : '';
                                @endphp
                                @if($motivoAvaliacao)
                                    <span class="text-muted" title="{{ $motivoAvaliacao }}">
                                        {{ Str::limit($motivoAvaliacao, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!$chamado->chamado_ciente_gestor)
                                    <span class="badge badge-secondary">Pendente</span>
                                @else
                                    <span class="badge badge-success">Ciente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="fas fa-search"></i>
                                </a>
                                @if(!$chamado->chamado_ciente_gestor)
                                <form action="{{ route('avaliacoes.ciente', $chamado->chamado_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Dar ciência">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Sem chamados avaliados (Regular/Ruim) pendentes de ciência.
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
<style>
.info-box {
    margin-bottom: 20px;
}

.card-header.bg-danger {
    background-color: #dc3545 !important;
    color: white;
}

.card-header.bg-warning {
    background-color: #ffc107 !important;
    color: #212529;
}

.card-header.bg-primary {
    background-color: #007bff !important;
    color: white;
}

.table-sm th,
.table-sm td {
    padding: 0.5rem;
    font-size: 0.9rem;
}

.text-muted {
    font-size: 0.8rem;
}
</style>
@stop
