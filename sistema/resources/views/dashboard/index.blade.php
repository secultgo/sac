@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>Chamados</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="" class="small-box bg-info" style="text-decoration: none;">
                <div class="inner">
                    <h2>Novo Chamado <i class="icon fas fa-plus"></i></h2>
                </div>
        
            </a>
        </div> <!-- Fim da primeira coluna -->

        <div class="col-lg-3 col-6">
            <!-- Segunda coluna (pode adicionar conteúdo aqui) -->
        </div>
    </div> <!-- Fim da row -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .small-box {
            min-height: 150px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: 0.25rem;
            transition: transform 0.2s ease-in-out;
        }

        .small-box:hover {
            transform: translateY(-5px);
        }

        .small-box .inner {
            flex: 1;
            padding: 20px;
            text-align: left;
        }

        .small-box .icon {
            padding: 20px;
            font-size: 50px;
            color: rgba(0,0,0,.15);
        }

        .small-box h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
            white-space: nowrap;
            padding: 0;
        }
    </style>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

