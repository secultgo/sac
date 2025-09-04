@extends('adminlte::page')

@section('title', 'Gráficos e Relatórios')

@section('content_header')
    <h1>Gráficos e Relatórios</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="data_inicio">Data Início:</label>
                            <input type="date" id="data_inicio" class="form-control" value="{{ $dataInicial }}">
                        </div>
                        <div class="col-md-3">
                            <label for="data_fim">Data Fim:</label>
                            <input type="date" id="data_fim" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        @if(auth()->user()->isSuperAdmin())
                        <div class="col-md-3">
                            <label for="departamento_id">Departamento:</label>
                            <select id="departamento_id" class="form-control">
                                <option value="todos">Todos os Departamentos</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->departamento_id }}">{{ $departamento->departamento_nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btn-atualizar" class="btn btn-primary">
                                <i class="fas fa-sync"></i> Atualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas Dinâmicos -->
    <div class="row mb-3" id="cards-estatisticas">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-total-chamados"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-chamados-fechados"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-chamados-nao-avaliados"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-chamados-pendentes"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-chamados-atendimento"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-body p-2">
                    <div id="card-chamados-abertos"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center d-none">
        <div class="spinner-border" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>

    <!-- Gráficos -->
    <div id="graficos-container" class="row">
        <!-- Chamados por Departamento -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Departamento</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-departamento"></div>
                </div>
            </div>
        </div>

        <!-- Chamados por Status -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Status</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-status"></div>
                </div>
            </div>
        </div>

        <!-- Avaliações dos Chamados -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Avaliações dos Chamados</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-avaliacoes"></div>
                </div>
            </div>
        </div>

        <!-- Evolução Temporal -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Evolução Temporal dos Chamados</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-temporal"></div>
                </div>
            </div>
        </div>

        <!-- Atendentes -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Atendente</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-atendentes"></div>
                </div>
            </div>
        </div>

        <!-- Performance -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Performance (Tempo de Resolução)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Tempo Médio</strong></span>
                                    <span class="info-box-number" id="tempo-medio" style="font-size: 14px;">-- </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-tachometer-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Tempo Mínimo</strong></span>
                                    <span class="info-box-number" id="tempo-minimo" style="font-size: 14px;">-- </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-hourglass-end"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Tempo Máximo</strong></span>
                                    <span class="info-box-number" id="tempo-maximo" style="font-size: 14px;">-- </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .info-box {
        margin-bottom: 10px;
    }
    
    #loading {
        margin: 50px 0;
    }
    
    .card {
        margin-bottom: 20px;
    }
    
    #cards-estatisticas .card {
        border: 1px solid #dee2e6;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.15s ease-in-out;
    }
    
    #cards-estatisticas .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    #cards-estatisticas .card-body {
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Estilos para Performance */
    .tempo-principal {
        font-weight: bold;
        font-size: 14px;
        line-height: 1.2;
        color: #fff;
    }
    
    .tempo-secundario {
        font-size: 11px;
        opacity: 0.8;
        color: #fff;
        margin-top: 2px;
    }
    
    .info-box.bg-gradient-info,
    .info-box.bg-gradient-success,
    .info-box.bg-gradient-warning,
    .info-box.bg-gradient-primary {
        color: white;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease;
    }
    
    .info-box.bg-gradient-info:hover,
    .info-box.bg-gradient-success:hover,
    .info-box.bg-gradient-warning:hover,
    .info-box.bg-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .info-box .info-box-icon {
        border-radius: 8px 0 0 8px;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let charts = {};

// Função para formatar tempo em minutos para dias, horas e minutos
function formatarTempo(minutos) {
    // Garantir que não é negativo ou nulo
    minutos = Math.max(0, minutos || 0);
    
    if (minutos === 0) return '0 min';
    
    const dias = Math.floor(minutos / (24 * 60));
    const horas = Math.floor((minutos % (24 * 60)) / 60);
    const mins = Math.floor(minutos % 60);
    
    let resultado = [];
    
    if (dias > 0) {
        resultado.push(dias + (dias === 1 ? ' dia' : ' dias'));
    }
    if (horas > 0) {
        resultado.push(horas + (horas === 1 ? ' hora' : ' horas'));
    }
    if (mins > 0 || resultado.length === 0) {
        resultado.push(mins + (mins === 1 ? ' min' : ' mins'));
    }
    
    return resultado.join(', ');
}

$(document).ready(function() {
    carregarGraficos();
    
    $('#btn-atualizar').click(function() {
        carregarGraficos();
    });
    
    // Atualização automática dos filtros
    $('#data_inicio, #data_fim, #departamento_id').change(function() {
        carregarGraficos();
    });
});

function carregarGraficos() {
    $('#loading').show();
    $('#graficos-container').hide();
    
    const filtros = {
        data_inicio: $('#data_inicio').val(),
        data_fim: $('#data_fim').val(),
        departamento_id: $('#departamento_id').val()
    };

    $.ajax({
        url: '{{ route("graficos.dados") }}',
        method: 'GET',
        data: filtros,
        dataType: 'json',
        success: function(dados) {
            $('#loading').hide();
            $('#graficos-container').show();
            criarGraficos(dados);
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar dados dos gráficos:', error);
            console.error('Status:', xhr.status);
            console.error('Response:', xhr.responseText);
            
            $('#loading').hide();
            alert('Erro ao carregar dados dos gráficos. Verifique o console para mais detalhes.');
        }
    });
}

function atualizarCards(estatisticas) {
    // Destroi cards existentes
    if (charts.cards) {
        charts.cards.forEach(card => {
            if (card.destroy) {
                card.destroy();
            }
        });
    }
    charts.cards = [];
    
    // Configuração base para os cards
    const cardsConfig = [
        {
            id: 'card-total-chamados',
            title: 'Total de Chamados',
            value: estatisticas.total_chamados || 0,
            color: '#17a2b8'
        },
        {
            id: 'card-chamados-fechados',
            title: 'Chamados Fechados',
            value: estatisticas.chamados_fechados || 0,
            color: '#28a745'
        },
        {
            id: 'card-chamados-nao-avaliados',
            title: 'Não Avaliados',
            value: estatisticas.chamados_nao_avaliados || 0,
            color: '#17a2b8'
        },
        {
            id: 'card-chamados-pendentes',
            title: 'Chamados Pendentes',
            value: estatisticas.chamados_pendentes || 0,
            color: '#FF851B'
        },
        {
            id: 'card-chamados-atendimento',
            title: 'Em Atendimento',
            value: estatisticas.chamados_atendimento || 0,
            color: '#007bff'
        },
        {
            id: 'card-chamados-abertos',
            title: 'Chamados Abertos',
            value: estatisticas.chamados_abertos || 0,
            color: '#dc3545'
        }
    ];
    
    // Criar cards apenas com HTML (sem gráficos)
    cardsConfig.forEach(config => {
        const elemento = document.querySelector(`#${config.id}`);
        elemento.innerHTML = `
            <div class="text-center">
                <h2 style="color: ${config.color}; font-size: 2.5rem; font-weight: bold; margin-bottom: 5px;">
                    ${config.value}
                </h2>
                <p style="color: #666; font-size: 0.9rem; margin: 0;">
                    ${config.title}
                </p>
            </div>
        `;
    });
}

function criarGraficos(dados) {
    // Atualizar cards primeiro
    if (dados.estatisticas) {
        atualizarCards(dados.estatisticas);
    }
    
    // Destroi gráficos ApexCharts existentes
    Object.values(charts).forEach(chart => {
        if (chart && chart.destroy) {
            chart.destroy();
        }
    });
    charts = {};
    
    // Definir cores dos status baseado no sistema (exatamente como no dashboard)
    const coresStatus = {
        'Aberto': '#dc3545',           // badge-danger
        'Em Atendimento': '#ffc107',   // badge-warning
        'Atendimento': '#ffc107',      // badge-warning
        'Fechado': '#28a745',          // badge-success
        'Pendente': '#FF851B',         // bg-orange
        'Não Avaliado': '#17a2b8',     // badge-info
        'Aguardando Usuário': '#6c757d', // badge-secondary
        'Reaberto': '#8B008B',         // bg-purple (roxo escuro para reaberto)
        'Cancelado': '#343a40'         // badge-dark
    };
    
    // 1. Gráfico de Status (Donut)
    const statusOptions = {
        series: dados.chamados_por_status.map(item => parseInt(item.total)),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: dados.chamados_por_status.map(item => item.status),
        colors: dados.chamados_por_status.map(item => coresStatus[item.status] || '#6c757d'),
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    charts.status = new ApexCharts(document.querySelector("#grafico-status"), statusOptions);
    charts.status.render();
    
    // 2. Gráfico de Departamento (Barras Verticais)
    const departamentoOptions = {
        series: [{
            name: 'Chamados',
            data: dados.chamados_por_departamento.map(item => parseInt(item.total))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: dados.chamados_por_departamento.map(item => item.departamento),
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            title: {
                text: 'Quantidade de Chamados'
            }
        },
        colors: ['#36A2EB']
    };
    charts.departamento = new ApexCharts(document.querySelector("#grafico-departamento"), departamentoOptions);
    charts.departamento.render();
    
    // 3. Gráfico Temporal (Linha)
    const temporalOptions = {
        series: [{
            name: 'Chamados Abertos',
            data: dados.evolucao_temporal.map(item => parseInt(item.total))
        }],
        chart: {
            type: 'line',
            height: 200,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#4BC0C0']
        },
        markers: {
            size: 4,
            colors: ['#4BC0C0'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 6
            }
        },
        xaxis: {
            categories: dados.evolucao_temporal.map(item => item.periodo)
        },
        yaxis: {
            title: {
                text: 'Quantidade de Chamados'
            }
        },
        colors: ['#4BC0C0'],
        fill: {
            type: 'solid',
            opacity: 0.1
        }
    };
    charts.temporal = new ApexCharts(document.querySelector("#grafico-temporal"), temporalOptions);
    charts.temporal.render();
    
    // 4. Performance - Melhorado com formatação correta e visual aprimorado
    if (dados.performance) {
        const performance = dados.performance;
        
        // Atualizar textos com formatação correta
        $('#tempo-medio').html(`
            <div class="tempo-principal">${formatarTempo(performance.tempo_medio_minutos)}</div>
            <div class="tempo-secundario">(${performance.tempo_medio_horas}h)</div>
        `);
        
        $('#tempo-minimo').html(`
            <div class="tempo-principal">${formatarTempo(performance.tempo_minimo_minutos)}</div>
            <div class="tempo-secundario">(${performance.tempo_minimo_horas}h)</div>
        `);
        
        $('#tempo-maximo').html(`
            <div class="tempo-principal">${formatarTempo(performance.tempo_maximo_minutos)}</div>
            <div class="tempo-secundario">(${performance.tempo_maximo_horas}h)</div>
        `);
    }
    
    // 5. Gráfico de Avaliações (Barras Verticais)
    const avaliacoesOptions = {
        series: [{
            name: 'Quantidade',
            data: dados.avaliacoes.map(item => parseInt(item.total))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: false,
                columnWidth: '70%',
                distributed: true,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toLocaleString('pt-BR');
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#000']
            }
        },
        xaxis: {
            categories: dados.avaliacoes.map(item => item.avaliacao),
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Quantidade de Avaliações',
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toLocaleString('pt-BR');
                }
            }
        },
        colors: dados.avaliacoes.map(item => {
            switch(item.avaliacao) {
                case 'Ótimo': return '#28a745';     // Verde vibrante
                case 'Bom': return '#20c997';       // Verde-água
                case 'Regular': return '#ffc107';   // Amarelo
                case 'Ruim': return '#fd7e14';      // Laranja
                case 'Péssimo': return '#dc3545';   // Vermelho
                default: return '#6c757d';          // Cinza padrão
            }
        }),
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 5
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toLocaleString('pt-BR') + ' avaliações';
                }
            }
        },
        legend: {
            show: false
        }
    };
    charts.avaliacoes = new ApexCharts(document.querySelector("#grafico-avaliacoes"), avaliacoesOptions);
    charts.avaliacoes.render();
    
    // 6. Gráfico de Atendentes (Barras Horizontais) - já implementado
    if (charts.atendentes) {
        charts.atendentes.destroy();
    }
    
    const atendentesData = (dados.atendentes || []).slice(0, 20);
    
    const atendentesOptions = {
        series: [{
            name: 'Chamados Atendidos',
            data: atendentesData.map(item => parseInt(item.total))
        }],
        chart: {
            type: 'bar',
            height: 800,
            toolbar: {
                show: false
            },
            animations: {
                enabled: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '70%',
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetX: 10,
            style: {
                fontSize: '12px',
                colors: ['#304758']
            }
        },
        xaxis: {
            categories: atendentesData.map(item => item.atendente),
            title: {
                text: 'Número de Chamados'
            }
        },
        yaxis: {
            title: {
                text: 'Atendentes'
            }
        },
        colors: ['#36A2EB'],
        grid: {
            show: true,
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " chamados";
                }
            }
        }
    };
    
    charts.atendentes = new ApexCharts(document.querySelector("#grafico-atendentes"), atendentesOptions);
    charts.atendentes.render();
}
</script>
@stop
