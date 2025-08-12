@extends('adminlte::page')

@section('title', 'Importar Usuários')

@section('content_header')
    <h1>Importar Usuários LDAP</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('usuarios.importar.ldap.post') }}">
            @csrf
            
            <div class="card-body">
                <div class="form-group">
                    <label for="ldap_server">Servidor LDAP:</label>
                    <select name="ldap_server" id="ldap_server" class="form-control" required>
                        <option value="">Selecione um servidor...</option>
                        @foreach($ldaps as $ldap)
                            <option value="{{ $ldap }}">{{ $ldap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="card-footer">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cloud-download-alt"></i> Importar Usuários
                </button>
            </div>
        </form>
    </div>
@stop
