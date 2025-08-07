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

                    @if($chamado->status_chamado_id == StatusChamado::ATENDIMENTO && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#modalPendencia">
                        <i class="fas fa-hourglass-half"></i> Colocar em Pendência
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE]) && Auth::user()->departamento_id == $chamado->departamento_id)
                    <button class="btn btn-secondary btn-block mb-2" data-toggle="modal" data-target="#modalDevolver">
                        <i class="fas fa-undo"></i> Devolver ao Usuário
                    </button>
                    @endif

                    @if(in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::AGUARDANDO_USUARIO]) && Auth::user()->departamento_id == $chamado->departamento_id)
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
                        <strong>Não Avaliado em:</strong>
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

<!-- Modal para Colocar em Pendência -->
<div class="modal fade" id="modalPendencia" tabindex="-1" role="dialog" aria-labelledby="modalPendenciaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.pendencia', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPendenciaLabel">
                        <i class="fas fa-hourglass-half"></i> Colocar Chamado em Pendência
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> O chamado será colocado em pendência. Descreva o motivo da pendência.
                    </div>
                    <div class="form-group">
                        <label for="motivoPendencia">Motivo da Pendência <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoPendencia" name="motivo_pendencia" rows="4" placeholder="Descreva o motivo da pendência..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-hourglass-half"></i> Colocar em Pendência
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Transferir Departamento -->
<div class="modal fade" id="modalTransferir" tabindex="-1" role="dialog" aria-labelledby="modalTransferirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.transferir', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTransferirLabel">
                        <i class="fas fa-exchange-alt"></i> Transferir Chamado para Outro Departamento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Departamento atual:</strong> {{ $chamado->departamento->departamento_nome }}
                    </div>
                    <div class="form-group">
                        <label for="novoDepartamento">Novo Departamento <span class="text-danger">*</span></label>
                        <select class="form-control" id="novoDepartamento" name="novo_departamento_id" required>
                            <option value="">Selecione o departamento...</option>
                            @foreach(Departamento::where('departamento_id', '!=', $chamado->departamento_id)->where('departamento_chamado', 1)->orderBy('departamento_nome')->get() as $dept)
                                <option value="{{ $dept->departamento_id }}">{{ $dept->departamento_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivoTransferencia">Motivo da Transferência <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoTransferencia" name="motivo_transferencia" rows="4" placeholder="Descreva o motivo da transferência..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-exchange-alt"></i> Transferir Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Devolver ao Usuário -->
<div class="modal fade" id="modalDevolver" tabindex="-1" role="dialog" aria-labelledby="modalDevolverLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.devolver', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDevolverLabel">
                        <i class="fas fa-undo"></i> Devolver Chamado ao Usuário
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> O chamado será devolvido ao usuário e ficará aguardando resposta. Descreva o motivo da devolução.
                    </div>
                    <div class="form-group">
                        <label for="motivoDevolucao">Motivo da Devolução <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoDevolucao" name="motivo_devolucao" rows="4" placeholder="Descreva o motivo da devolução ao usuário..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo"></i> Devolver ao Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Resolver Chamado -->
<div class="modal fade" id="modalResolver" tabindex="-1" role="dialog" aria-labelledby="modalResolverLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.resolver', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResolverLabel">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Resolução:</strong> Descreva a solução aplicada para resolver o chamado.
                    </div>
                    <div class="form-group">
                        <label for="solucao">Solução Aplicada <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="solucao" name="solucao" rows="4" placeholder="Descreva detalhadamente a solução aplicada..." required></textarea>
                        <small class="form-text text-muted">
                            Esta informação será registrada no histórico do chamado e o status será alterado para "Não Avaliado".
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Alterar Responsável -->
<div class="modal fade" id="modalAlterarResponsavel" tabindex="-1" role="dialog" aria-labelledby="modalAlterarResponsavelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.alterar-responsavel', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlterarResponsavelLabel">
                        <i class="fas fa-user-edit"></i> Alterar Responsável do Chamado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Responsável atual:</strong> {{ $chamado->responsavel ? $chamado->responsavel->name : 'Nenhum responsável definido' }}
                    </div>
                    <div class="form-group">
                        <label for="novoResponsavel">Novo Responsável <span class="text-danger">*</span></label>
                        <select class="form-control" id="novoResponsavel" name="novo_responsavel_id" required>
                            <option value="">Selecione o responsável...</option>
                            @foreach(User::where('departamento_id', $chamado->departamento_id)->where('usuario_id', '!=', $chamado->responsavel_id)->where('status_id', 1)->orderBy('usuario_nome')->get() as $usuario)
                                <option value="{{ $usuario->usuario_id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivoAlteracao">Motivo da Alteração <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoAlteracao" name="motivo_alteracao" rows="4" placeholder="Descreva o motivo da alteração do responsável..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-edit"></i> Alterar Responsável
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Responder ao Chamado (Usuário) -->
<div class="modal fade" id="modalResponderUsuario" tabindex="-1" role="dialog" aria-labelledby="modalResponderUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResponderUsuarioLabel">
                        <i class="fas fa-reply"></i> Responder Solicitação
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Atenção:</strong> Sua resposta fará com que o chamado retorne automaticamente para atendimento.
                    </div>
                    <div class="form-group">
                        <label for="comentarioResposta">Sua Resposta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentarioResposta" name="comentario" rows="5" placeholder="Digite sua resposta ao técnico responsável..." required></textarea>
                        <small class="form-text text-muted">
                            Descreva detalhadamente sua resposta à solicitação do técnico.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="anexoResposta">Anexo (Opcional)</label>
                        <input type="file" class="form-control-file" id="anexoResposta" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <small class="form-text text-muted">
                            Formatos aceitos: JPG, PNG, PDF, DOC, DOCX, TXT. Tamanho máximo: 5MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-reply"></i> Enviar Resposta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Avaliar Chamado -->
<div class="modal fade" id="modalAvaliarChamado" tabindex="-1" role="dialog" aria-labelledby="modalAvaliarChamadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.avaliar', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAvaliarChamadoLabel">
                        <i class="fas fa-star"></i> Avaliar Atendimento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Seu chamado foi resolvido e está aguardando avaliação!</strong> Avalie o atendimento recebido.
                    </div>
                    
                    <div class="form-group">
                        <label for="avaliacao">Avaliação do Atendimento <span class="text-danger">*</span></label>
                        <div class="rating-container">
                            @foreach(AvaliacaoChamado::orderBy('avaliacao_chamado_id', 'asc')->get() as $avaliacao)
                            <div class="radio">
                                <label for="{{ $avaliacao->avaliacao_chamado_id }}">
                                    <input type="radio" name="avaliacao" id="{{ $avaliacao->avaliacao_chamado_id }}" value="{{ $avaliacao->avaliacao_chamado_id }}" required>
                                    @if($avaliacao->avaliacao_chamado_imagem)
                                        {!! $avaliacao->avaliacao_chamado_imagem !!}
                                    @endif
                                    <p>{{ $avaliacao->avaliacao_chamado_nome }}</p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comentarioAvaliacao">Comentário sobre o Atendimento <span class="opcional-text">(Opcional)</span></label>
                        <textarea class="form-control" id="comentarioAvaliacao" name="comentario_avaliacao" rows="4" placeholder="Deixe um comentário sobre o atendimento recebido..."></textarea>
                        <small class="form-text text-muted">
                            Seu feedback nos ajuda a melhorar nossos serviços.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-star"></i> Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
.rating-container .radio {
    margin-bottom: 15px;
}
.rating-container .radio label {
    cursor: pointer;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.2s;
    display: block;
    border: 2px solid #e9ecef;
    background-color: #f8f9fa;
}
.rating-container .radio label:hover {
    border-color: #ffc107;
    background-color: #fff3cd;
}
.rating-container .radio input[type="radio"]:checked + label,
.rating-container .radio input[type="radio"]:checked ~ label {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
    font-weight: bold;
}
.rating-container .radio input[type="radio"]:checked + label i,
.rating-container .radio input[type="radio"]:checked ~ label i {
    color: #ffffff !important;
}
.rating-container .radio input[type="radio"] {
    margin-right: 10px;
}
.rating-container i.fa-chamado {
    font-size: 24px;
    margin-right: 10px;
    transition: color 0.2s;
}
.rating-container img {
    width: 24px;
    height: 24px;
    margin-right: 10px;
}
.rating-container p {
    margin: 5px 0 0 0;
    font-size: 14px;
    display: inline;
}

/* Estilos para ícones de avaliação */
.fa-2 { font-size: 2em; }
.fa-3 { font-size: 4em; }
.fa-4 { font-size: 7em; }
.fa-5 { font-size: 12em; }
.fa-6 { font-size: 20em; }
.fa-chamado { font-size: 10.5em; }
</style>
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
    
    // Validação do comentário obrigatório para avaliações Regular (3) e Ruim (4)
    $('#modalAvaliarChamado form').on('submit', function(e) {
        var avaliacaoSelecionada = $('input[name="avaliacao"]:checked').val();
        var comentario = $('#comentarioAvaliacao').val().trim();
        
        // Se selecionou Regular (3) ou Ruim (4) e não preencheu comentário
        if ((avaliacaoSelecionada == '3' || avaliacaoSelecionada == '4') && comentario === '') {
            e.preventDefault();
            showNotification('Por favor, deixe um comentário explicando sua avaliação.', 'error');
            $('#comentarioAvaliacao').focus();
            return false;
        }
    });
    
    // Atualizar indicação visual do campo comentário baseado na avaliação
    $('input[name="avaliacao"]').on('change', function() {
        var avaliacaoSelecionada = $(this).val();
        var comentarioLabel = $('label[for="comentarioAvaliacao"]');
        var comentarioField = $('#comentarioAvaliacao');
        var opcionalText = $('.opcional-text');
        
        if (avaliacaoSelecionada == '3' || avaliacaoSelecionada == '4') {
            // Tornar obrigatório visualmente
            if (!comentarioLabel.find('.text-danger').length) {
                comentarioLabel.append(' <span class="text-danger">*</span>');
            }
            opcionalText.html('(Obrigatório)').addClass('text-danger');
            comentarioField.attr('placeholder', 'Por favor, explique sua avaliação (obrigatório)');
        } else {
            // Remover obrigatoriedade visual
            comentarioLabel.find('.text-danger').remove();
            opcionalText.html('(Opcional)').removeClass('text-danger');
            comentarioField.attr('placeholder', 'Deixe um comentário sobre o atendimento recebido...');
        }
    });
    
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
        $('#modalPendencia').modal('hide');
        $('#modalTransferir').modal('hide');
        $('#modalDevolver').modal('hide');
        $('#modalResolver').modal('hide');
        $('#modalAlterarResponsavel').modal('hide');
        $('#modalResponderUsuario').modal('hide');
        $('#modalAvaliarChamado').modal('hide');
        // Limpar os formulários
        $('#modalComentario form')[0].reset();
        $('#modalPendencia form')[0].reset();
        $('#modalTransferir form')[0].reset();
        $('#modalDevolver form')[0].reset();
        $('#modalResolver form')[0].reset();
        $('#modalAlterarResponsavel form')[0].reset();
        $('#modalResponderUsuario form')[0].reset();
        $('#modalAvaliarChamado form')[0].reset();
    @endif
});
</script>
@stop
