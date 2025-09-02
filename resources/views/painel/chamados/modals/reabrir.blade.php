<!-- Modal para Reabrir Chamado -->
<div class="modal fade" id="modalReabrirChamado" tabindex="-1" role="dialog" aria-labelledby="modalReabrirChamadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.reabrir', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReabrirChamadoLabel">
                        <i class="fas fa-redo"></i> Reabrir Chamado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> O chamado será reaberto e retornará para atendimento. Descreva o motivo da reabertura.
                    </div>
                    <div class="form-group">
                        <label for="motivoReabertura">Motivo da Reabertura <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoReabertura" name="motivo_reabertura" rows="4" placeholder="Descreva por que você está reabrindo este chamado..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-redo"></i> Reabrir Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
