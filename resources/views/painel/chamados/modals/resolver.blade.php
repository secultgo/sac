<!-- Modal para Resolver Chamado -->
<div class="modal fade" id="modalResolver" tabindex="-1" role="dialog" aria-labelledby="modalResolverLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.resolver', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResolverLabel">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Resolução:</strong> Descreva a solução aplicada para resolver o chamado.
                    </div>
                    <div class="form-group">
                        <label for="solucao">Solução Aplicada <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="solucao" name="solucao" rows="4" placeholder="Descreva detalhadamente a solução aplicada..." required></textarea>
                        <small class="form-text text-muted">
                            Esta informação será registrada no histórico do chamado e o status será alterado para "Não Avaliado".
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Resolver Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
