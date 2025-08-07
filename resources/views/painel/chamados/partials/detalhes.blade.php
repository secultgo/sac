<!-- Informações Principais -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-ticket-alt"></i> Detalhes do Chamado
        </h3>
    </div>
    <div class="card-body">
        <!-- Status Atual -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="info-box-custom text-center">
                    <h5><i class="fas fa-info-circle"></i> Status Atual</h5>
                    @switch($chamado->status_chamado_id)
                        @case(1)
                            <span class="badge badge-danger status-badge">
                                <i class="fas fa-folder-open"></i> Aberto
                            </span>
                            @break
                        @case(2)
                            <span class="badge badge-warning status-badge">
                                <i class="fas fa-user-clock"></i> Em Atendimento
                            </span>
                            @break
                        @case(3)
                            <span class="badge badge-success status-badge">
                                <i class="fas fa-check"></i> Fechado
                            </span>
                            @break
                        @case(4)
                            <span class="badge bg-orange status-badge">
                                <i class="fas fa-hourglass-half"></i> Pendente
                            </span>
                            @break
                        @case(5)
                            <span class="badge badge-info status-badge">
                                <i class="fas fa-check-circle"></i> Não Avaliado
                            </span>
                            @break
                        @case(6)
                            <span class="badge badge-secondary status-badge">
                                <i class="fas fa-clock"></i> Aguardando Usuário
                            </span>
                            @break
                        @default
                            <span class="badge badge-dark status-badge">
                                Status {{ $chamado->status_chamado_id }}
                            </span>
                    @endswitch
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-user"></i> Solicitante</h5>
                    <p><strong>{{ $chamado->usuario->usuario_nome ?? 'N/A' }}</strong></p>
                    <p class="text-muted mb-0">{{ $chamado->usuario->usuario_email ?? '' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-user-cog"></i> Responsável</h5>
                    <p><strong>{{ $chamado->responsavel->usuario_nome ?? 'Não atribuído' }}</strong></p>
                    <p class="text-muted mb-0">{{ $chamado->responsavel->usuario_email ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-building"></i> Departamento</h5>
                    <p><strong>{{ $chamado->departamento->departamento_nome ?? 'N/A' }}</strong></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-map-marker-alt"></i> Local</h5>
                    <p><strong>{{ $chamado->local->local_nome ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-tools"></i> Problema</h5>
                    <p><strong>{{ $chamado->problema->problema_nome ?? 'N/A' }}</strong></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box-custom">
                    <h5><i class="fas fa-cogs"></i> Serviço</h5>
                    <p><strong>{{ $chamado->servicoChamado->servico_chamado_nome ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>

        @if($chamado->chamado_ip)
        <div class="info-box-custom">
            <h5><i class="fas fa-network-wired"></i> IP do Solicitante</h5>
            <p><code>{{ $chamado->chamado_ip }}</code></p>
        </div>
        @endif

        <div class="info-box-custom">
            <h5><i class="fas fa-comment-alt"></i> Descrição do Problema</h5>
            <p>{{ $chamado->chamado_descricao }}</p>
        </div>

        @if($chamado->chamado_anexo)
        <div class="info-box-custom">
            <h5><i class="fas fa-paperclip"></i> Anexo</h5>
            <div class="attachment-item">
                <i class="fas fa-file"></i>
                <a href="{{ asset('uploads/chamado/' . $chamado->chamado_anexo) }}" target="_blank">
                    {{ $chamado->chamado_anexo }}
                </a>
                <span class="badge badge-secondary ml-2">
                    <i class="fas fa-download"></i> Download
                </span>
            </div>
        </div>
        @endif
    </div>
</div>
