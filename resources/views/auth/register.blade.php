@extends('adminlte::auth.register')

@section('auth_header', 'Secretaria de Estado da Cultura')

@push('css')
<style>
    .login-box {
        width: 1000px;
        margin-top: 20px;
    }
    .login-logo img {
        width: 800px !important;
        height: auto !important;
        margin: 20px auto;
    }
    .login-page {
        min-height: 100vh !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-body {
        padding: 40px;
    }
    .form-group {
        margin-bottom: 25px;
    }
</style>
@endpush

@section('auth_logo')
    <img src="{{ asset('vendor/adminlte/dist/img/SECULT-LOGO-2022.png') }}" 
         alt="Logo do Sistema de Abertura de Chamados" 
         class="brand-image img-circle elevation-3">
@endsection

@section('auth_body')
    <form action="{{ route('register') }}" method="post">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nome completo" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Senha">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Confirmar senha">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Register button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Registrar
                </button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">
            Já tenho uma conta
        </a>
    </p>
@endsection