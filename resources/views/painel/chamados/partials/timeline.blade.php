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
                    // Verifica se é um comentário de avaliação do usuário
                    $isAvaliacaoUsuario = str_starts_with($comentario->comentario_chamado_comentario, 'Avaliação do usuário: ');
                    // Verifica se é o comentário automático de fechamento por avaliação
                    $isAvaliacaoFechamento = str_contains($comentario->comentario_chamado_comentario, 'Chamado avaliado como') && str_contains($comentario->comentario_chamado_comentario, 'fechado automaticamente');
                    
                    $tipo = 'comentario';
                    if ($isAvaliacaoUsuario) {
                        $tipo = 'avaliacao_comentario';
                    } elseif ($isAvaliacaoFechamento) {
                        $tipo = 'avaliacao_fechamento';
                    }
                    
                    $eventos->push([
                        'tipo' => $tipo,
                        'data' => is_string($comentario->comentario_chamado_data) ? \Carbon\Carbon::parse($comentario->comentario_chamado_data) : $comentario->comentario_chamado_data,
                        'usuario' => $comentario->usuario,
                        'comentario' => $comentario,
                        'comentario_limpo' => $isAvaliacaoUsuario ? str_replace('Avaliação do usuário: ', '', $comentario->comentario_chamado_comentario) : $comentario->comentario_chamado_comentario
                    ]);
                }
                
                // Ordena por data
                $eventos = $eventos->sortBy('data');
                
                // Consolida eventos que ocorrem na mesma data/hora
                $eventosConsolidados = collect();
                
                foreach($eventos as $index => $evento) {
                    $dataAtual = $evento['data']->format('Y-m-d H:i:s');
                    
                    // Verifica se existe evento de fechamento na mesma data/hora
                    if ($evento['tipo'] === 'fechado') {
                        $comentariosSimultaneos = collect();
                        $avaliacaoComentario = null;
                        
                        // Procura por comentários na mesma data/hora
                        foreach($eventos as $outroEvento) {
                            if ($outroEvento['data']->format('Y-m-d H:i:s') === $dataAtual) {
                                if ($outroEvento['tipo'] === 'avaliacao_fechamento') {
                                    // Não adiciona o comentário automático de fechamento, pois já temos o evento de fechado
                                    continue;
                                } elseif ($outroEvento['tipo'] === 'avaliacao_comentario') {
                                    $avaliacaoComentario = $outroEvento;
                                } elseif ($outroEvento['tipo'] === 'comentario') {
                                    $comentariosSimultaneos->push($outroEvento);
                                }
                            }
                        }
                        
                        // Se há avaliação, cria evento consolidado de avaliação
                        if ($avaliacaoComentario || $chamado->avaliacao_chamado_id) {
                            $eventoConsolidado = [
                                'tipo' => 'avaliacao',
                                'data' => $evento['data'],
                                'usuario' => $chamado->usuario, // Sempre o usuário solicitante que avalia
                                'avaliacao_comentario' => $avaliacaoComentario,
                                'comentarios_extras' => $comentariosSimultaneos
                            ];
                            $eventosConsolidados->push($eventoConsolidado);
                        } else {
                            // Evento de fechamento normal com comentários
                            $eventoConsolidado = $evento;
                            if ($comentariosSimultaneos->isNotEmpty()) {
                                $eventoConsolidado['comentarios_consolidados'] = $comentariosSimultaneos;
                            }
                            $eventosConsolidados->push($eventoConsolidado);
                        }
                    } elseif ($evento['tipo'] === 'resolvido') {
                        // Consolida resolvido com comentários na mesma data/hora
                        $comentariosSimultaneos = collect();
                        
                        foreach($eventos as $outroEvento) {
                            if ($outroEvento['data']->format('Y-m-d H:i:s') === $dataAtual && $outroEvento['tipo'] === 'comentario') {
                                $comentariosSimultaneos->push($outroEvento);
                            }
                        }
                        
                        $eventoConsolidado = $evento;
                        if ($comentariosSimultaneos->isNotEmpty()) {
                            $eventoConsolidado['comentarios_consolidados'] = $comentariosSimultaneos;
                        }
                        $eventosConsolidados->push($eventoConsolidado);
                    } elseif (!in_array($evento['tipo'], ['avaliacao_fechamento', 'avaliacao_comentario'])) {
                        // Adiciona outros eventos que não foram consolidados
                        $jaConsolidado = false;
                        foreach($eventosConsolidados as $consolidado) {
                            if ($consolidado['data']->format('Y-m-d H:i:s') === $dataAtual && 
                                (isset($consolidado['comentarios_consolidados']) || isset($consolidado['avaliacao_comentario']))) {
                                $jaConsolidado = true;
                                break;
                            }
                        }
                        
                        if (!$jaConsolidado) {
                            $eventosConsolidados->push($evento);
                        }
                    }
                }
                
                $eventos = $eventosConsolidados;
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
                            <h6><i class="fas fa-check-circle text-success"></i> Chamado Fechado</h6>
                            @break
                        @case('fechado')
                            <h6><i class="fas fa-times-circle text-success"></i> Chamado Fechado</h6>
                            @break
                        @case('avaliacao')
                            <h6><i class="fas fa-star text-warning"></i> Chamado Avaliado</h6>
                            @break
                        @case('avaliacao_comentario')
                            <h6><i class="fas fa-star text-warning"></i> Comentário de Avaliação</h6>
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
                    @elseif($evento['tipo'] == 'avaliacao')
                        <div class="mt-2 p-3 border rounded" style="background-color: #fff9e6; border-color: #ffc107;">
                            <div class="d-flex align-items-center mb-2">
                                @if($chamado->avaliacaoChamado && $chamado->avaliacaoChamado->avaliacao_chamado_imagem)
                                    <div class="me-2" style="width: 32px; height: 32px;">
                                        {!! $chamado->avaliacaoChamado->avaliacao_chamado_imagem !!}
                                    </div>
                                    <strong class="text-warning">{{ $chamado->avaliacaoChamado->avaliacao_chamado_nome }}</strong>
                                @else
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong class="text-warning">Avaliação</strong>
                                @endif
                            </div>
                            @if(isset($evento['avaliacao_comentario']) && $evento['avaliacao_comentario'])
                                <div class="mt-2 p-2 bg-light rounded">
                                    <p class="mb-1 font-italic">"{{ $evento['avaliacao_comentario']['comentario_limpo'] }}"</p>
                                </div>
                            @endif
                            @if(isset($evento['comentarios_extras']) && $evento['comentarios_extras']->isNotEmpty())
                                @foreach($evento['comentarios_extras'] as $comentarioExtra)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <p class="mb-1">{{ $comentarioExtra['comentario']->comentario_chamado_comentario }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @elseif($evento['tipo'] == 'avaliacao_comentario')
                        <div class="mt-2 p-3 border rounded" style="background-color: #fff9e6; border-color: #ffc107;">
                            <div class="d-flex align-items-center mb-2">
                                @if($chamado->avaliacaoChamado && $chamado->avaliacaoChamado->avaliacao_chamado_imagem)
                                    <div class="me-2" style="width: 32px; height: 32px;">
                                        {!! $chamado->avaliacaoChamado->avaliacao_chamado_imagem !!}
                                    </div>
                                    <strong class="text-warning">{{ $chamado->avaliacaoChamado->avaliacao_chamado_nome }}</strong>
                                @else
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong class="text-warning">Avaliação</strong>
                                @endif
                            </div>
                            <div class="mt-2 p-2 bg-light rounded">
                                <p class="mb-1 font-italic">"{{ $evento['comentario_limpo'] }}"</p>
                            </div>
                        </div>
                    @elseif(isset($evento['comentarios_consolidados']) && $evento['comentarios_consolidados']->isNotEmpty())
                        @foreach($evento['comentarios_consolidados'] as $comentarioConsolidado)
                            <div class="mt-2 p-2 bg-light rounded">
                                <p class="mb-1">{{ $comentarioConsolidado['comentario']->comentario_chamado_comentario }}</p>
                                @if($comentarioConsolidado['comentario']->comentario_chamado_anexo)
                                    <div class="mt-2">
                                        <i class="fas fa-paperclip text-muted"></i>
                                        <a href="{{ asset('uploads/chamado/' . $comentarioConsolidado['comentario']->comentario_chamado_anexo) }}" target="_blank" class="text-primary">
                                            {{ $comentarioConsolidado['comentario']->comentario_chamado_anexo }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
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
