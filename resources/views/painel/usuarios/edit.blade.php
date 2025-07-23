@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content')
    <h1>Editar Usuário</h1>
    <form action="{{ route('usuarios.update', $usuario->usuario_id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('painel.usuarios.form') {{-- ajuste aqui se sua pasta for painel/usuarios --}}
           
    </form>
@endsection
