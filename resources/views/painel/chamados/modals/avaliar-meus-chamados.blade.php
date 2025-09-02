<!-- Modal para Avaliar Chamado (Meus Chamados) -->
<div class="modal fade" id="modalAvaliar{{ $chamado->chamado_id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('chamados.avaliar', $chamado->chamado_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-star"></i> Avaliar Chamado #{{ $chamado->chamado_id }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Seu chamado foi resolvido!</strong> Avalie o atendimento recebido.
                    </div>
                    
                    <div class="form-group">
                        <label for="avaliacao{{ $chamado->chamado_id }}">Avaliação do Atendimento <span class="text-danger">*</span></label>
                        <div class="rating-container">
                            @foreach(\App\Models\AvaliacaoChamado::orderBy('avaliacao_chamado_id', 'asc')->get() as $avaliacao)
                            <div class="radio">
                                <label for="avaliacao{{ $chamado->chamado_id }}_{{ $avaliacao->avaliacao_chamado_id }}">
                                    <input type="radio" name="avaliacao" id="avaliacao{{ $chamado->chamado_id }}_{{ $avaliacao->avaliacao_chamado_id }}" value="{{ $avaliacao->avaliacao_chamado_id }}" required>
                                    @if($avaliacao->avaliacao_chamado_imagem)
                                        {!! $avaliacao->avaliacao_chamado_imagem !!}
                                    @endif
                                    <p>{{ $avaliacao->avaliacao_chamado_nome }}</p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comentarioAvaliacao{{ $chamado->chamado_id }}">Comentário sobre o Atendimento <span class="opcional-text">(Opcional)</span></label>
                        <textarea class="form-control" id="comentarioAvaliacao{{ $chamado->chamado_id }}" name="comentario_avaliacao" rows="4" placeholder="Deixe um comentário sobre o atendimento recebido..."></textarea>
                        <small class="form-text text-muted">
                            Seu feedback nos ajuda a melhorar nossos serviços.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-star"></i> Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
