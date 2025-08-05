<!-- Timeline do Chamado -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history"></i> Histórico do Chamado
        </h3>
    </div>
    <div class="card-body">
        <div class="timeline">
            @if($chamado->chamado_abertura)
            <div class="timeline-item">
                <h6><i class="fas fa-plus-circle text-danger"></i> Chamado Aberto</h6>
                <p class="text-muted mb-0">
                    {{ is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : $chamado->chamado_abertura->format('d/m/Y H:i:s') }}
                </p>
                <small class="text-muted">Por: {{ $chamado->usuario->usuario_nome ?? 'N/A' }}</small>
            </div>
            @endif

            @if($chamado->chamado_atendimento)
            <div class="timeline-item">
                <h6><i class="fas fa-play-circle text-warning"></i> Atendimento Iniciado</h6>
                <p class="text-muted mb-0">
                    {{ is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : $chamado->chamado_atendimento->format('d/m/Y H:i:s') }}
                </p>
                <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
            </div>
            @endif

            @if($chamado->chamado_pendente)
            <div class="timeline-item">
                <h6><i class="fas fa-pause-circle text-orange"></i> Chamado Pendente</h6>
                <p class="text-muted mb-0">
                    {{ is_string($chamado->chamado_pendente) ? \Carbon\Carbon::parse($chamado->chamado_pendente)->format('d/m/Y H:i:s') : $chamado->chamado_pendente->format('d/m/Y H:i:s') }}
                </p>
                @if($chamado->chamado_pendencia)
                <small class="text-muted">Motivo: {{ $chamado->chamado_pendencia }}</small>
                @endif
            </div>
            @endif

            @if($chamado->chamado_resolvido)
            <div class="timeline-item">
                <h6><i class="fas fa-check-circle text-info"></i> Chamado Resolvido</h6>
                <p class="text-muted mb-0">
                    {{ is_string($chamado->chamado_resolvido) ? \Carbon\Carbon::parse($chamado->chamado_resolvido)->format('d/m/Y H:i:s') : $chamado->chamado_resolvido->format('d/m/Y H:i:s') }}
                </p>
                <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
            </div>
            @endif

            @if($chamado->chamado_fechado)
            <div class="timeline-item">
                <h6><i class="fas fa-times-circle text-success"></i> Chamado Fechado</h6>
                <p class="text-muted mb-0">
                    {{ is_string($chamado->chamado_fechado) ? \Carbon\Carbon::parse($chamado->chamado_fechado)->format('d/m/Y H:i:s') : $chamado->chamado_fechado->format('d/m/Y H:i:s') }}
                </p>
                <small class="text-muted">Por: {{ $chamado->responsavel->usuario_nome ?? 'N/A' }}</small>
            </div>
            @endif
        </div>
    </div>
</div>
