<!-- Modal para Alterar Responsável -->
<div class="modal fade" id="modalAlterarResponsavel" tabindex="-1" role="dialog" aria-labelledby="modalAlterarResponsavelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.alterar-responsavel', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlterarResponsavelLabel">
                        <i class="fas fa-user-edit"></i> Alterar Responsável do Chamado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Responsável atual:</strong> {{ $chamado->responsavel ? $chamado->responsavel->name : 'Nenhum responsável definido' }}
                    </div>
                    <div class="form-group">
                        <label for="novoResponsavel">Novo Responsável <span class="text-danger">*</span></label>
                        <select class="form-control" id="novoResponsavel" name="novo_responsavel_id" required>
                            <option value="">Selecione o responsável...</option>
                            @foreach(\App\Models\User::where('departamento_id', $chamado->departamento_id)->where('usuario_id', '!=', $chamado->responsavel_id)->where('status_id', 1)->orderBy('usuario_nome')->get() as $usuario)
                                <option value="{{ $usuario->usuario_id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivoAlteracao">Motivo da Alteração <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoAlteracao" name="motivo_alteracao" rows="4" placeholder="Descreva o motivo da alteração do responsável..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-edit"></i> Alterar Responsável
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
