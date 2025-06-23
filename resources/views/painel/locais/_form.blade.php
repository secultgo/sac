@csrf

<div class="card-body">
  {{-- Nome --}}
  <div class="form-group">
    <label for="local_nome">Nome</label>
    <input
      type="text"
      name="local_nome"
      id="local_nome"
      class="form-control @error('local_nome') is-invalid @enderror"
      value="{{ old('local_nome', $local->local_nome ?? '') }}"
      maxlength="50"
      required
    >
    @error('local_nome')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  {{-- Sigla --}}
  <div class="form-group">
    <label for="local_sigla">Sigla</label>
    <input
      type="text"
      name="local_sigla"
      id="local_sigla"
      class="form-control @error('local_sigla') is-invalid @enderror"
      value="{{ old('local_sigla', $local->local_sigla ?? '') }}"
      maxlength="10"
    >
    @error('local_sigla')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  {{-- Endereço --}}
  <div class="form-group">
    <label for="local_endereco">Endereço</label>
    <textarea
      name="local_endereco"
      id="local_endereco"
      class="form-control @error('local_endereco') is-invalid @enderror"
      rows="2"
    >{{ old('local_endereco', $local->local_endereco ?? '') }}</textarea>
    @error('local_endereco')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  {{-- Telefone --}}
  <div class="form-group">
    <label for="local_telefone">Telefone</label>
    <input
      type="text"
      name="local_telefone"
      id="local_telefone"
      class="form-control @error('local_telefone') is-invalid @enderror"
      value="{{ old('local_telefone', $local->local_telefone ?? '') }}"
      maxlength="20"
    >
    @error('local_telefone')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>
</div>
