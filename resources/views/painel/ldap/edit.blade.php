@extends('adminlte::page')

@section('title', 'Editar Configuração LDAP')

@section('content_header')
    <h1>Editar Configuração LDAP</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('ldap.update', $ldap) }}" method="POST">
        @csrf @method('PUT')
        @include('painel.ldap._form')
        <div class="card-footer">
            <a href="{{ route('ldap.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar
            </button>
        </div>
    </form>
</div>
@stop