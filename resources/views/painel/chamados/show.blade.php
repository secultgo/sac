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
@include('painel.chamados.partials.styles')

<div class="row">
    <div class="col-md-8">
        @include('painel.chamados.partials.detalhes', ['chamado' => $chamado])

        @include('painel.chamados.partials.timeline', ['chamado' => $chamado])
    </div>

    <div class="col-md-4">
        <!-- Ações Disponíveis -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs"></i> Ações Disponíveis
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($chamado->status_chamado_id != 3)
                    <button class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-comment-dots"></i> Adicionar Comentário
                    </button>
                    @endif
                    
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

                    @if($chamado->status_chamado_id != 3)
                    <button class="btn btn-dark btn-block mb-2">
                        <i class="fas fa-exchange-alt"></i> Transferir Departamento
                    </button>

                    <button class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-user-edit"></i> Alterar Responsável
                    </button>
                    @endif

                    @if($chamado->status_chamado_id == 3)
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Chamado fechado - Nenhuma ação disponível
                    </div>
                    @endif
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
                            
                            // Calcula a diferença total em segundos
                            $diff = $inicio->diff($fim);
                            
                            // Monta o texto formatado
                            $tempoFormatado = '';
                            if ($diff->d > 0) {
                                $tempoFormatado .= $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' ';
                            }
                            if ($diff->h > 0) {
                                $tempoFormatado .= $diff->h . 'h ';
                            }
                            if ($diff->i > 0) {
                                $tempoFormatado .= $diff->i . 'min ';
                            }
                            if ($diff->s > 0 || empty($tempoFormatado)) {
                                $tempoFormatado .= $diff->s . 's';
                            }
                            
                            $tempoFormatado = trim($tempoFormatado);
                        @endphp
                        {{ $tempoFormatado }}
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
@stop
