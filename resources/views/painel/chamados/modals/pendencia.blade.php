<!-- Modal para Colocar em Pendência -->
<div class="modal fade" id="modalPendencia" tabindex="-1" role="dialog" aria-labelledby="modalPendenciaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.pendencia', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPendenciaLabel">
                        <i class="fas fa-hourglass-half"></i> Colocar Chamado em Pendência
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> O chamado será colocado em pendência. Descreva o motivo da pendência.
                    </div>
                    <div class="form-group">
                        <label for="motivoPendencia">Motivo da Pendência <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoPendencia" name="motivo_pendencia" rows="4" placeholder="Descreva o motivo da pendência..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-hourglass-half"></i> Colocar em Pendência
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
