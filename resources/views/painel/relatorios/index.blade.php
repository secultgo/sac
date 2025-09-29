@extends('adminlte::page')

@section('title', 'Relatórios - ' . $titulo)

@section('content_header')
    <h1>{{ $titulo }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $subtitulo }}</h3>
        </div>

        <div class="card-body p-0">
            <table id="chamadosTable" class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data de criação</th>
                        <th>Data atendimento</th>
                        <th>Data resolução</th>
                        <th>Data fechamento</th>
                        <th>Usuário</th>
                        <th>Lotação</th>
                        <th>Local</th>
                        <th>Problema</th>
                        <th>Serviço</th>
                        <th>Telefone</th>
                        <th>Responsável</th>
                        <th>Departamento</th>
                        <th>Status</th>
                        <th>Avaliação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chamados as $chamado)
                        <tr>
                            <td>{{ $chamado->chamado_id }}</td>
                            <td>{{ $chamado->chamado_abertura ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : '' }}</td>
                            <td>{{ $chamado->chamado_atendimento ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : '' }}</td>
                            <td>{{ $chamado->chamado_resolvido ? \Carbon\Carbon::parse($chamado->chamado_resolvido)->format('d/m/Y H:i:s') : '' }}</td>
                            <td>{{ $chamado->chamado_fechado ? \Carbon\Carbon::parse($chamado->chamado_fechado)->format('d/m/Y H:i:s') : '' }}</td>
                            <td>{{ $chamado->solicitante_nome ?? '' }}</td>
                            <td>{{ $chamado->lotacao_nome ?? '' }}</td>
                            <td>{{ $chamado->local_nome ?? '' }}</td>
                            <td>{{ $chamado->problema_nome ?? '' }}</td>
                            <td>{{ $chamado->servico_chamado_nome ?? '' }}</td>
                            <td>{{ $chamado->usuario_fone_residencial ?? '' }}</td>
                            <td>{{ $chamado->responsavel_nome ?? '' }}</td>
                            <td>{{ $chamado->departamento_nome ?? '' }}</td>
                            <td>{{ $chamado->status_chamado_nome ?? '' }}</td>
                            <td>{{ $chamado->avaliacao_chamado_nome ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center">{{ $mensagem_vazia ?? 'Nenhum chamado encontrado.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
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
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "order": [[0, "desc"]],
        "columnDefs": [
            {
                "targets": [14],
                "orderable": false,
                "searchable": false
            }
        ],
        "dom": 'Bfrtip',
        "buttons": [
            'colvis',
            'pageLength',
            {
                extend: 'excelHtml5',
                text: 'Excel'
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A3',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    // Ajustar larguras das colunas de forma mais equilibrada
                    doc.content[1].table.widths = ['4%', '7%', '7%', '7%', '7%', '12%', '8%', '8%', '8%', '8%', '6%', '10%', '10%', '6%', '6%'];
                    
                    // Configurações de fonte
                    doc.styles.tableHeader.fontSize = 8;
                    doc.styles.tableHeader.bold = true;
                    doc.defaultStyle.fontSize = 7;
                    
                    // Configurações da tabela
                    doc.content[1].table.headerRows = 1;
                    doc.content[1].table.dontBreakRows = true;
                    
                    // Margens da página
                    doc.pageMargins = [20, 20, 20, 20];
                    
                    // Quebra de linha automática
                    doc.content[1].table.widths.forEach(function(width, index) {
                        doc.content[1].table.body.forEach(function(row) {
                            if (row[index] && row[index].text) {
                                row[index].style = { fontSize: 7 };
                            }
                        });
                    });
                }
            },
            {
                extend: 'print',
                text: 'Imprimir'
            }
        ],
        "scrollX": true
    });
});
</script>
@stop
