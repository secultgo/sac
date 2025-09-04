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
                    <canvas id="grafico-status" height="300"></canvas>
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
                    <canvas id="grafico-departamento" height="300"></canvas>
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
                    <canvas id="grafico-temporal" height="200"></canvas>
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
                    <canvas id="grafico-avaliacoes" height="300"></canvas>
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
                    <canvas id="grafico-atendentes" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas Detalhadas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ranking de Atendentes</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tabela-atendentes">
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Atendente</th>
                                <th>Chamados</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalhes por Status</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tabela-status">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalhes por Departamento</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tabela-departamento">
                        <thead>
                            <tr>
                                <th>Departamento</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    $('#loading').removeClass('d-none');
    $('#graficos-container').hide();
    
    const dados = {
        data_inicio: $('#data_inicio').val(),
        data_fim: $('#data_fim').val(),
        departamento_id: $('#departamento_id').val() || null
    };
    
    $.ajax({
        url: '{{ route("graficos.dados") }}',
        method: 'GET',
        data: dados,
        success: function(response) {
            criarGraficos(response);
            $('#loading').addClass('d-none');
            $('#graficos-container').show();
        },
        error: function() {
            alert('Erro ao carregar os dados dos gráficos');
            $('#loading').addClass('d-none');
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

function preencherTabelas(dados) {
    // Tabela de Atendentes
    let htmlAtendentes = '';
    const totalAtendentes = dados.atendentes.reduce((sum, item) => sum + parseInt(item.total), 0);
    
    dados.atendentes.forEach((item, index) => {
        const percentual = totalAtendentes > 0 ? ((parseInt(item.total) / totalAtendentes) * 100).toFixed(1) : 0;
        htmlAtendentes += `
            <tr>
                <td>${index + 1}º</td>
                <td>${item.atendente}</td>
                <td>${item.total}</td>
                <td>${percentual}%</td>
            </tr>
        `;
    });
    $('#tabela-atendentes tbody').html(htmlAtendentes);
    
    // Tabela de Status
    let htmlStatus = '';
    const totalStatus = dados.chamados_por_status.reduce((sum, item) => sum + parseInt(item.total), 0);
    
    dados.chamados_por_status.forEach(item => {
        const percentual = totalStatus > 0 ? ((parseInt(item.total) / totalStatus) * 100).toFixed(1) : 0;
        htmlStatus += `
            <tr>
                <td>${item.status}</td>
                <td>${item.total}</td>
                <td>${percentual}%</td>
            </tr>
        `;
    });
    $('#tabela-status tbody').html(htmlStatus);
    
    // Tabela de Departamentos
    let htmlDepartamento = '';
    const totalDepartamento = dados.chamados_por_departamento.reduce((sum, item) => sum + parseInt(item.total), 0);
    
    dados.chamados_por_departamento.forEach(item => {
        const percentual = totalDepartamento > 0 ? ((parseInt(item.total) / totalDepartamento) * 100).toFixed(1) : 0;
        htmlDepartamento += `
            <tr>
                <td>${item.departamento}</td>
                <td>${item.total}</td>
                <td>${percentual}%</td>
            </tr>
        `;
    });
    $('#tabela-departamento tbody').html(htmlDepartamento);
}

function criarGraficos(dados) {
    // Atualizar cards primeiro
    if (dados.estatisticas) {
        atualizarCards(dados.estatisticas);
    }
    
    // Destroi gráficos existentes
    Object.values(charts).forEach(chart => chart.destroy());
    charts = {};
    
    // Definir cores dos status baseado no sistema (cores exatas do Bootstrap + customizadas)
    const coresStatus = {
        'Aberto': '#dc3545',              // badge-danger
        'Em Atendimento': '#ffc107',      // badge-warning
        'Atendimento': '#ffc107',         // badge-warning
        'Fechado': '#28a745',             // badge-success
        'Pendente': '#FF851B',            // bg-orange (cor customizada do sistema)
        'Não Avaliado': '#17a2b8',        // badge-info
        'Aguardando Usuário': '#6c757d',  // badge-secondary
        'Aguardando Resposta': '#6c757d', // badge-secondary
        'Reaberto': '#8B008B',            // bg-purple (cor customizada para reaberto)
        'Cancelado': '#343a40'            // badge-dark
    };
    
    // Gráfico de Status
    const ctxStatus = document.getElementById('grafico-status').getContext('2d');
    const coresStatusGrafico = dados.chamados_por_status.map(item => 
        coresStatus[item.status] || '#6c757d'
    );
    
    charts.status = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: dados.chamados_por_status.map(item => item.status),
            datasets: [{
                data: dados.chamados_por_status.map(item => item.total),
                backgroundColor: coresStatusGrafico
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Departamento
    const ctxDept = document.getElementById('grafico-departamento').getContext('2d');
    charts.departamento = new Chart(ctxDept, {
        type: 'bar',
        data: {
            labels: dados.chamados_por_departamento.map(item => item.departamento),
            datasets: [{
                label: 'Quantidade de Chamados',
                data: dados.chamados_por_departamento.map(item => item.total),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Gráfico Temporal
    const ctxTemporal = document.getElementById('grafico-temporal').getContext('2d');
    charts.temporal = new Chart(ctxTemporal, {
        type: 'line',
        data: {
            labels: dados.evolucao_temporal.map(item => item.periodo),
            datasets: [{
                label: 'Chamados Abertos',
                data: dados.evolucao_temporal.map(item => item.total),
                borderColor: '#4BC0C0',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Performance
    $('#tempo-medio').text(dados.performance.tempo_medio + ' horas');
    $('#tempo-minimo').text(dados.performance.tempo_minimo + ' horas');
    $('#tempo-maximo').text(dados.performance.tempo_maximo + ' horas');
    
    // Gráfico de Avaliações com cores do sistema
    const ctxAval = document.getElementById('grafico-avaliacoes').getContext('2d');
    charts.avaliacoes = new Chart(ctxAval, {
        type: 'bar',
        data: {
            labels: dados.avaliacoes.map(item => item.avaliacao),
            datasets: [{
                label: 'Quantidade',
                data: dados.avaliacoes.map(item => item.total),
                backgroundColor: [
                    '#dc3545', '#ffc107', '#6c757d', '#28a745', '#17a2b8'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Gráfico de Atendentes - MODIFICADO para barras HORIZONTAIS ordenadas
    const ctxAtend = document.getElementById('grafico-atendentes').getContext('2d');
    
    // Ordenar atendentes por quantidade (maior para menor)
    const atendentesOrdenados = dados.atendentes.sort((a, b) => b.total - a.total);
    
    charts.atendentes = new Chart(ctxAtend, {
        type: 'bar',
        data: {
            labels: atendentesOrdenados.map(item => item.atendente),
            datasets: [{
                label: 'Chamados Atendidos',
                data: atendentesOrdenados.map(item => item.total),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Isso faz as barras ficarem horizontais (Chart.js v3+)
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Preencher tabelas detalhadas
    preencherTabelas(dados);
}
</script>
@stop
