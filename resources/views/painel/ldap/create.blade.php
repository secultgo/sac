@extends('adminlte::page')

@section('title', 'Nova Configuração LDAP')

@section('content_header')
    <h1>Nova Configuração LDAP</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('ldap.store') }}" method="POST">
        @include('painel.ldap._form')
        <div class="card-footer">
            <a href="{{ route('ldap.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Salvar
            </button>
        </div>
    </form>
</div>
@stop