@extends('adminlte::page')

@section('content_header')
    <h1>{{ isset($problema) ? 'Editar Problema' : 'Novo Problema' }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ isset($problema) ? route('problemas.update', $problema) : route('problemas.store') }}"
        method="POST">
        @csrf
        @if(isset($problema)) @method('PUT') @endif
        @include('painel.problemas._form')
        <div class="card-footer">
            <a href="{{ route('problemas.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                Voltar</a>
            <button type="submit" class="btn btn-{{ isset($problema) ? 'primary' : 'success' }}">
                <i class="fas fa-{{ isset($problema) ? 'save' : 'check' }}"></i>
                {{ isset($problema) ? 'Atualizar' : 'Salvar' }}
            </button>
        </div>
    </form>
</div>
@stop