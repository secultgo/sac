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

<button type="submit" class="btn btn-success">Salvar</button>
<a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
