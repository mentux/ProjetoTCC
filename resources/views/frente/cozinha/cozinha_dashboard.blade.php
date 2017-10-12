@extends('layouts.cozinha_cabecalho')

@section('cozinha')
<h2>Cozinha</h3>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a href="{{url('pedidos_pendentes_hoje')}}"><h3 class="text-center">Pendentes(hoje)</h3></a>
                </div>
                <div class="panel-body text-center">
                    <h1 class="text-info">
                        {{$pendente}}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <a href="{{url('pedidos_andamento')}}"><h3 class="text-center">Em Andamento(hoje)</h3></a>
                </div>
                <div class="panel-body text-center">
                    <h1 class="text-info">
                        {{$andamento}}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-3 ">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <a href="{{url('pedidos_pronto')}}"><h3 class="text-center">Prontos(hoje)</h3></a>
                </div>
                <div class="panel-body text-center">
                    <h1 class="text-info">
                        {{$prontos}}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>

@stop