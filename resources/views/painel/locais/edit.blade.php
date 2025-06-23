@extends('adminlte::page')

@section('title', 'Editar Local')

@section('content_header')
    <h1>Editar Local</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('locais.update', $local) }}" method="POST">
        @csrf @method('PUT')
        @include('painel.locais._form')
        <div class="card-footer">
            <a href="{{ route('locais.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar
            </button>
        </div>
    </form>
</div>
@stop