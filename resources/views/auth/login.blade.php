@extends('adminlte::auth.login')

@section('auth_header', 'Secretaria de Estado da Cultura')

@push('css')
<style>
    .login-box {
        width: 320px;
        margin-top: 20px;
    }
    .login-logo img {
        width: 240px !important;
        height: auto !important;
        margin: 20px auto;
    }
    .login-page {
        min-height: 100vh !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('auth_logo')
    <img src="{{ asset('vendor/adminlte/dist/img/SECULT-LOGO-2022.png') }}" 
         alt="Logo do Sistema de Abertura de Chamados" 
         class="brand-image img-circle elevation-3">
@endsection

@section('auth_body')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email" autofocus>
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

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block">
                    Entrar
                </button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    @if (Route::has('password.request'))
        <p class="my-0">
            <a href="{{ route('password.request') }}">
                Esqueceu sua senha?
            </a>
        </p>
    @endif
@endsection
