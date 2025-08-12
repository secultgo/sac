@extends('adminlte::page')

@php
use App\Models\StatusChamado;
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard Painel de Controle</h1>
@stop

@section('content')

<div class="row mb-4">
    <!-- Card 1: Total de Chamados do Departamento -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ ($contadores['abertos'] ?? 0) + ($contadores['atendimento'] ?? 0) + ($contadores['fechados'] ?? 0) + ($contadores['pendentes'] ?? 0) + ($contadores['resolvidos'] ?? 0) + ($contadores['aguardando_usuario'] ?? 0) }}</h3>
                <p>Total de Chamados</p>
            </div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
            <a href="{{ route('painel.dashboard') }}" class="small-box-footer">
                Ver todos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Card 2: Total de Chamados Fechados -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $contadores['fechados'] ?? 0 }}</h3>
                <p>Chamados Fechados</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('painel.dashboard', ['status' => 3]) }}" class="small-box-footer">
                Ver fechados <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Card 3: Chamados Pendentes -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $contadores['pendentes'] ?? 0 }}</h3>
                <p>Chamados Pendentes</p>
            </div>
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            <a href="{{ route('painel.dashboard', ['status' => 4]) }}" class="small-box-footer">
                Ver pendentes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Card 4: Chamados Abertos -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $contadores['abertos'] ?? 0 }}</h3>
                <p>Chamados Abertos</p>
            </div>
            <div class="icon"><i class="fas fa-folder-open"></i></div>
            <a href="{{ route('painel.dashboard', ['status' => 1]) }}" class="small-box-footer">
                Ver abertos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Card 5: Total de Chamados do Mês -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $contadores['mes_atual'] ?? 0 }}</h3>
                <p>Chamados do Mês</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <a href="{{ route('painel.dashboard') }}" class="small-box-footer">
                Ver do mês <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Card 6: Percentual de Chamados Fechados no Mês -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $contadores['percentual_fechados_mes'] ?? 0 }}%</h3>
                <p>% Fechados no Mês</p>
            </div>
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <a href="{{ route('painel.dashboard', ['status' => 3]) }}" class="small-box-footer">
                Ver relatório <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <!-- Tabela de Chamados -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center flex-wrap">
        <h3 class="card-title mb-2 mb-md-0">Chamados</h3>

        <div class="d-flex flex-wrap justify-content-center">
            <a href="{{ route('painel.dashboard', ['status' => 1]) }}" class="btn btn-sm btn-danger rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 1 ? 'active' : '' }}">
                Abertos <span class="badge badge-light ml-1">{{ $contadores['abertos'] ?? 0 }}</span>
            </a>
            <a href="{{ route('painel.dashboard', ['status' => 2]) }}" class="btn btn-sm btn-warning rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 2 ? 'active' : '' }}">
                Atendimento <span class="badge badge-light ml-1">{{ $contadores['atendimento'] ?? 0 }}</span>
            </a>
            <a href="{{ route('painel.dashboard', ['status' => 4]) }}" class="btn btn-sm bg-orange text-white rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 4 ? 'active' : '' }}">
                Pendentes <span class="badge badge-light ml-1">{{ $contadores['pendentes'] ?? 0 }}</span>
            </a>
            <a href="{{ route('painel.dashboard', ['status' => 6]) }}" class="btn btn-sm btn-secondary rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 6 ? 'active' : '' }}">
                Aguardando Usuário <span class="badge badge-light ml-1">{{ $contadores['aguardando_usuario'] ?? 0 }}</span>
            </a>
            <a href="{{ route('painel.dashboard', ['status' => 5]) }}" class="btn btn-sm btn-info rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 5 ? 'active' : '' }}">
                Não Avaliados <span class="badge badge-light ml-1">{{ $contadores['resolvidos'] ?? 0 }}</span>
            </a>
            <a href="{{ route('painel.dashboard', ['status' => 3]) }}" class="btn btn-sm btn-success rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 3 ? 'active' : '' }}">
                Fechados <span class="badge badge-light ml-1">{{ $contadores['fechados'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if($chamados->count() > 0)
            <table id="chamadosTable" class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Solicitante</th>
                        <th>Departamento</th>
                        <th>Local</th>
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
                        <td>{{ $chamado->chamado_id }}</td>
                        <td>
                            {{ $chamado->chamado_descricao }}
                        </td>
                        <td>{{ $chamado->usuario->usuario_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->departamento->departamento_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->local->local_nome ?? 'N/A' }}</td>
                        <td>{{ $chamado->responsavel->usuario_nome ?? 'Não atribuído' }}</td>
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
                                    <span class="badge badge-secondary">Aguardando Usuário</span>
                                    @break
                                @case(8)
                                    <span class="badge bg-purple text-white">Reaberto</span>
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

                                <!-- Iniciar Atendimento - para chamados ABERTOS ou REABERTOS -->
                                @if(in_array($chamado->status_chamado_id, [1, 8]))
                                <form action="{{ route('chamados.iniciar', $chamado->chamado_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning mr-1 mb-1" title="{{ $chamado->status_chamado_id == 1 ? 'Iniciar Atendimento' : 'Reiniciar Atendimento' }}">
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

                                <!-- Responder Solicitação - apenas para usuários com chamados aguardando -->
                                @if($chamado->status_chamado_id == 6 && Auth::user()->usuario_id == $chamado->usuario_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-success mr-1 mb-1" title="Responder Solicitação">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @endif

                                <!-- Adicionar Comentário - para chamados que não estão fechados, abertos ou não avaliados -->
                                @if(!in_array($chamado->status_chamado_id, [3, 1, 5]) && $chamado->status_chamado_id != 6)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-secondary mr-1 mb-1" title="Adicionar Comentário">
                                    <i class="fas fa-comment-dots"></i>
                                </a>
                                @endif

                                <!-- Colocar em Pendência - apenas para chamados em ATENDIMENTO (2) ou REABERTOS (8) -->
                                @if(in_array($chamado->status_chamado_id, [2, 8]) && Auth::user()->departamento_id == $chamado->departamento_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-info mr-1 mb-1" title="Colocar em Pendência">
                                    <i class="fas fa-hourglass-half"></i>
                                </a>
                                @endif

                                <!-- Devolver ao Usuário - para chamados em ATENDIMENTO (2), PENDENTE (4) ou REABERTOS (8) -->
                                @if(in_array($chamado->status_chamado_id, [2, 4, 8]) && Auth::user()->departamento_id == $chamado->departamento_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-warning mr-1 mb-1" title="Devolver ao Usuário">
                                    <i class="fas fa-undo"></i>
                                </a>
                                @endif

                                <!-- Resolver Chamado - para ATENDIMENTO (2), PENDENTE (4), AGUARDANDO_USUARIO (6) e REABERTOS (8) -->
                                @if(in_array($chamado->status_chamado_id, [2, 4, 6, 8]) && Auth::user()->departamento_id == $chamado->departamento_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-success mr-1 mb-1" title="Resolver Chamado">
                                    <i class="fas fa-check"></i>
                                </a>
                                @endif

                                <!-- Transferir Departamento - não disponível para FECHADO (3) e NÃO AVALIADO (5) -->
                                @if(!in_array($chamado->status_chamado_id, [3, 5]) && Auth::user()->departamento_id == $chamado->departamento_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-dark mr-1 mb-1" title="Transferir Departamento">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                @endif

                                <!-- Alterar Responsável - não disponível para FECHADO (3), ABERTO (1) e NÃO AVALIADO (5) -->
                                @if(!in_array($chamado->status_chamado_id, [3, 1, 5]) && Auth::user()->departamento_id == $chamado->departamento_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-outline-primary mr-1 mb-1" title="Alterar Responsável">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @endif

                                <!-- Avaliar Atendimento - apenas para chamados NÃO AVALIADOS (5) pelo usuário solicitante -->
                                @if($chamado->status_chamado_id == 5 && Auth::user()->usuario_id == $chamado->usuario_id)
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-warning mr-1 mb-1" title="Avaliar Atendimento">
                                    <i class="fas fa-star"></i>
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
                    <h4><i class="fas fa-info-circle"></i> Nenhum chamado encontrado</h4>
                    <p>
                        @switch($statusFiltro)
                            @case(1)
                                Não há chamados abertos no momento.
                                @break
                            @case(2)
                                Não há chamados em atendimento no momento.
                                @break
                            @case(3)
                                Não há chamados fechados no momento.
                                @break
                            @case(4)
                                Não há chamados pendentes no momento.
                                @break
                            @case(6)
                                Não há chamados aguardando usuário no momento.
                                @break
                            @default
                                Não há chamados cadastrados no momento.
                        @endswitch
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Chamados Não Avaliados -->
@if(session('mostrarModalAvaliacao') && session('chamadosNaoAvaliados') > 0)
<div class="modal fade" id="modalChamadosNaoAvaliados" tabindex="-1" role="dialog" aria-labelledby="modalChamadosNaoAvaliadosLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalChamadosNaoAvaliadosLabel">
                    <i class="fas fa-exclamation-triangle"></i> Chamados Pendentes de Avaliação
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-clipboard-check fa-3x text-warning"></i>
                </div>
                <h5 class="mb-3">Você possui {{ session('chamadosNaoAvaliados') }} chamado{{ session('chamadosNaoAvaliados') > 1 ? 's' : '' }} não avaliado{{ session('chamadosNaoAvaliados') > 1 ? 's' : '' }}!</h5>
                <p class="text-muted">
                    Para abrir um novo chamado, é necessário avaliar primeiro os chamados que já foram resolvidos.
                    Isso nos ajuda a melhorar continuamente nossos serviços.
                </p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Como avaliar:</strong> Procure pelos chamados com status "Não Avaliado" na tabela abaixo e clique no botão "Avaliar Atendimento".
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <i class="fas fa-star"></i> Entendi, vou avaliar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<style>
/* Cores customizadas para badges e cards */
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

.bg-purple {
    background-color: #6f42c1 !important; /* roxo */
    color: white !important; /* texto branco */
}

.bg-purple .inner,
.bg-purple h3,
.bg-purple p {
    color: white !important; /* garantir texto branco */
}

.bg-purple .small-box-footer {
    color: white !important; /* texto do footer branco */
}

/* Badge customizado para status Reaberto */
.badge.bg-purple {
    background-color: #8B008B !important; /* roxo escuro para reaberto */
    color: white !important;
    font-weight: bold !important;
}

/* Estilo para botões ativos */
.btn.active {
    box-shadow: 0 0 0 2px rgba(0,123,255,.5);
    transform: scale(1.05);
}

/* Customizar estilo do DataTables */
.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
    margin-bottom: 10px;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 4px;
    padding: 4px 8px;
    border: 1px solid #ddd;
    width: 250px;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 4px;
    padding: 4px 8px;
    border: 1px solid #ddd;
}

/* Melhorar responsividade da tabela */
.dataTables_wrapper {
    overflow-x: auto;
}

#chamadosTable {
    width: 100% !important;
    table-layout: auto;
}

#chamadosTable th,
#chamadosTable td {
    padding: 8px 12px;
    vertical-align: top; /* Alinhar no topo para melhor apresentação */
}

/* Definir larguras das colunas */
#chamadosTable th:nth-child(1), #chamadosTable td:nth-child(1) { width: 80px; min-width: 80px; }  /* ID */
#chamadosTable th:nth-child(2), #chamadosTable td:nth-child(2) { width: 25%; min-width: 200px; }  /* Descrição */
#chamadosTable th:nth-child(3), #chamadosTable td:nth-child(3) { width: 12%; min-width: 120px; }  /* Solicitante */
#chamadosTable th:nth-child(4), #chamadosTable td:nth-child(4) { width: 12%; min-width: 120px; }  /* Departamento */
#chamadosTable th:nth-child(5), #chamadosTable td:nth-child(5) { width: 10%; min-width: 100px; }  /* Local */
#chamadosTable th:nth-child(6), #chamadosTable td:nth-child(6) { width: 10%; min-width: 100px; }  /* Responsável */
#chamadosTable th:nth-child(7), #chamadosTable td:nth-child(7) { width: 8%; min-width: 90px; }   /* Data Criação */
#chamadosTable th:nth-child(8), #chamadosTable td:nth-child(8) { width: 8%; min-width: 90px; }   /* Data Atendimento */
#chamadosTable th:nth-child(9), #chamadosTable td:nth-child(9) { width: 7%; min-width: 80px; }   /* Status */
#chamadosTable th:nth-child(10), #chamadosTable td:nth-child(10) { width: 12%; min-width: 120px; white-space: nowrap; } /* Ações */

/* Estilo para dispositivos menores */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter input {
        width: 200px;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    #chamadosTable {
        font-size: 0.85em;
    }
}
</style>
@stop

@section('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
    // Configurar DataTables
    $('#chamadosTable').DataTable({
        "language": {
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Mostrando _START_ até _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 até 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "infoPostFix": "",
            "thousands": ".",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Buscar:",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Primeiro",
                "last": "Último",
                "next": "Próximo",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": ativar para classificar a coluna em ordem crescente",
                "sortDescending": ": ativar para classificar a coluna em ordem decrescente"
            },
            "buttons": {
                "colvis": "Colunas",
                "pageLength": "Exibir %d registros"
            }
        },
        "responsive": false,
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        // Manter ordenação do servidor quando filtro = Abertos (1);
        // para outros filtros, ordenar por ID decrescente
        "order": {!! $statusFiltro == 1 ? '[]' : '[[0, "desc"]]' !!},
        "columnDefs": [
            {
                "targets": [9], // Coluna de ações
                "orderable": false,
                "searchable": false
            }
        ],
        "autoWidth": false,
        "dom": 'Bfrtip',
        "buttons": [
            'colvis',
            'pageLength',
            'excelHtml5', 'pdfHtml5',{
                extend: 'print',
                text: 'Imprimir'
            }]
    });
});

// Mostrar modal de chamados não avaliados
@if(session('mostrarModalAvaliacao') && session('chamadosNaoAvaliados') > 0)
    $(document).ready(function() {
        $('#modalChamadosNaoAvaliados').modal('show');
    });
@endif

// Verificar se há notificação de chamado criado
@if(session('chamado_id') && session('posicao_fila'))
    Swal.fire({
        title: 'Chamado Criado com Sucesso!',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p><strong>Seu chamado foi adicionado à fila de espera</strong></p>
                <p>Todas as atualizações sobre o andamento do seu chamado serão registradas diretamente no chamado no SAC.</p>
                <hr>
                <p><strong>Sua posição na fila de atendimento:</strong> 
                   <span style="color: #007bff; font-size: 18px; font-weight: bold;">{{ session('posicao_fila') }}</span>
                </p>
                <p><strong>Número do chamado:</strong> #{{ session('chamado_id') }}</p>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#28a745',
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: '500px'
    });
@endif
</script>
@stop
