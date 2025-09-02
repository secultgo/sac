<!-- Modal para Adicionar Comentário -->
<div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-labelledby="modalComentarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalComentarioLabel">
                        <i class="fas fa-comment-dots"></i> Adicionar Comentário
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comentario">Comentário <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="4" placeholder="Digite seu comentário..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="anexo">Anexo (Opcional)</label>
                        <input type="file" class="form-control-file" id="anexo" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <small class="form-text text-muted">
                            Formatos aceitos: JPG, PNG, PDF, DOC, DOCX, TXT. Tamanho máximo: 5MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Adicionar Comentário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
