<!-- Timeline do Chamado -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history"></i> Histórico do Chamado
        </h3>
    </div>
    <div class="card-body">
        <div class="timeline">
            @php
                // Coleta todos os eventos e comentários para ordenar cronologicamente
                $eventos = collect();
                
                // Adiciona eventos do chamado
                if($chamado->chamado_abertura) {
                    $eventos->push([
                        'tipo' => 'abertura',
                        'data' => is_string($chamado->chamado_abertura) ? \Carbon\Carbon::parse($chamado->chamado_abertura) : $chamado->chamado_abertura,
                        'usuario' => $chamado->usuario
                    ]);
                }
                
                if($chamado->chamado_atendimento) {
                    $eventos->push([
                        'tipo' => 'atendimento',
                        'data' => is_string($chamado->chamado_atendimento) ? \Carbon\Carbon::parse($chamado->chamado_atendimento) : $chamado->chamado_atendimento,
                        'usuario' => $chamado->responsavel
                    ]);
                }
                
                if($chamado->chamado_pendente) {
                    $eventos->push([
                        'tipo' => 'pendente',
                        'data' => is_string($chamado->chamado_pendente) ? \Carbon\Carbon::parse($chamado->chamado_pendente) : $chamado->chamado_pendente,
                        'usuario' => $chamado->responsavel,
                        'motivo' => $chamado->chamado_pendencia
                    ]);
                }
                
                if($chamado->chamado_resolvido) {
                    $eventos->push([
                        'tipo' => 'resolvido',
                        'data' => is_string($chamado->chamado_resolvido) ? \Carbon\Carbon::parse($chamado->chamado_resolvido) : $chamado->chamado_resolvido,
                        'usuario' => $chamado->responsavel
                    ]);
                }
                
                if($chamado->chamado_fechado) {
                    $eventos->push([
                        'tipo' => 'fechado',
                        'data' => is_string($chamado->chamado_fechado) ? \Carbon\Carbon::parse($chamado->chamado_fechado) : $chamado->chamado_fechado,
                        'usuario' => $chamado->responsavel
                    ]);
                }
                
                // Adiciona comentários
                foreach($chamado->comentarios as $comentario) {
                    $eventos->push([
                        'tipo' => 'comentario',
                        'data' => is_string($comentario->comentario_chamado_data) ? \Carbon\Carbon::parse($comentario->comentario_chamado_data) : $comentario->comentario_chamado_data,
                        'usuario' => $comentario->usuario,
                        'comentario' => $comentario
                    ]);
                }
                
                // Ordena por data
                $eventos = $eventos->sortBy('data');
            @endphp

            @foreach($eventos as $evento)
                <div class="timeline-item">
                    @switch($evento['tipo'])
                        @case('abertura')
                            <h6><i class="fas fa-plus-circle text-danger"></i> Chamado Aberto</h6>
                            @break
                        @case('atendimento')
                            <h6><i class="fas fa-play-circle text-warning"></i> Atendimento Iniciado</h6>
                            @break
                        @case('pendente')
                            <h6><i class="fas fa-pause-circle text-orange"></i> Chamado Pendente</h6>
                            @break
                        @case('resolvido')
                            <h6><i class="fas fa-check-circle text-info"></i> Chamado Não Avaliado</h6>
                            @break
                        @case('fechado')
                            <h6><i class="fas fa-times-circle text-success"></i> Chamado Fechado</h6>
                            @break
                        @case('comentario')
                            <h6><i class="fas fa-comment text-primary"></i> Comentário Adicionado</h6>
                            @break
                    @endswitch
                    
                    <p class="text-muted mb-0">
                        {{ $evento['data']->format('d/m/Y H:i:s') }}
                    </p>
                    <small class="text-muted">Por: {{ $evento['usuario']->usuario_nome ?? 'N/A' }}</small>
                    
                    @if($evento['tipo'] == 'comentario')
                        <div class="mt-2 p-2 bg-light rounded">
                            <p class="mb-1">{{ $evento['comentario']->comentario_chamado_comentario }}</p>
                            @if($evento['comentario']->comentario_chamado_anexo)
                                <div class="mt-2">
                                    <i class="fas fa-paperclip text-muted"></i>
                                    <a href="{{ asset('uploads/chamado/' . $evento['comentario']->comentario_chamado_anexo) }}" target="_blank" class="text-primary">
                                        {{ $evento['comentario']->comentario_chamado_anexo }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @elseif($evento['tipo'] == 'pendente' && isset($evento['motivo']) && $evento['motivo'])
                        <div class="mt-1">
                            <small class="text-muted">Motivo: {{ $evento['motivo'] }}</small>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
