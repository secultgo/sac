@extends('adminlte::page')

@section('title', 'Chamados Cientes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Chamados Cientes</h1>
        <a href="{{ route('avaliacoes.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para Avaliações
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-check-circle"></i> Chamados Avaliados com Ciência
                </h3>
            </div>
            <div class="card-body">
                @if(isset($cientes) && $cientes->count() > 0)
                    <div class="table-responsive">
                        <table id="cientesTable" class="table table-bordered table-hover table-sm">
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
                                @foreach($cientes as $chamado)
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
                                            // Pega apenas o comentário que começa com "Avaliação do usuário:"
                                            $comentarioAvaliacao = $chamado->comentarios
                                                ->filter(fn($c) => str_starts_with($c->comentario_chamado_comentario, 'Avaliação do usuário:'))
                                                ->first();

                                            $motivoAvaliacao = $comentarioAvaliacao
                                                ? str_replace('Avaliação do usuário: ', '', $comentarioAvaliacao->comentario_chamado_comentario)
                                                : '';
                                        @endphp

                                        @if($motivoAvaliacao)
                                            <span class="text-muted" title="{{ $motivoAvaliacao }}">
                                                {{ \Illuminate\Support\Str::limit($motivoAvaliacao, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Ciente</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('chamados.show', $chamado->chamado_id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Nenhum chamado ciente encontrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
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

#cientesTable { /* Alterado para cientesTable */
    width: 100% !important;
    table-layout: auto;
}

#cientesTable th, /* Alterado para cientesTable */
#cientesTable td { /* Alterado para cientesTable */
    padding: 8px 12px;
    vertical-align: top; /* Alinhar no topo para melhor apresentação */
}

/* Definir larguras das colunas */
#cientesTable th:nth-child(1), #cientesTable td:nth-child(1) { width: 80px; min-width: 80px; }  /* ID */
#cientesTable th:nth-child(2), #cientesTable td:nth-child(2) { width: 25%; min-width: 200px; }  /* Descrição */
#cientesTable th:nth-child(3), #cientesTable td:nth-child(3) { width: 12%; min-width: 120px; }  /* Solicitante */
#cientesTable th:nth-child(4), #cientesTable td:nth-child(4) { width: 12%; min-width: 120px; }  /* Departamento */
#cientesTable th:nth-child(5), #cientesTable td:nth-child(5) { width: 10%; min-width: 100px; }  /* Responsável */
#cientesTable th:nth-child(6), #cientesTable td:nth-child(6) { width: 8%; min-width: 90px; }  /* Data Criação */
#cientesTable th:nth-child(7), #cientesTable td:nth-child(7) { width: 8%; min-width: 90px; }  /* Data Atendimento */
#cientesTable th:nth-child(8), #cientesTable td:nth-child(8) { width: 7%; min-width: 80px; }  /* Avaliação */
#cientesTable th:nth-child(9), #cientesTable td:nth-child(9) { width: 15%; min-width: 150px; }  /* Motivo da Avaliação */
#cientesTable th:nth-child(10), #cientesTable td:nth-child(10) { width: 7%; min-width: 80px; }  /* Ciência */
#cientesTable th:nth-child(11), #cientesTable td:nth-child(11) { width: 12%; min-width: 120px; white-space: nowrap; } /* Ações */

/* Estilo para dispositivos menores */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter input {
        width: 200px;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    #cientesTable { /* Alterado para cientesTable */
        font-size: 0.85em;
    }
}
</style>
@stop

@section('js')
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
    $('#cientesTable').DataTable({ // Alterado para cientesTable
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
            "search": "Buscar:", // Alterado para "Buscar:"
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
        "responsive": true, // Alterado para true
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "autoWidth": false,
        "dom": 'Bfrtip',
        "buttons": [
            'colvis',
            'pageLength',
        ]  
    });
});
</script>
@stop