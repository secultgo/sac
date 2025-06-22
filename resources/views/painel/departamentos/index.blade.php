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
    <div class="card-header">
      <a href="{{ route('departamentos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo
      </a>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
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
                <a href="{{ route('departamentos.edit', $d) }}" class="btn btn-sm btn-info">
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
                  <button class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Nenhum departamento cadastrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $departamentos->links() }}
    </div>
  </div>
@stop
