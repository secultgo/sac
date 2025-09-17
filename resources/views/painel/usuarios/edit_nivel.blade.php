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
                
                <!-- Campo para identificar a origem -->
                @if(request()->headers->get('referer') && str_contains(request()->headers->get('referer'), '/equipe'))
                    <input type="hidden" name="from_equipe" value="1">
                @endif

                <div class="form-group mb-3">
                    <label for="usuario_nivel">Nível</label>
                    <select name="usuario_nivel" id="usuario_nivel" class="form-control @error('usuario_nivel') is-invalid @enderror">
                        @foreach($nivel_usuarios as $nivel)
                            @php
                                $nivelAtual = $usuario->nivelUsuarios->first();
                                $usuarioLogadoNivel = auth()->user()->nivelUsuarios->first();
                                
                                // Se o usuário logado for gestor (nível 2), não mostrar Super Usuário (nível 1)
                                if ($usuarioLogadoNivel && $usuarioLogadoNivel->nivel_id == 2 && $nivel->nivel_id == 1) {
                                    continue;
                                }
                            @endphp
                            <option value="{{ $nivel->nivel_id }}" 
                                @if($nivelAtual && $nivelAtual->nivel_id == $nivel->nivel_id) selected @endif>
                                {{ $nivel->nivel_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_nivel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Salvar</button>
                @if(request()->headers->get('referer') && str_contains(request()->headers->get('referer'), '/equipe'))
                    <a href="{{ route('equipe.index') }}" class="btn btn-secondary">Cancelar</a>
                @else
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                @endif
            </form>
        </div>
    </div>
@stop
