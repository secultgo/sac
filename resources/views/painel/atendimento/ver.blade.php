@extends('adminlte::page')

@section('title', 'Chamado #')
@section('content_header')
  <h1>Chamado #</h1>
@stop

@section('content')
<style>
.placeholder-bg {
  background-color: #f0f0f0;  /* cinza claro */
  padding: 6px 10px;
  border-radius: 4px;
  min-height: 30px;          /* altura mínima para dar espaço */
  color: #555;               /* cor do texto para parecer um placeholder */
  font-style: italic;        /* opcional, para dar efeito de placeholder */
}
</style>


@php
    $chamado = new stdClass();
    $chamado->chamado_id = 13843;
    $chamado->descricao = 'Descrição da necessidade não listada';

    $status = new stdClass();
    $status->nome = 'Atendimento';
    $chamado->status = $status;

    $usuario = new stdClass();
    $usuario->nome = 'Gisele Gomes Garcia';
    $chamado->usuario = $usuario;

    $departamento = new stdClass();
    $departamento->nome = 'Gerência de Tecnologia';
    $chamado->departamento = $departamento;

    $chamado->lotacao = 'Gerência de Museus, Bibliotecas, Instituto do Livro e Arquivo Histórico';
    $chamado->local = 'CENTRO CULTURAL MARIETTA TELLES MACHADO';
    $chamado->ramal = 'Ramal';
    $chamado->problema = 'Outros';
    $chamado->servico = 'Serviço';
    $chamado->responsavel = 'Luis Felipe Pinheiro Peres de Santana';

    $chamado->created_at = \Carbon\Carbon::create(2025, 6, 10, 14, 35, 32);
    $chamado->data_atendimento = \Carbon\Carbon::create(2025, 6, 11, 8, 25, 33);
@endphp

<div class="container py-4">
  <div class="card">
    <div class="card-header">
      <h3>Detalhes do Chamado Nº {{ $chamado->chamado_id }}</h3>
    </div>
    <div class="card-body">
    <dl class="row">
  <dt class="col-sm-3">Usuário</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->usuario->nome }}</dd>

  <dt class="col-sm-3">Lotação</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->lotacao }}</dd>

  <dt class="col-sm-3">Local</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->local }}</dd>

  <dt class="col-sm-3">Ramal</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->ramal }}</dd>

  <dt class="col-sm-3">Status</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->status->nome }}</dd>

  <dt class="col-sm-3">Departamento</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->departamento->nome }}</dd>

  <dt class="col-sm-3">Problema</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->problema }}</dd>

  <dt class="col-sm-3">Serviço</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->servico }}</dd>

  <dt class="col-sm-3">Descrição da necessidade</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->descricao }}</dd>

  <dt class="col-sm-3">Data/Hora Abertura</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->created_at->format('d/m/Y H:i:s') }}</dd>

  <dt class="col-sm-3">Responsável</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->responsavel }}</dd>

  <dt class="col-sm-3">Data/Hora Atendimento</dt>
  <dd class="col-sm-9 placeholder-bg">{{ $chamado->data_atendimento->format('d/m/Y H:i:s') }}</dd>
</dl>


      <div class="text-right">
      <div style="display: flex; justify-content: flex-start; flex-wrap: wrap; gap: 0.25rem; font-size: 0.8rem; padding: 0.5rem 0;">
          <a href="#" class="btn btn-sm btn-warning" title="Devolver ao Usuário" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-undo me-1"></i> Devolver
          </a>
          <a href="#" class="btn btn-sm btn-secondary" title="Andamento" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-comment-dots me-1"></i> Andamento
          </a>
          <a href="#" class="btn btn-sm btn-dark" title="Transferir de Departamento" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-exchange-alt me-1"></i> Transferir
          </a>
          <a href="#" class="btn btn-sm btn-success" title="Alterar Responsável" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-user-edit me-1"></i> Alterar Responsável
          </a>
          <a href="#" class="btn btn-sm btn-info" title="Pendência" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-hourglass-half me-1"></i> Pendência
          </a>
          <a href="#" class="btn btn-sm btn-danger" title="Finalizar" style="padding: 0.25rem 0.5rem;">
            <i class="fas fa-check me-1"></i> Finalizar
          </a>
        </div>
      </div>


    </div>
  </div>
</div>
@endsection