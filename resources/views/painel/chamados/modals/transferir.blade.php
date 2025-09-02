<!-- Modal para Transferir Departamento -->
<div class="modal fade" id="modalTransferir" tabindex="-1" role="dialog" aria-labelledby="modalTransferirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.transferir', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTransferirLabel">
                        <i class="fas fa-exchange-alt"></i> Transferir Chamado para Outro Departamento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Departamento atual:</strong> {{ $chamado->departamento->departamento_nome }}
                    </div>
                    <div class="form-group">
                        <label for="novoDepartamento">Novo Departamento <span class="text-danger">*</span></label>
                        <select class="form-control" id="novoDepartamento" name="novo_departamento_id" required>
                            <option value="">Selecione o departamento...</option>
                            @foreach(Departamento::where('departamento_id', '!=', $chamado->departamento_id)->where('departamento_chamado', 1)->orderBy('departamento_nome')->get() as $dept)
                                <option value="{{ $dept->departamento_id }}">{{ $dept->departamento_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivoTransferencia">Motivo da Transferência <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoTransferencia" name="motivo_transferencia" rows="4" placeholder="Descreva o motivo da transferência..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-exchange-alt"></i> Transferir Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
