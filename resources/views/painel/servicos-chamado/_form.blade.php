@csrf

<div class="card-body">
    {{-- Departamento (apenas para super admin) --}}
    @if(auth()->user()->isSuperAdmin())
    <div class="form-group">
        <label for="departamento_id">Departamento</label>
        <select name="departamento_id" id="departamento_id" class="form-control @error('departamento_id') is-invalid @enderror"
            required>
            <option value="">Selecione...</option>
            @foreach($departamentos as $dept)
                <option value="{{ $dept->departamento_id }}" 
                    {{ old('departamento_id', $servicosChamado->problema->departamento_id ?? '') == $dept->departamento_id ? 'selected' : '' }}>
                    {{ $dept->departamento_nome }}
                </option>
            @endforeach
        </select>
        @error('departamento_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    @else
    {{-- Para gestores e outros usuários, departamento pré-selecionado --}}
    <div class="form-group">
        <label for="departamento_nome">Departamento</label>
        <input type="text" class="form-control" value="{{ $departamentos->first()->departamento_nome ?? 'Não encontrado' }}" readonly>
        <input type="hidden" name="departamento_id" value="{{ auth()->user()->departamento_id }}">
    </div>
    @endif

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

@if(auth()->user()->isSuperAdmin())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departamentoSelect = document.getElementById('departamento_id');
    const problemaSelect = document.getElementById('problema_id');
    
    departamentoSelect.addEventListener('change', function() {
        const departamentoId = this.value;
        
        // Limpa os problemas
        problemaSelect.innerHTML = '<option value="">Selecione...</option>';
        
        if (departamentoId) {
            // Busca problemas do departamento
            fetch(`/painel/servicos/problemas-por-departamento/${departamentoId}`)
                .then(response => response.json())
                .then(problemas => {
                    problemas.forEach(problema => {
                        const option = document.createElement('option');
                        option.value = problema.problema_id;
                        option.textContent = problema.problema_nome;
                        problemaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar problemas:', error);
                });
        }
    });
});
</script>
@endif