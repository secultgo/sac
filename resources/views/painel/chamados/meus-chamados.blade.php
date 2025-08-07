@extends('adminlte::page')

@section('title', 'Meus Chamados')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Meus Chamados</h1>
        <a href="{{ route('chamados.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Chamado
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Cards de Estatísticas -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $contadores['abertos'] }}</h3>
                <p>Abertos</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
            <a href="{{ route('meus-chamados.index', ['status' => 1]) }}" class="small-box-footer">
                Ver abertos <i class="fas fa-arrow-circle-right"></i>
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
            <a href="{{ route('meus-chamados.index', ['status' => 2]) }}" class="small-box-footer">
                Ver em atendimento <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $contadores['resolvidos'] }}</h3>
                <p>Não Avaliados</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('meus-chamados.index', ['status' => 5]) }}" class="small-box-footer">
                Ver não avaliados <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $contadores['fechados'] }}</h3>
                <p>Fechados</p>
            </div>
            <div class="icon"><i class="fas fa-check-double"></i></div>
            <a href="{{ route('meus-chamados.index', ['status' => 3]) }}" class="small-box-footer">
                Ver fechados <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Lista de Chamados
        </h3>
    </div>

    <div class="card-body p-3">
        <!-- Filtros de Status -->
        <div class="mb-3">
            <a href="{{ route('meus-chamados.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 mr-2 mb-2 {{ !request('status') ? 'active' : '' }}">
                Todos
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 1]) }}" class="btn btn-sm btn-danger rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 1 ? 'active' : '' }}">
                Abertos <span class="badge badge-light ml-1">{{ $contadores['abertos'] }}</span>
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 2]) }}" class="btn btn-sm btn-warning rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 2 ? 'active' : '' }}">
                Atendimento <span class="badge badge-light ml-1">{{ $contadores['atendimento'] }}</span>
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 6]) }}" class="btn btn-sm btn-secondary rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 6 ? 'active' : '' }}">
                Aguardando Resposta <span class="badge badge-light ml-1">{{ $contadores['aguardando_usuario'] }}</span>
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 4]) }}" class="btn btn-sm bg-orange text-white rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 4 ? 'active' : '' }}">
                Pendentes <span class="badge badge-light ml-1">{{ $contadores['pendentes'] }}</span>
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 5]) }}" class="btn btn-sm btn-info rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 5 ? 'active' : '' }}">
                Não Avaliados <span class="badge badge-light ml-1">{{ $contadores['resolvidos'] }}</span>
            </a>
            <a href="{{ route('meus-chamados.index', ['status' => 3]) }}" class="btn btn-sm btn-success rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 3 ? 'active' : '' }}">
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
                        <th>Departamento</th>
                        <th>Local</th>
                        <th>Problema</th>
                        <th>Responsável</th>
                        <th>Data de Criação</th>
                        <th>Data Atendimento</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chamados as $chamado)
                    <tr>
                        <td><strong>#{{ $chamado->chamado_id }}</strong></td>
                        <td class="descricao-limitada">
                            {{ Str::limit($chamado->chamado_descricao, 50) }}
                        </td>
                        <td>{{ $chamado->departamento->departamento_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->local->local_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->problema->problema_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->responsavel ? $chamado->responsavel->name : 'Não atribuído' }}</td>
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
                                    <span class="badge badge-info">Não Avaliado</span>
                                    @break
                                @case(6)
                                    <span class="badge badge-secondary">Aguardando Resposta</span>
                                    @break
                                @case(7)
                                    <span class="badge badge-dark">Cancelado</span>
                                    @break
                                @default
                                    <span class="badge badge-dark">Status {{ $chamado->status_chamado_id }}</span>
                            @endswitch
                        </td>
                        <td class="text-right">
                            <div class="d-flex flex-wrap justify-content-end">
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-primary mr-1 mb-1" title="Visualizar Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($chamado->status_chamado_id == 6) <!-- Aguardando resposta do usuário -->
                                    <button class="btn btn-sm btn-success mr-1 mb-1" data-toggle="modal" data-target="#modalResponder{{ $chamado->chamado_id }}" title="Responder">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                @endif
                                
                                @if($chamado->status_chamado_id == 5) <!-- Não Avaliado -->
                                    <button class="btn btn-sm btn-info mr-1 mb-1" data-toggle="modal" data-target="#modalAvaliar{{ $chamado->chamado_id }}" title="Avaliar Solução">
                                        <i class="fas fa-star"></i>
                                    </button>
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
                    <h4><i class="fas fa-info-circle"></i> Nenhum chamado encontrado</h4>
                    <p>
                        @switch($statusFiltro)
                            @case(1)
                                Você não possui chamados abertos no momento.
                                @break
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
                                Você não possui chamados não avaliados no momento.
                                @break
                            @case(6)
                                Você não possui chamados aguardando sua resposta no momento.
                                @break
                            @default
                                Você não possui chamados cadastrados no momento.
                        @endswitch
                    </p>
                    <a href="{{ route('chamados.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Primeiro Chamado
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modais para Responder -->
@foreach($chamados as $chamado)
    @if($chamado->status_chamado_id == 6)
    <div class="modal fade" id="modalResponder{{ $chamado->chamado_id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-reply"></i> Responder Chamado #{{ $chamado->chamado_id }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="comentario{{ $chamado->chamado_id }}">Sua Resposta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comentario{{ $chamado->chamado_id }}" name="comentario" rows="4" placeholder="Digite sua resposta..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="anexo{{ $chamado->chamado_id }}">Anexo (Opcional)</label>
                            <input type="file" class="form-control-file" id="anexo{{ $chamado->chamado_id }}" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
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
    @endif
@endforeach

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
.btn.active {
    box-shadow: 0 0 0 2px rgba(0,123,255,.5);
    transform: scale(1.05);
}

.descricao-limitada {
    max-width: 200px;
    word-wrap: break-word;
}

.bg-orange {
    background-color: #fd7e14 !important;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.small-box {
    border-radius: 0.25rem;
}

.small-box .icon {
    color: rgba(255,255,255,.8);
}

.small-box .small-box-footer {
    color: rgba(255,255,255,.8);
}

.table th {
    border-top: none;
}

.badge {
    font-size: 0.775em;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
});
</script>
@stop
