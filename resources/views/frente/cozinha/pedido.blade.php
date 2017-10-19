@extends('layouts.cozinha_cabecalho')

@section('cozinha')
	@if((Route::getCurrentRoute()->getPath()) == 'pedidos_pendentes')
    <h2>Pedidos Pendentes </h2>
    <a class='btn btn-primary' href="{{url('pedidos_pendentes_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_pendentes')}}">Todos</a>

    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_andamento')
    <h2>Pedidos em Andamento</h2>
    <a class='btn btn-primary' href="{{url('pedidos_andamento_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_andamento')}}">Todos</a>

    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_andamento_hoje')
    <h2>Pedidos em Andamento Hoje</h2>
    <a class='btn btn-primary' href="{{url('pedidos_andamento_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_andamento')}}">Todos</a>

    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_pendentes_hoje')
    <h2>Pedidos Pendentes Hoje</h2>
    <a class='btn btn-primary' href="{{url('pedidos_pendentes_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_pendentes')}}">Todos</a>


    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_pronto_hoje')
    <h2>Pedidos Prontos Hoje</h2>
    <a class='btn btn-primary' href="{{url('pedidos_pronto_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_pronto')}}">Todos</a>

    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_pronto')
    <h2>Pedidos Prontos</h2>
    <a class='btn btn-primary' href="{{url('pedidos_pronto_hoje')}}">Hoje</a>
    <a class='btn btn-primary' href="{{url('pedidos_pronto')}}">Todos</a>

    @endif

<table class="table table-striped">
    <thead>
        <tr>
            <th>Mesa</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($venda as $pedido)
        <tr>   
            <td>
                {{$pedido->mesa->numero}}
            </td>
            @if($pedido->status === 1)
            <td>
                Pendente
            </td>
            @elseif($pedido->status === 2)
            <td>
                Em andamento
            </td>
            @else
            <td>
                Pronto
            </td>
            @endif
            <td class="text-left small">
             <a href="{{route('cozinha.detalhes',$pedido->id_venda)}}" class="btn btn-primary" >Mais detalhes</a>
            </td>
            @if($pedido->status == 1)
            <td class="text-left small">
                {{ Form::open (['route' => ['status_pendente', $pedido->id_venda], 'method' => 'PUT']) }}
                        {{ Form::submit('Preparar', ['class'=>'btn btn-warning']) }}
                {{ Form::close() }}
            </td>
            @elseif($pedido->status == 2)
            <td class='text-left small'>
            {{ Form::open (['route' => ['status_pronto', $pedido->id_venda], 'method' => 'PUT']) }}
                        {{ Form::submit('Finalizar', ['class'=>'btn btn-warning']) }}
                {{ Form::close() }}
            </td>
            @else
            <td class="text-left small">
                
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
{{ $venda->render() }}
@stop