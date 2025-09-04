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
                                    <option value="{{ $departamento->id }}">{{ $departamento->nome }}</option>
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

    <!-- Cards de Estatísticas -->
    <div class="row mb-3" id="cards-estatisticas">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="total-chamados">0</h3>
                    <p>Total de Chamados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3 id="chamados-fechados">0</h3>
                    <p>Chamados Fechados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-archive"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="chamados-resolvidos">0</h3>
                    <p>Chamados Resolvidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="chamados-pendentes">0</h3>
                    <p>Chamados Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="chamados-atendimento">0</h3>
                    <p>Em Atendimento</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wrench"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="chamados-abertos">0</h3>
                    <p>Chamados Abertos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
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
        <!-- Chamados por Status -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Status</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-status"></div>
                </div>
            </div>
        </div>

        <!-- Chamados por Departamento -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Departamento</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-departamento"></div>
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

        <!-- Performance -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performance (Tempo de Resolução)</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tempo Médio</span>
                            <span class="info-box-number" id="tempo-medio">-- horas</span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-tachometer-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tempo Mínimo</span>
                            <span class="info-box-number" id="tempo-minimo">-- horas</span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-end"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tempo Máximo</span>
                            <span class="info-box-number" id="tempo-maximo">-- horas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avaliações -->
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

        <!-- Atendentes -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chamados por Atendente</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-atendentes"></div>
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
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let charts = {};

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
    $('#total-chamados').text(estatisticas.total_chamados || 0);
    $('#chamados-fechados').text(estatisticas.chamados_fechados || 0);
    $('#chamados-resolvidos').text(estatisticas.chamados_resolvidos || 0);
    $('#chamados-pendentes').text(estatisticas.chamados_pendentes || 0);
    $('#chamados-atendimento').text(estatisticas.chamados_atendimento || 0);
    $('#chamados-abertos').text(estatisticas.chamados_abertos || 0);
}

function criarGraficos(dados) {
    // Atualizar cards primeiro
    if (dados.estatisticas) {
        atualizarCards(dados.estatisticas);
    }
    
    // Destroi gráficos ApexCharts existentes
    Object.values(charts).forEach(chart => {
        if (chart.destroy) {
            chart.destroy();
        }
    });
    charts = {};
    
    // Definir cores dos status baseado no sistema
    const coresStatus = {
        'Aberto': '#dc3545',
        'Em Atendimento': '#ffc107',
        'Atendimento': '#ffc107',
        'Fechado': '#28a745',
        'Pendente': '#FF851B',
        'Não Avaliado': '#17a2b8',
        'Aguardando Usuário': '#6c757d',
        'Aguardando Resposta': '#6c757d',
        'Reaberto': '#8B008B',
        'Cancelado': '#343a40'
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
            width: 3
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
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        }
    };
    charts.temporal = new ApexCharts(document.querySelector("#grafico-temporal"), temporalOptions);
    charts.temporal.render();
    
    // 4. Performance (manter como está)
    $('#tempo-medio').text(dados.performance.tempo_medio + ' horas');
    $('#tempo-minimo').text(dados.performance.tempo_minimo + ' horas');
    $('#tempo-maximo').text(dados.performance.tempo_maximo + ' horas');
    
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
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: true
        },
        xaxis: {
            categories: dados.avaliacoes.map(item => item.avaliacao)
        },
        yaxis: {
            title: {
                text: 'Quantidade'
            }
        },
        colors: ['#dc3545', '#ffc107', '#6c757d', '#28a745', '#17a2b8']
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
