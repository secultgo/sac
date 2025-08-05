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
                    <button class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#modalComentario">
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
                            {{ is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : $chamado->chamado_abertura->format('d/m/Y H:i:s') }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if($chamado->chamado_atendimento)
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Atendimento em:</strong>
                    </div>
                    <div class="col-6">
                        {{ is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : $chamado->chamado_atendimento->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                @endif
                @if($chamado->chamado_resolvido)
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Resolvido em:</strong>
                    </div>
                    <div class="col-6">
                        {{ is_string($chamado->chamado_resolvido) ? \Carbon\Carbon::parse($chamado->chamado_resolvido)->format('d/m/Y H:i:s') : $chamado->chamado_resolvido->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                @endif
                @if($chamado->chamado_fechado)
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Fechado em:</strong>
                    </div>
                    <div class="col-6">
                        {{ is_string($chamado->chamado_fechado) ? \Carbon\Carbon::parse($chamado->chamado_fechado)->format('d/m/Y H:i:s') : $chamado->chamado_fechado->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                @endif
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
                        <span class="badge badge-info">{{ $tempoFormatado }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Comentário -->
<div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-labelledby="modalComentarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalComentarioLabel">
                        <i class="fas fa-comment-dots"></i> Adicionar Comentário
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comentario">Comentário <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="4" placeholder="Digite seu comentário..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="anexo">Anexo (Opcional)</label>
                        <input type="file" class="form-control-file" id="anexo" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <small class="form-text text-muted">
                            Formatos aceitos: JPG, PNG, PDF, DOC, DOCX, TXT. Tamanho máximo: 5MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Adicionar Comentário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
$(document).ready(function() {
    // Função para mostrar notificações com fallback
    function showNotification(message, type) {
        // Tenta usar toastr primeiro
        if (typeof toastr !== 'undefined') {
            if (type === 'success') {
                toastr.success(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        } 
        // Fallback para SweetAlert2 se disponível
        else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Sucesso!' : 'Erro!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }
        // Fallback final para alert nativo
        else {
            alert(message);
        }
    }
    
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            showNotification('{{ $error }}', 'error');
        @endforeach
    @endif
    
    // Fechar modal após sucesso
    @if(session('success'))
        $('#modalComentario').modal('hide');
        // Limpar o formulário
        $('#modalComentario form')[0].reset();
    @endif
});
</script>
@stop
