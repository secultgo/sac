@extends('adminlte::page')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Meus Atendimentos')
@section('content_header')
    <h1>Meus Atendimentos</h1>
@stop

@section('content')

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

        <div class="d-flex flex-wrap justify-content-center">
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
                Não Avaliados <span class="badge badge-light ml-1">{{ $contadores['resolvidos'] }}</span>
            </a>
            <a href="{{ route('meus-atendimentos.index', ['status' => 3]) }}" class="btn btn-sm btn-success rounded-pill px-3 mr-2 mb-2 {{ $statusFiltro == 3 ? 'active' : '' }}">
                Fechados <span class="badge badge-light ml-1">{{ $contadores['fechados'] }}</span>
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if($chamados->count() > 0)
            <table id="meusAtendimentosTable" class="table table-striped table-hover mb-0">
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
                        <td>
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
                                    <span class="badge badge-info">Não Avaliado</span>
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

                                <!-- Adicionar Comentário - para chamados que não estão fechados, abertos ou não avaliados -->
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

                                <!-- Transferir Departamento - não disponível para FECHADO (3) e NÃO AVALIADO (5) -->
                                @if(!in_array($chamado->status_chamado_id, [3, 5]))
                                <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-dark mr-1 mb-1" title="Transferir Departamento">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                @endif

                                <!-- Alterar Responsável - não disponível para FECHADO (3), ABERTO (1) e NÃO AVALIADO (5) -->
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
                                Você não possui chamados não avaliados no momento.
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

#meusAtendimentosTable {
    width: 100% !important;
    table-layout: auto;
}

#meusAtendimentosTable th,
#meusAtendimentosTable td {
    padding: 8px 12px;
    vertical-align: top; /* Alinhar no topo para melhor apresentação */
}

/* Definir larguras das colunas */
#meusAtendimentosTable th:nth-child(1), #meusAtendimentosTable td:nth-child(1) { width: 80px; min-width: 80px; }  /* ID */
#meusAtendimentosTable th:nth-child(2), #meusAtendimentosTable td:nth-child(2) { width: 25%; min-width: 200px; }  /* Descrição */
#meusAtendimentosTable th:nth-child(3), #meusAtendimentosTable td:nth-child(3) { width: 12%; min-width: 120px; }  /* Solicitante */
#meusAtendimentosTable th:nth-child(4), #meusAtendimentosTable td:nth-child(4) { width: 12%; min-width: 120px; }  /* Departamento */
#meusAtendimentosTable th:nth-child(5), #meusAtendimentosTable td:nth-child(5) { width: 10%; min-width: 100px; }  /* Local */
#meusAtendimentosTable th:nth-child(6), #meusAtendimentosTable td:nth-child(6) { width: 10%; min-width: 100px; }  /* Problema */
#meusAtendimentosTable th:nth-child(7), #meusAtendimentosTable td:nth-child(7) { width: 8%; min-width: 90px; }   /* Data Criação */
#meusAtendimentosTable th:nth-child(8), #meusAtendimentosTable td:nth-child(8) { width: 8%; min-width: 90px; }   /* Data Atendimento */
#meusAtendimentosTable th:nth-child(9), #meusAtendimentosTable td:nth-child(9) { width: 7%; min-width: 80px; }   /* Status */
#meusAtendimentosTable th:nth-child(10), #meusAtendimentosTable td:nth-child(10) { width: 12%; min-width: 120px; white-space: nowrap; } /* Ações */

/* Estilo para dispositivos menores */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter input {
        width: 200px;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    #meusAtendimentosTable {
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
    $('#meusAtendimentosTable').DataTable({
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
        "order": [[0, "desc"]], // Ordenar por ID decrescente
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
</script>
@stop
