@extends('adminlte::page')

@section('title', 'Novo Usuário')

@section('content_header')
    <h1>Novo Usuário</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="usuario_nome">Nome</label>
                    <input 
                        type="text" 
                        id="usuario_nome"
                        name="usuario_nome" 
                        value="{{ old('usuario_nome', $usuario->usuario_nome ?? '') }}" 
                        class="form-control @error('usuario_nome') is-invalid @enderror" 
                        required
                    >
                    @error('usuario_nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="usuario_email">Email</label>
                    <input 
                        type="email" 
                        id="usuario_email"
                        name="usuario_email" 
                        value="{{ old('usuario_email', $usuario->usuario_email ?? '') }}" 
                        class="form-control @error('usuario_email') is-invalid @enderror" 
                        required
                    >
                    @error('usuario_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if (!isset($usuario))
                    <div class="form-group mb-3">
                        <label for="usuario_senha">Senha</label>
                        <input 
                            type="password" 
                            id="usuario_senha"
                            name="usuario_senha" 
                            class="form-control @error('usuario_senha') is-invalid @enderror" 
                            required
                        >
                        @error('usuario_senha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="form-group mb-3">
                    <label for="departamento_id">Departamento</label>
                    <select 
                        id="departamento_id"
                        name="departamento_id" 
                        class="form-control @error('departamento_id') is-invalid @enderror" 
                        required
                    >
                        <option value="">Selecione</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->departamento_id }}" 
                                {{ old('departamento_id') == $departamento->departamento_id ? 'selected' : '' }}>
                                {{ $departamento->departamento_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Tipo de Login</label>
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="radio" 
                            name="usuario_ldap" 
                            id="ldap_local" 
                            value="0" 
                            {{ old('usuario_ldap', 0) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="ldap_local">Local</label>
                    </div>
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="radio" 
                            name="usuario_ldap" 
                            id="ldap_ldap" 
                            value="1" 
                            {{ old('usuario_ldap') == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="ldap_ldap">LDAP</label>
                    </div>
                    @error('usuario_ldap')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
