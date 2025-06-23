@extends('adminlte::page')

@section('title', 'Editar Serviço')

@section('content_header')
    <h1>Editar Serviço</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('servicos.update', $servicosChamado) }}" method="POST">
        @csrf
        @method('PUT')
        @include('painel.servicos-chamado._form')
        <div class="card-footer">
            <a href="{{ route('servicos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar
            </button>
        </div>
    </form>
</div>
@stop