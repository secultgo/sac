@extends('adminlte::page')

@section('title', 'Importar Usuários')

@section('content_header')
    <h1>Importar Usuários</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Importar Usuários via LDAP</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('usuarios.importar.ldap.post') }}">
                @csrf

                <div class="form-group">
                    <label for="ldap_server">Selecione o servidor LDAP:</label>
                    <select name="ldap_server" id="ldap_server" class="form-control">
                        @foreach($ldaps as $ldap)
                            <option value="{{ $ldap }}">{{ $ldap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download mr-1"></i> Importar Usuários
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
