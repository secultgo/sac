@extends('adminlte::page')

@section('title', 'Chamado #' . $chamado->chamado_id)
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Chamado #{{ $chamado->chamado_id }}</h1>
        <a href="{{ route('painel.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
<style>
.info-box-custom {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
}

.info-box-custom h5 {
    margin-bottom: 10px;
    color: #007bff;
    font-weight: bold;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #007bff;
}

.attachment-item {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    background: #f8f9fa;
}

.status-badge {
    font-size: 1.1em;
    padding: 8px 12px;
}
</style>

<div class="row">
    <div class="col-md-8">
        <!-- Informações Principais -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt"></i> Detalhes do Chamado
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-user"></i> Solicitante</h5>
                            <p><strong>{{ $chamado->usuario->usuario_nome ?? 'N/A' }}</strong></p>
                            <p class="text-muted mb-0">{{ $chamado->usuario->usuario_email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-user-cog"></i> Responsável</h5>
                            <p><strong>{{ $chamado->responsavel->usuario_nome ?? 'Não atribuído' }}</strong></p>
                            <p class="text-muted mb-0">{{ $chamado->responsavel->usuario_email ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-building"></i> Departamento</h5>
                            <p><strong>{{ $chamado->departamento->departamento_nome ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-map-marker-alt"></i> Local</h5>
                            <p><strong>{{ $chamado->local->local_nome ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-tools"></i> Problema</h5>
                            <p><strong>{{ $chamado->problema->problema_nome ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box-custom">
                            <h5><i class="fas fa-cogs"></i> Serviço</h5>
                            <p><strong>{{ $chamado->servicoChamado->servico_chamado_nome ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                </div>

                @if($chamado->chamado_ip)
                <div class="info-box-custom">
                    <h5><i class="fas fa-network-wired"></i> IP do Solicitante</h5>
                    <p><code>{{ $chamado->chamado_ip }}</code></p>
                </div>
                @endif

                <div class="info-box-custom">
                    <h5><i class="fas fa-comment-alt"></i> Descrição do Problema</h5>
                    <p>{{ $chamado->chamado_descricao }}</p>
                </div>

                @if($chamado->chamado_anexo)
                <div class="info-box-custom">
                    <h5><i class="fas fa-paperclip"></i> Anexo</h5>
                    <div class="attachment-item">
                        <i class="fas fa-file"></i>
                        <a href="{{ asset('uploads/chamado/' . $chamado->chamado_anexo) }}" target="_blank">
                            {{ $chamado->chamado_anexo }}
                        </a>
                        <span class="badge badge-secondary ml-2">
                            <i class="fas fa-download"></i> Download
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Timeline do Chamado -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Histórico do Chamado
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($chamado->chamado_abertura)
                    <div class="timeline-item">
                        <h6><i class="fas fa-plus-circle text-danger"></i> Chamado Aberto</h6>
                        <p class="text-muted mb-0">
                            {{ is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : $chamado->chamado_abertura->format('d/m/Y H:i:s') }}
                        </p>
                        <small class="text-muted">Por: {{ $chamado->usuario->usuario_nome ?? 'N/A' }}</small>
                    </div>
                    @endif

                    @if($chamado->chamado_atendimento)
                    <div class="timeline-item">
                        <h6><i class="fas fa-play-circle text-warning"></i> Atendimento Iniciado</h6>
                        <p class="text-muted mb-0">
                            {{ is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : $chamado->chamado_atendimento->format('d/m/Y H:i:s') }}
                        </p>
                        <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
                    </div>
                    @endif

                    @if($chamado->chamado_pendente)
                    <div class="timeline-item">
                        <h6><i class="fas fa-pause-circle text-orange"></i> Chamado Pendente</h6>
                        <p class="text-muted mb-0">
                            {{ is_string($chamado->chamado_pendente) ? \Carbon\Carbon::parse($chamado->chamado_pendente)->format('d/m/Y H:i:s') : $chamado->chamado_pendente->format('d/m/Y H:i:s') }}
                        </p>
                        @if($chamado->chamado_pendencia)
                        <small class="text-muted">Motivo: {{ $chamado->chamado_pendencia }}</small>
                        @endif
                    </div>
                    @endif

                    @if($chamado->chamado_resolvido)
                    <div class="timeline-item">
                        <h6><i class="fas fa-check-circle text-info"></i> Chamado Resolvido</h6>
                        <p class="text-muted mb-0">
                            {{ is_string($chamado->chamado_resolvido) ? \Carbon\Carbon::parse($chamado->chamado_resolvido)->format('d/m/Y H:i:s') : $chamado->chamado_resolvido->format('d/m/Y H:i:s') }}
                        </p>
                        <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
                    </div>
                    @endif

                    @if($chamado->chamado_fechado)
                    <div class="timeline-item">
                        <h6><i class="fas fa-times-circle text-success"></i> Chamado Fechado</h6>
                        <p class="text-muted mb-0">
                            {{ is_string($chamado->chamado_fechado) ? \Carbon\Carbon::parse($chamado->chamado_fechado)->format('d/m/Y H:i:s') : $chamado->chamado_fechado->format('d/m/Y H:i:s') }}
                        </p>
                        <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status e Ações -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Status Atual
                </h3>
            </div>
            <div class="card-body text-center">
                @switch($chamado->status_chamado_id)
                    @case(1)
                        <span class="badge badge-danger status-badge">
                            <i class="fas fa-folder-open"></i> Aberto
                        </span>
                        @break
                    @case(2)
                        <span class="badge badge-warning status-badge">
                            <i class="fas fa-user-clock"></i> Em Atendimento
                        </span>
                        @break
                    @case(3)
                        <span class="badge badge-success status-badge">
                            <i class="fas fa-check"></i> Fechado
                        </span>
                        @break
                    @case(4)
                        <span class="badge bg-orange status-badge">
                            <i class="fas fa-hourglass-half"></i> Pendente
                        </span>
                        @break
                    @case(5)
                        <span class="badge badge-info status-badge">
                            <i class="fas fa-check-circle"></i> Resolvido
                        </span>
                        @break
                    @case(6)
                        <span class="badge badge-secondary status-badge">
                            <i class="fas fa-clock"></i> Aguardando Usuário
                        </span>
                        @break
                    @default
                        <span class="badge badge-dark status-badge">
                            Status {{ $chamado->status_chamado_id }}
                        </span>
                @endswitch
            </div>
        </div>

        <!-- Ações Disponíveis -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs"></i> Ações Disponíveis
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-comment-dots"></i> Adicionar Comentário
                    </button>
                    
                    @if($chamado->status_chamado_id == 1)
                    <button class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-play"></i> Iniciar Atendimento
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [2, 4]))
                    <button class="btn btn-info btn-block mb-2">
                        <i class="fas fa-hourglass-half"></i> Colocar em Pendência
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [2, 4]))
                    <button class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-undo"></i> Devolver ao Usuário
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [2, 4]))
                    <button class="btn btn-success btn-block mb-2">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </button>
                    @endif

                    <button class="btn btn-dark btn-block mb-2">
                        <i class="fas fa-exchange-alt"></i> Transferir Departamento
                    </button>

                    <button class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-user-edit"></i> Alterar Responsável
                    </button>
                </div>
            </div>
        </div>

        <!-- Informações Técnicas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Informações Técnicas
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-6">
                        #{{ $chamado->chamado_id }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Criado em:</strong>
                    </div>
                    <div class="col-6">
                        @if($chamado->chamado_abertura)
                            {{ is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y') : $chamado->chamado_abertura->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if($chamado->chamado_atendimento)
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Tempo em atendimento:</strong>
                    </div>
                    <div class="col-6">
                        @php
                            $inicio = is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento) : $chamado->chamado_atendimento;
                            $fim = $chamado->chamado_fechado ? 
                                (is_string($chamado->chamado_fechado) ? \Carbon\Carbon::parse($chamado->chamado_fechado) : $chamado->chamado_fechado) : 
                                now();
                            $tempo = $inicio->diffInHours($fim);
                        @endphp
                        {{ $tempo }}h
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
.bg-orange {
    background-color: #FF851B !important;
    color: white !important;
}

.text-orange {
    color: #FF851B !important;
}
</style>
@stop
