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
        <div class="mb-3 text-center">
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
            <table id="meusChamadosTable" class="table table-striped table-hover mb-0">
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
                        <td>
                            {{ $chamado->chamado_descricao }}
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
                                @case(8)
                                    <span class="badge bg-purple text-white">Reaberto</span>
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

{{-- Incluir modais específicos para meus chamados --}}
@foreach($chamados as $chamado)
    @if($chamado->status_chamado_id == 6) {{-- Aguardando resposta do usuário --}}
        @include('painel.chamados.modals.responder-meus-chamados')
    @endif
    
    @if($chamado->status_chamado_id == 5) {{-- Não Avaliado --}}
        @include('painel.chamados.modals.avaliar-meus-chamados')
    @endif
@endforeach

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
@include('painel.chamados.modals._styles')
<style>
/* Cores customizadas para badges e cards */
.bg-orange {
    background-color: #fd7e14 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
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

#meusChamadosTable {
    width: 100% !important;
    table-layout: auto;
}

#meusChamadosTable th,
#meusChamadosTable td {
    padding: 8px 12px;
    vertical-align: top; /* Alinhar no topo para melhor apresentação */
}

/* Definir larguras das colunas */
#meusChamadosTable th:nth-child(1), #meusChamadosTable td:nth-child(1) { width: 80px; min-width: 80px; }  /* ID */
#meusChamadosTable th:nth-child(2), #meusChamadosTable td:nth-child(2) { width: 25%; min-width: 200px; }  /* Descrição */
#meusChamadosTable th:nth-child(3), #meusChamadosTable td:nth-child(3) { width: 12%; min-width: 120px; }  /* Departamento */
#meusChamadosTable th:nth-child(4), #meusChamadosTable td:nth-child(4) { width: 10%; min-width: 100px; }  /* Local */
#meusChamadosTable th:nth-child(5), #meusChamadosTable td:nth-child(5) { width: 10%; min-width: 100px; }  /* Problema */
#meusChamadosTable th:nth-child(6), #meusChamadosTable td:nth-child(6) { width: 12%; min-width: 120px; }  /* Responsável */
#meusChamadosTable th:nth-child(7), #meusChamadosTable td:nth-child(7) { width: 8%; min-width: 90px; }   /* Data Criação */
#meusChamadosTable th:nth-child(8), #meusChamadosTable td:nth-child(8) { width: 8%; min-width: 90px; }   /* Data Atendimento */
#meusChamadosTable th:nth-child(9), #meusChamadosTable td:nth-child(9) { width: 7%; min-width: 80px; }   /* Status */
#meusChamadosTable th:nth-child(10), #meusChamadosTable td:nth-child(10) { width: 12%; min-width: 120px; white-space: nowrap; } /* Ações */

.descricao-limitada {
    max-width: 200px;
    word-wrap: break-word;
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

/* Estilo para dispositivos menores */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter input {
        width: 200px;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    #meusChamadosTable {
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

@include('painel.chamados.modals._scripts')

<script>
$(document).ready(function() {
    // Configurar DataTables
    $('#meusChamadosTable').DataTable({
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

    // Toastr notifications
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
