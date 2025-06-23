@extends('adminlte::page')

@section('title', 'Novo Serviço')

@section('content_header')
    <h1>Novo Serviço</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('servicos.store') }}" method="POST">
        @include('painel.servicos-chamado._form')
        <div class="card-footer">
            <a href="{{ route('servicos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Salvar
            </button>
        </div>
    </form>
</div>
@stop