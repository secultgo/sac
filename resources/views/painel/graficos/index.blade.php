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
                            <input type="date" id="data_inicio" class="form-control" value="{{ date('Y-m-d', strtotime('-1 month')) }}">
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

function criarGraficos(dados) {
    // Destroi gráficos existentes
    Object.values(charts).forEach(chart => chart.destroy());
    charts = {};
    
    // Gráfico de Status
    const ctxStatus = document.getElementById('grafico-status').getContext('2d');
    charts.status = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: dados.chamados_por_status.map(item => item.status),
            datasets: [{
                data: dados.chamados_por_status.map(item => item.total),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ]
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
    
    // Gráfico de Avaliações
    const ctxAval = document.getElementById('grafico-avaliacoes').getContext('2d');
    charts.avaliacoes = new Chart(ctxAval, {
        type: 'bar',
        data: {
            labels: dados.avaliacoes.map(item => item.avaliacao),
            datasets: [{
                label: 'Quantidade',
                data: dados.avaliacoes.map(item => item.total),
                backgroundColor: [
                    '#FF6384', '#FF9F40', '#FFCD56', '#4BC0C0', '#36A2EB'
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
    
    // Gráfico de Atendentes
    const ctxAtend = document.getElementById('grafico-atendentes').getContext('2d');
    charts.atendentes = new Chart(ctxAtend, {
        type: 'pie',
        data: {
            labels: dados.atendentes.map(item => item.atendente),
            datasets: [{
                data: dados.atendentes.map(item => item.total),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ]
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
}
</script>
@stop
