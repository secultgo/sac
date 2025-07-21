@extends('adminlte::page')

@section('title', 'Meus Atendimentos')
@section('content_header')
  <h1>Meus Atendimentos</h1>
@stop

@section('content')
<style>
.descricao-limitada {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3em;
  max-height: 3.5em; /* 1.3em * 2 linhas */
  max-width: 265px;  /* largura fixa */
  word-break: break-word; /* para evitar overflow */
}


</style>


<div class="row">
<div class="col-lg-3 col-6">
  <div class="small-box bg-warning">
    <div class="inner">
      <h3>10</h3>
      <p>Em Atendimento</p>
    </div>
    <div class="icon">
      <i class="fas fa-headset"></i>
    </div>
    <a href="#" class="small-box-footer">Mais detalhes <i class="fas fa-arrow-circle-right"></i></a>
  </div>
</div>

<div class="col-lg-3 col-6">
  <div class="small-box bg-info">
    <div class="inner">
      <h3>5</h3>
      <p>Pendentes</p>
    </div>
    <div class="icon">
      <i class="fas fa-clock"></i>
    </div>
    <a href="#" class="small-box-footer">Mais detalhes <i class="fas fa-arrow-circle-right"></i></a>
  </div>
</div>

<div class="col-lg-3 col-6">
  <div class="small-box bg-success">
    <div class="inner">
      <h3>20</h3>
      <p>Fechados</p>
    </div>
    <div class="icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <a href="#" class="small-box-footer">Mais detalhes <i class="fas fa-arrow-circle-right"></i></a>
  </div>
</div>

  <!-- Tabela de chamados -->
<table class="table table-striped mb-0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Descrição</th>
      <th>Solicitante</th>
      <th>Lotação</th>
      <th>Local</th>
      <th>Telefone</th>
      <th>Data de Criação</th>
      <th>Data Atendimento</th>
      <th>Status</th>
      <th class="text-right">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>2</td>
      <td class="descricao-limitada">
      Impressora apresentou falha de impressão contínua e parou de funcionar após 
      várias tentativas de reinício. O usuário precisa do equipamento para entrega
       urgente de relatórios financeiros.

      </td>

      <td>Carla Mendes</td>
      <td>Financeiro</td>
      <td>2º Andar</td>
      <td>(62) 98888-1111</td>
      <td>21/07/2025 08:45</td>
      <td>21/07/2025 09:20</td>
      <td><span class="badge badge-success">Concluído</span></td>
      <!-- Deixar apenas o butão "ver" -->
      <td class="text-right">
        <div class="d-flex flex-wrap justify-content-end">
          <!-- Primeira linha: 4 botões -->
          <div class="w-100 mb-1 d-flex justify-content-end flex-wrap">
            <a href="ver" class="btn btn-sm btn-primary mr-1 mb-1" title="Ver">
              <i class="fas fa-eye"></i>
            </a>
            <a href="#" class="btn btn-sm btn-secondary mr-1 mb-1" title="Comentário">
              <i class="fas fa-comment-dots"></i>
            </a>
            <a href="#" class="btn btn-sm btn-warning mr-1 mb-1" title="Devolver ao Usuário">
              <i class="fas fa-undo"></i>
            </a>
            <a href="#" class="btn btn-sm btn-info mr-1 mb-1" title="Pendência">
              <i class="fas fa-hourglass-half"></i>
            </a>
          </div>
          <!-- Segunda linha: 3 botões -->
          <div class="w-100 d-flex justify-content-end flex-wrap">
            <a href="#" class="btn btn-sm btn-dark mr-1 mb-1" title="Transferir de Departamento">
              <i class="fas fa-exchange-alt"></i>
            </a>
            <a href="#" class="btn btn-sm btn-success mr-1 mb-1" title="Alterar Responsável">
              <i class="fas fa-user-edit"></i>
            </a>
            <a href="#" class="btn btn-sm btn-danger mr-1 mb-1" title="Resolver">
              <i class="fas fa-check"></i>
            </a>
          </div>
        </div>
      </td>


    </tr>
  </tbody>
</table>

@stop
