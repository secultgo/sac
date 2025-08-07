@extends('adminlte::page')

@section('title', 'Departamentos')
@section('content_header')
  <h1>Departamentos</h1>
@stop

@section('content')
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header d-flex justify-content-end">
      <a href="{{ route('departamentos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo
      </a>
    </div>
    <div class="card-body p-0">
      <table id="departamentosTable" class="table table-striped table-hover mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Chamados</th>
            <th class="text-right">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($departamentos as $d)
            <tr>
              <td>{{ $d->departamento_id }}</td>
              <td>{{ $d->departamento_nome }}</td>
              <td>{{ $d->departamento_sigla }}</td>
              <td>
                @if($d->departamento_chamado)
                  <span class="badge badge-success">Sim</span>
                @else
                  <span class="badge badge-secondary">Não</span>
                @endif
              </td>
              <td class="text-right">
                <a href="{{ route('departamentos.edit', $d) }}" class="btn btn-sm btn-info" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <form
                  action="{{ route('departamentos.destroy', $d) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Deseja realmente excluir?');"
                >
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger" title="Excluir">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">Nenhum departamento cadastrado.</td>
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
    $('#departamentosTable').DataTable({
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
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "order": [[0, "asc"]],
        "columnDefs": [
            {
                "targets": [4],
                "orderable": false,
                "searchable": false
            }
        ],
        "dom": 'Bfrtip',
        "buttons": [
            'colvis',
            'pageLength',
            'excelHtml5', 
            'pdfHtml5',
            {
                extend: 'print',
                text: 'Imprimir'
            }
        ]
    });
});
</script>
@stop
