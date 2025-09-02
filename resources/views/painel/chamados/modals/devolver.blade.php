<!-- Modal para Devolver ao Usuário -->
<div class="modal fade" id="modalDevolver" tabindex="-1" role="dialog" aria-labelledby="modalDevolverLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.devolver', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDevolverLabel">
                        <i class="fas fa-undo"></i> Devolver Chamado ao Usuário
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> O chamado será devolvido ao usuário e ficará aguardando resposta. Descreva o motivo da devolução.
                    </div>
                    <div class="form-group">
                        <label for="motivoDevolucao">Motivo da Devolução <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoDevolucao" name="motivo_devolucao" rows="4" placeholder="Descreva o motivo da devolução ao usuário..." required></textarea>
                        <small class="form-text text-muted">
                            Este comentário será adicionado automaticamente ao histórico do chamado.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo"></i> Devolver ao Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
