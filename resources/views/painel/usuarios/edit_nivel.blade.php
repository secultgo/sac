@extends('adminlte::page')

@section('title', 'Editar Nível do Usuário')

@section('content_header')
    <h1>Editar Nível do Usuário</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
        <form action="{{ route('usuarios.update_nivel', $usuario->usuario_id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="usuario_nivel">Nível</label>
                    <select name="usuario_nivel" id="usuario_nivel" class="form-control @error('usuario_nivel') is-invalid @enderror">
                        @foreach($nivel_usuarios as $nivel)
                            <option value="{{ $nivel->nivel_id }}" 
                                @if(optional($usuario->nivelUsuario)->nivel_id == $nivel->nivel_id) selected @endif>
                                {{ $nivel->nivel_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_nivel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
