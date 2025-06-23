@csrf
<div class="card-body">
    {{-- Nome --}}
    <div class="form-group">
        <label for="problema_nome">Nome do Problema</label>
        <input type="text" name="problema_nome" id="problema_nome"
            class="form-control @error('problema_nome') is-invalid @enderror"
            value="{{ old('problema_nome', $problema->problema_nome ?? '') }}" maxlength="50" required>
        @error('problema_nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>

    {{-- Departamento --}}
    <div class="form-group">
        <label for="departamento_id">Departamento</label>
        <select name="departamento_id" id="departamento_id"
            class="form-control @error('departamento_id') is-invalid @enderror" required>
            <option value="">Selecione...</option>
            @foreach($departamentos as $dep)
                <option value="{{ $dep->departamento_id }}" {{ old('departamento_id', $problema->departamento_id ?? '') == $dep->departamento_id ? 'selected' : '' }}>
                    {{ $dep->departamento_nome }}
                </option>
            @endforeach
        </select>
        @error('departamento_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>