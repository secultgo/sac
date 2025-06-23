@csrf

<div class="card-body">
    {{-- Problema --}}
    <div class="form-group">
        <label for="problema_id">Problema</label>
        <select name="problema_id" id="problema_id" class="form-control @error('problema_id') is-invalid @enderror"
            required>
            <option value="">Selecione...</option>
            @foreach($problemas as $p)
                <option value="{{ $p->problema_id }}" {{ old('problema_id', $servicosChamado->problema_id ?? '') == $p->problema_id ? 'selected' : '' }}>
                    {{ $p->problema_nome }}
                </option>
            @endforeach
        </select>
        @error('problema_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>

    {{-- Nome --}}
    <div class="form-group">
        <label for="servico_chamado_nome">Nome do Serviço</label>
        <input type="text" name="servico_chamado_nome" id="servico_chamado_nome"
            class="form-control @error('servico_chamado_nome') is-invalid @enderror"
            value="{{ old('servico_chamado_nome', $servicosChamado->servico_chamado_nome ?? '') }}" maxlength="100"
            required>
        @error('servico_chamado_nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>