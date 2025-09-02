<!-- Modal para Responder ao Chamado (Usuário) -->
<div class="modal fade" id="modalResponderUsuario" tabindex="-1" role="dialog" aria-labelledby="modalResponderUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.comentarios.store', $chamado->chamado_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResponderUsuarioLabel">
                        <i class="fas fa-reply"></i> Responder Solicitação
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Atenção:</strong> Sua resposta fará com que o chamado retorne automaticamente para atendimento.
                    </div>
                    <div class="form-group">
                        <label for="comentarioResposta">Sua Resposta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentarioResposta" name="comentario" rows="5" placeholder="Digite sua resposta ao técnico responsável..." required></textarea>
                        <small class="form-text text-muted">
                            Descreva detalhadamente sua resposta à solicitação do técnico.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="anexoResposta">Anexo (Opcional)</label>
                        <input type="file" class="form-control-file" id="anexoResposta" name="anexo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
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
