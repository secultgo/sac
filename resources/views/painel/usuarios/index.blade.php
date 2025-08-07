@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>Usuários</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-end">
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary mr-2">
                <i class="fas fa-plus"></i> Novo Usuário
            </a>
            <a href="{{ route('usuarios.importar.ldap') }}" class="btn btn-dark">
                <i class="fas fa-cloud-upload-alt"></i> Importar LDAP
            </a>
        </div>

        <div class="card-body p-0">
            <table id="usuariosTable" class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Departamento</th>
                        <th>Email</th>
                        <th>Nível</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td>{{ $u->usuario_id }}</td>
                            <td>{{ $u->usuario_nome }}</td>
                            <td>
                                {{ $departamentos->firstWhere('departamento_id', $u->departamento_id)->departamento_nome ?? 'Sem departamento' }}
                            </td>
                            <td>{{ $u->usuario_email }}</td>
                            <td>
                                @php
                                    $nivelUser = $nivel_usuarios->firstWhere('usuario_id', $u->usuario_id);
                                    $nivelNome = 'Sem nível';
                                    if ($nivelUser) {
                                        $nivel = $niveis->firstWhere('nivel_id', $nivelUser->nivel_id);
                                        $nivelNome = $nivel->nivel_nome ?? 'Sem nível';
                                    }
                                @endphp
                                {{ $nivelNome }}
                            </td>
                            <td class="text-right">
                                @if($u->status_id == 1)
                                    <form action="{{ route('usuarios.desativar', $u->usuario_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-default" title="Desativar">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('usuarios.ativar', $u->usuario_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-default" title="Ativar">
                                            <i class="fas fa-toggle-off"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('usuarios.edit_nivel', $u->usuario_id) }}" class="btn btn-sm btn-secondary" title="Editar Nível">
                                    <i class="fas fa-user-shield"></i>
                                </a>

                                <a href="{{ route('usuarios.edit', $u->usuario_id) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('usuarios.destroy', $u->usuario_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
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
                            <td colspan="6" class="text-center">Nenhum usuário cadastrado.</td>
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
    $('#usuariosTable').DataTable({
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
                "targets": [5],
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
