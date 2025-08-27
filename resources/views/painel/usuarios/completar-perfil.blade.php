@extends('adminlte::page')

@section('title', 'Completar Perfil')

@section('content_header')
    <h1>Completar Perfil</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
        Para utilizar todas as funcionalidades do sistema, você precisa completar as informações do seu perfil.
        Por favor, preencha os campos obrigatórios abaixo:
    </div>

    <div class="card">
        <form action="{{ route('usuarios.atualizar-perfil') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                {{-- Nome Completo --}}
                <div class="form-group">
                    <label for="usuario_nome">Nome Completo</label>
                    <input 
                        type="text" 
                        name="usuario_nome" 
                        id="usuario_nome" 
                        class="form-control" 
                        value="{{ $user->usuario_nome }}"
                        readonly
                    >
                    <small class="form-text text-muted">Este campo não pode ser alterado.</small>
                </div>

                {{-- E-mail --}}
                <div class="form-group">
                    <label for="usuario_email">E-mail</label>
                    <input 
                        type="email" 
                        name="usuario_email" 
                        id="usuario_email" 
                        class="form-control" 
                        value="{{ $user->usuario_email }}"
                        readonly
                    >
                    <small class="form-text text-muted">Este campo não pode ser alterado.</small>
                </div>

                {{-- Departamento --}}
                <div class="form-group">
                    <label for="departamento_id">Departamento <span class="text-danger">*</span></label>
                    <select 
                        name="departamento_id" 
                        id="departamento_id" 
                        class="form-control @error('departamento_id') is-invalid @enderror" 
                        required
                    >
                        <option value="">Selecione seu departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->departamento_id }}" 
                                    @if(old('departamento_id', $user->departamento_id) == $departamento->departamento_id) selected @endif>
                                {{ $departamento->departamento_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Telefone --}}
                <div class="form-group">
                    <label for="usuario_fone_residencial">Telefone <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="usuario_fone_residencial" 
                        id="usuario_fone_residencial" 
                        class="form-control @error('usuario_fone_residencial') is-invalid @enderror" 
                        value="{{ old('usuario_fone_residencial', $user->usuario_fone_residencial) }}"
                        placeholder="(11) 3333-4444" 
                        maxlength="15"
                        required
                    >
                    @error('usuario_fone_residencial')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">
                        Informe um número de telefone para facilitar a comunicação sobre seus chamados.
                    </small>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Completar Perfil e Continuar
                </button>
            </div>
        </form>
    </div>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i>
        Após completar seu perfil, você poderá abrir chamados e utilizar todas as funcionalidades do sistema.
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Máscara para telefone brasileiro (fixo)
        $('#usuario_fone_residencial').on('input', function() {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length <= 10) {
                // Formato: (11) 3333-4444
                value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
                value = value.replace(/^(\d{2})(\d{1,4})$/, '($1) $2');
                value = value.replace(/^(\d{2})$/, '($1');
            } else {
                // Formato: (11) 9 3333-4444 (celular com 9)
                value = value.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1) $2 $3-$4');
                value = value.replace(/^(\d{2})(\d{1})(\d{1,4})$/, '($1) $2 $3');
                value = value.replace(/^(\d{2})(\d{1})$/, '($1) $2');
            }
            
            this.value = value;
        });

        // Foco automático no primeiro campo obrigatório vazio
        if (!$('#departamento_id').val()) {
            $('#departamento_id').focus();
        } else if (!$('#usuario_fone_residencial').val()) {
            $('#usuario_fone_residencial').focus();
        }
    });
</script>
@stop
