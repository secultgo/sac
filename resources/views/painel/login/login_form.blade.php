@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('title', 'Login')

@section('auth_body')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="" method="POST">
        @csrf

        <div class="input-group mb-4">
            <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        <div class="input-group mb-4">
            <input type="password" name="password" class="form-control" placeholder="Senha" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Entrar</button>
        </div>
    </form>

    <p class="mb-1">
        <a href="">Esqueceu a senha?</a>
    </p>
@endsection

@section('auth_footer')
    <small class="d-block text-center">Sistema v2.0</small>
@endsection
