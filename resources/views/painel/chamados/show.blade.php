@extends('adminlte::page')

@php
use App\Models\StatusChamado;
use App\Models\Departamento;
use App\Models\User;
use App\Models\AvaliacaoChamado;
use Illuminate\Support\Facades\Auth;
@endphp

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
                    @if($chamado->status_chamado_id == StatusChamado::ABERTO)
                    <form action="{{ route('chamados.iniciar', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-play"></i> Iniciar Atendimento
                        </button>
                    </form>
                    
                    @can('gestor')
                    <button type="button" class="btn btn-info btn-block mb-2" 
                            data-toggle="modal" 
                            data-target="#modalAtribuirResponsavel" 
                            data-chamado-id="{{ $chamado->chamado_id }}"
                            data-chamado-descricao="{{ Str::limit($chamado->chamado_descricao, 50) }}"
                            data-departamento-id="{{ $chamado->departamento_id }}">
                        <i class="fas fa-user-plus"></i> Atribuir Responsável
                    </button>
                    @endcan
                    @endif

                    @if($chamado->status_chamado_id == StatusChamado::PENDENTE)
                    <form action="{{ route('chamados.atender', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-user-cog"></i> Atender Chamado
                        </button>
                    </form>
                    @endif

                    @if($chamado->status_chamado_id == StatusChamado::AGUARDANDO_USUARIO && Auth::user()->usuario_id == $chamado->usuario_id)
                    <button class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#modalResponderUsuario">
                        <i class="fas fa-reply"></i> Responder Solicitação
                    </button>
                    @elseif(!in_array($chamado->status_chamado_id, [StatusChamado::FECHADO, StatusChamado::ABERTO, StatusChamado::RESOLVIDO]))
                    <button class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#modalComentario">
                        <i class="fas fa-comment-dots"></i> Adicionar Comentário
                    </button>
                    @endif

                    @if($chamado->status_chamado_id == StatusChamado::REABERTO && Auth::user()->departamento_id == $chamado->departamento_id)
                    <form action="{{ route('chamados.iniciar', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-play"></i> Reiniciar Atendimento
                        </button>
                    </form>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::REABERTO]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#modalPendencia">
                        <i class="fas fa-hourglass-half"></i> Colocar em Pendência
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::REABERTO]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-secondary btn-block mb-2" data-toggle="modal" data-target="#modalDevolver">
                        <i class="fas fa-undo"></i> Devolver ao Usuário
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::AGUARDANDO_USUARIO, StatusChamado::REABERTO]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#modalResolver">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </button>
                    @endif

                    @if(!in_array($chamado->status_chamado_id, [StatusChamado::FECHADO, StatusChamado::RESOLVIDO]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-dark btn-block mb-2" data-toggle="modal" data-target="#modalTransferir">
                        <i class="fas fa-exchange-alt"></i> Transferir Departamento
                    </button>
                    @endif

                    @if(!in_array($chamado->status_chamado_id, [StatusChamado::FECHADO, StatusChamado::ABERTO, StatusChamado::RESOLVIDO]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-outline-primary btn-block mb-2" data-toggle="modal" data-target="#modalAlterarResponsavel">
                        <i class="fas fa-user-edit"></i> Alterar Responsável
                    </button>
                    @endif

                    @if($chamado->status_chamado_id == StatusChamado::RESOLVIDO && Auth::user()->usuario_id == $chamado->usuario_id)
                    <button class="btn btn-warning btn-block mb-2" data-toggle="modal" data-target="#modalAvaliarChamado">
                        <i class="fas fa-star"></i> Avaliar Atendimento
                    </button>
                    <button class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#modalReabrirChamado">
                        <i class="fas fa-redo"></i> Reabrir Chamado
                    </button>
                    @endif

                    @if($chamado->status_chamado_id == StatusChamado::FECHADO)
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
                        <strong>Fechado em:</strong>
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
                        <strong>Avaliado em:</strong>
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

{{-- Incluir todos os modais como partials --}}
@include('painel.chamados.modals.comentario')
@include('painel.chamados.modals.pendencia')
@include('painel.chamados.modals.transferir')
@include('painel.chamados.modals.devolver')
@include('painel.chamados.modals.resolver')
@include('painel.chamados.modals.alterar-responsavel')
@include('painel.chamados.modals.responder-usuario')
@include('painel.chamados.modals.avaliar')
@include('painel.chamados.modals.reabrir')

<!-- Modal para Atribuir Responsável -->
<div class="modal fade" id="modalAtribuirResponsavel" tabindex="-1" role="dialog" aria-labelledby="modalAtribuirResponsavelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalAtribuirResponsavelLabel">
                    <i class="fas fa-user-plus"></i> Atribuir Responsável
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAtribuirResponsavel" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Chamado:</strong> <span id="chamadoDescricao"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="responsavel_id">Selecione o Responsável:</label>
                        <select class="form-control" id="responsavel_id" name="responsavel_id" required>
                            <option value="">Selecione um responsável...</option>
                        </select>
                        <small class="form-text text-muted">
                            Apenas usuários do mesmo departamento que podem atender chamados são listados.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-user-plus"></i> Atribuir Responsável
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@include('painel.chamados.modals._styles')
@stop

@section('js')
@include('painel.chamados.modals._scripts')

<script>
// Modal para Atribuir Responsável
$('#modalAtribuirResponsavel').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const chamadoId = button.data('chamado-id');
    const chamadoDescricao = button.data('chamado-descricao');
    const departamentoId = button.data('departamento-id');
    
    const modal = $(this);
    modal.find('#chamadoDescricao').text(chamadoDescricao);
    modal.find('#formAtribuirResponsavel').attr('action', `/painel/chamados/${chamadoId}/atribuir-responsavel`);
    
    // Carregar usuários do departamento que podem atender
    const select = modal.find('#responsavel_id');
    select.empty().append('<option value="">Carregando...</option>');
    
    $.ajax({
        url: `/painel/chamados/${chamadoId}/usuarios-departamento`,
        method: 'GET',
        success: function(data) {
            select.empty().append('<option value="">Selecione um responsável...</option>');
            data.forEach(function(usuario) {
                select.append(`<option value="${usuario.usuario_id}">${usuario.usuario_nome}</option>`);
            });
        },
        error: function() {
            select.empty().append('<option value="">Erro ao carregar usuários</option>');
            Swal.fire('Erro', 'Não foi possível carregar os usuários do departamento.', 'error');
        }
    });
});

// Limpar modal ao fechar
$('#modalAtribuirResponsavel').on('hidden.bs.modal', function () {
    $(this).find('#responsavel_id').empty();
});
</script>
@stop
