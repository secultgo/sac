@extends('adminlte::page')

@section('title', 'Novo Departamento')
@section('content_header')
  <h1>Novo Departamento</h1>
@stop

@section('content')
  <div class="card">
    <form action="{{ route('departamentos.store') }}" method="POST">
      @include('painel.departamentos._form')
      <div class="card-footer">
        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <button type="submit" class="btn btn-success">
          <i class="fas fa-check"></i> Salvar
        </button>
      </div>
    </form>
  </div>
@stop
