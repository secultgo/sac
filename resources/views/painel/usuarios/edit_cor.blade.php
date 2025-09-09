@extends('adminlte::page')

@section('title', 'Editar Cor do Usuário')

@section('content_header')
    <h1>Editar Cor do Usuário</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
        <form action="{{ route('usuarios.update_cor', $usuario->usuario_id) }}" method="POST">
            @csrf
            @method('PUT')
                
                <!-- Campo para identificar a origem -->
                @if(request()->headers->get('referer') && str_contains(request()->headers->get('referer'), '/equipe'))
                    <input type="hidden" name="from_equipe" value="1">
                @endif

                <div class="form-group mb-3">
                    <label for="usuario_cor_id">Cores disponíveis</label>
                      <select name="usuario_cor_id" id="usuario_cor_id" class="form-control @error('usuario_cor_id') is-invalid @enderror">
                          @foreach($cores as $cor)
                            <option value="{{ $cor->cor_id }}">
                                {{ $cor->cor_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_cor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('equipe.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
