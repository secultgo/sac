@extends('adminlte::page')

@section('title', 'Novo Chamado')

@section('content_header')
    <h1>Novo Chamado</h1>
@endsection

@section('content')
    <form action="{{ route('chamados.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="departamento_id">Departamento</label>
                    <select name="departamento_id" id="departamento_id" class="form-control" required>
                        <option value="">Selecione</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->departamento_id }}">{{ $departamento->departamento_nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="problema_id">Problema</label>
                    <select name="problema_id" id="problema_id" class="form-control" required disabled>
                        <option value="">Selecione o departamento primeiro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="servico_chamado_id">Serviço</label>
                    <select name="servico_chamado_id" id="servico_chamado_id" class="form-control" required disabled>
                        <option value="">Selecione o problema primeiro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="local_id">Local</label>
                    <select name="local_id" id="local_id" class="form-control" required>
                        <option value="">Selecione</option>
                        @foreach($locais as $local)
                            <option value="{{ $local->local_id }}">{{ $local->local_nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="chamado_descricao">Descrição</label>
            <textarea name="chamado_descricao" id="chamado_descricao" class="form-control" required>{{ old('chamado_descricao') }}</textarea>
        </div>
        <div class="form-group">
            <label for="chamado_ip">IP do Solicitante</label>
            <input type="text" name="chamado_ip" id="chamado_ip" class="form-control" readonly value="{{ $_SERVER['REMOTE_ADDR'] ?? '' }}">
        </div>
        <div class="form-group">
            <label for="chamado_anexo">Anexo (opcional)</label>
            <input type="file" name="chamado_anexo" id="chamado_anexo" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
            <small class="form-text text-muted">Arquivos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT (máx. 10MB)</small>
        </div>
        <button type="submit" class="btn btn-primary">Abrir Chamado</button>
    </form>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departamentoSelect = document.getElementById('departamento_id');
        const problemaSelect = document.getElementById('problema_id');
        const servicoSelect = document.getElementById('servico_chamado_id');

        departamentoSelect.addEventListener('change', function() {
            const departamentoId = this.value;
            problemaSelect.innerHTML = '<option value="">Carregando...</option>';
            problemaSelect.disabled = true;
            servicoSelect.innerHTML = '<option value="">Selecione o problema primeiro</option>';
            servicoSelect.disabled = true;
            if (departamentoId) {
                fetch(`/painel/chamados/problemas/${departamentoId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">Selecione</option>';
                        data.forEach(function(problema) {
                            options += `<option value="${problema.problema_id}">${problema.problema_nome}</option>`;
                        });
                        problemaSelect.innerHTML = options;
                        problemaSelect.disabled = false;
                    });
            } else {
                problemaSelect.innerHTML = '<option value="">Selecione o departamento primeiro</option>';
                problemaSelect.disabled = true;
            }
        });

        problemaSelect.addEventListener('change', function() {
            const problemaId = this.value;
            servicoSelect.innerHTML = '<option value="">Carregando...</option>';
            servicoSelect.disabled = true;
            if (problemaId) {
                fetch(`/painel/chamados/servicos/${problemaId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">Selecione</option>';
                        data.forEach(function(servico) {
                            options += `<option value="${servico.servico_chamado_id}">${servico.servico_chamado_nome}</option>`;
                        });
                        servicoSelect.innerHTML = options;
                        servicoSelect.disabled = false;
                    });
            } else {
                servicoSelect.innerHTML = '<option value="">Selecione o problema primeiro</option>';
                servicoSelect.disabled = true;
            }
        });
    });
</script>
@endsection
