@csrf

<div class="card-body">
  {{-- Nome --}}
  <div class="form-group">
    <label for="departamento_nome">Nome</label>
    <input
      type="text"
      name="departamento_nome"
      id="departamento_nome"
      class="form-control @error('departamento_nome') is-invalid @enderror"
      value="{{ old('departamento_nome', $departamento->departamento_nome ?? '') }}"
      maxlength="100"
      required
    >
    @error('departamento_nome')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  {{-- Sigla --}}
  <div class="form-group">
    <label for="departamento_sigla">Sigla</label>
    <input
      type="text"
      name="departamento_sigla"
      id="departamento_sigla"
      class="form-control @error('departamento_sigla') is-invalid @enderror"
      value="{{ old('departamento_sigla', $departamento->departamento_sigla ?? '') }}"
      maxlength="20"
    >
    @error('departamento_sigla')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  {{-- Flag chamado --}}
  <div class="form-group form-check">
    <input type="hidden" name="departamento_chamado" value="0">
    <input
      type="checkbox"
      name="departamento_chamado"
      id="departamento_chamado"
      class="form-check-input"
      value="1"
      {{ old('departamento_chamado', $departamento->departamento_chamado ?? false) ? 'checked' : '' }}
    >
    <label class="form-check-label" for="departamento_chamado">Recebe chamados</label>
  </div>
</div>
