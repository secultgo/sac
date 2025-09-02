<!-- Modal para Responder Chamado (Meus Chamados) -->
<div class="modal fade" id="modalResponder{{ $chamado->chamado_id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-reply"></i> Responder Chamado #{{ $chamado->chamado_id }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comentario{{ $chamado->chamado_id }}">Sua Resposta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentario{{ $chamado->chamado_id }}" name="comentario" rows="4" placeholder="Digite sua resposta..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="anexo{{ $chamado->chamado_id }}">Anexo (Opcional)</label>
                        <input type="file" class="form-control-file" id="anexo{{ $chamado->chamado_id }}" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <small class="form-text text-muted">
                            Formatos aceitos: JPG, PNG, PDF, DOC, DOCX, TXT. Tamanho máximo: 5MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-reply"></i> Enviar Resposta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
