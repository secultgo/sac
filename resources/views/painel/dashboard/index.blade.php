@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard Painel de Controle</h1>
@stop

@section('content')
<style>
.descricao-limitada {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3em;
  max-height: 3.5em; /* 1.3em * 2 linhas */
  max-width: 400px;  /* largura fixa */
  word-break: break-word; /* para evitar overflow */
}
.bg-orange {
    background-color: #FF851B !important; /* laranja vibrante */
    color: white !important; /* texto branco */
}

.bg-orange .inner,
.bg-orange h3,
.bg-orange p {
    color: white !important; /* garantir texto branco */
}



.bg-orange .small-box-footer {
    color: white !important; /* texto do footer branco */
}

</style>

<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>4551</h3>
                <p>Total de Chamados</p>
            </div>
            <div class="icon"><i class="fas fa-list"></i></div>
            <a href="#" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>4529</h3>
                <p>Chamados Fechados</p>
            </div>
            <div class="icon"><i class="fas fa-check"></i></div>
            <a href="#" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-orange">
        <div class="inner">
            <h3>3</h3>
            <p>Chamados Pendentes</p>
        </div>
        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
        <a href="#" class="small-box-footer">
            Mais detalhes <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>

    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>19</h3>
                <p>Chamados Abertos</p>
            </div>
            <div class="icon"><i class="fas fa-folder-open"></i></div>
            <a href="#" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Tabela de Chamados -->
<div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center flex-wrap">
    <h3 class="card-title mb-2 mb-md-0">Chamados</h3>

    <div class="d-flex flex-wrap justify-content-start">
        <a href="?status=1" class="btn btn-sm btn-danger rounded-pill px-3 mr-2 mb-2">
            Abertos <span class="badge badge-light ml-1">19</span>
        </a>
        <a href="?status=2" class="btn btn-sm btn-warning rounded-pill px-3 mr-2 mb-2">
            Atendimento <span class="badge badge-light ml-1">XX</span>
        </a>
        <a href="?status=3" class="btn btn-sm btn-success rounded-pill px-3 mr-2 mb-2">
            Fechados <span class="badge badge-light ml-1">4529</span>
        </a>
        <a href="?status=4" class="btn btn-sm bg-orange text-white rounded-pill px-3 mr-2 mb-2">
            Pendentes <span class="badge badge-light ml-1">3</span>
        </a>
        <a href="?status=5" class="btn btn-sm btn-info rounded-pill px-3 mr-2 mb-2">
            Resolvidos <span class="badge badge-light ml-1">XX</span>
        </a>
        <a href="?status=6" class="btn btn-sm btn-secondary rounded-pill px-3 mr-2 mb-2">
            Aguardando Usuário <span class="badge badge-light ml-1">XX</span>
        </a>
    </div>
</div>


    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Solicitante</th>
                    <th>Lotação</th>
                    <th>Local</th>
                    <th>Telefone</th>
                    <th>Responsável</th>
                    <th>Data de Criação</th>
                    <th>Data Atendimento</th>
                    <th>Status</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>13843</td>
                    <td class="descricao-limitada">
                        Boa tarde. Gostaria de solicitar ao Luís e ao João, uma aplicação para facilitar a pesquisa no acervo fonográfico. Obrigada!
                    </td>
                    <td>Gisele Gomes Garcia</td>
                    <td>Gerência de Museus, Bibliotecas, Instituto do Livro e Arquivo Histórico</td>
                    <td>CENTRO CULTURAL MARIETTA TELLES MACHADO</td>
                    <td>62 99121-8989</td>
                    <td>Luis Felipe Pinheiro Peres de Santana</td>
                    <td>10/06/2025 14:35:32</td>
                    <td>11/06/2025 08:25:33</td>
                    <td><span class="badge badge-warning">Atendimento</span></td>
                    <td class="text-right">
                        <div class="d-flex flex-wrap justify-content-end">
                            <!-- Linha 1: 4 botões -->
                            <div class="w-100 mb-1 d-flex justify-content-end flex-wrap">
                                <a href="#" class="btn btn-sm btn-primary mr-1 mb-1" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-secondary mr-1 mb-1" title="Comentário">
                                    <i class="fas fa-comment-dots"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-warning mr-1 mb-1" title="Devolver ao Usuário">
                                    <i class="fas fa-undo"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-info mr-1 mb-1" title="Pendência">
                                    <i class="fas fa-hourglass-half"></i>
                                </a>
                            </div>
                            <!-- Linha 2: 3 botões -->
                            <div class="w-100 d-flex justify-content-end flex-wrap">
                                <a href="#" class="btn btn-sm btn-dark mr-1 mb-1" title="Transferir de Departamento">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-success mr-1 mb-1" title="Alterar Responsável">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger mr-1 mb-1" title="Resolver">
                                    <i class="fas fa-check"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
console.log('Dashboard carregado');

// Verificar se há notificação de chamado criado
@if(session('chamado_id') && session('posicao_fila'))
    Swal.fire({
        title: 'Chamado Criado com Sucesso!',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p><strong>Seu chamado foi adicionado à fila de espera</strong></p>
                <p>Todas as atualizações sobre o andamento do seu chamado serão registradas diretamente no chamado no SAC.</p>
                <hr>
                <p><strong>Sua posição na fila de atendimento:</strong> 
                   <span style="color: #007bff; font-size: 18px; font-weight: bold;">{{ session('posicao_fila') }}</span>
                </p>
                <p><strong>Número do chamado:</strong> #{{ session('chamado_id') }}</p>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#28a745',
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: '500px'
    });
@endif
</script>
@stop
