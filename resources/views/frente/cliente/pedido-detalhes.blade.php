@extends('layouts.cliente')

@section('conteudo')
<h2>Pedido - {{$pedido->id_venda}} </h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Data</th>
            <th class="text-right">Valor</th>
            <th class="text-right">Status Local</th>
            <th class="text-right">Enviado / Finalizado</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{$pedido->data_venda->format('d/m/Y')}}
            </td>
            <td class='text-center'>
                {{number_format($pedido->valor_venda, 2, ',', '.')}}
            </td>
            <td class="text-right small">
                @if ($pedido->pago)
                    @if ($pedido->enviado)
                        <span class="text-success">FINALIZADO</span>
                    @else
                        <span class="text-warning">PRONTO PARA ENVIAR</span>
                    @endif
                @else
                    <b class="text-warning">Aguardando atualização de status de pagamento</b>
                @endif
            </td>
            <td class="text-right small">
                {!! $pedido->enviado 
                    ? '<span class="text-success">ENVIADO / FINALIZADO</span>' 
                    : '<b class="text-warning">Aguardando atualização de status de pagamento</b>'
                !!}
            </td>
        </tr>
    </tbody>
</table>

<h3>Itens - {{$pedido->itens->count()}}</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Produto</th>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor Unitário</th>
            <th class="text-right">Total do item</th>
            <th class="text-right">Avaliar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedido->itens as $item)
        <tr>
            <td>
                <img src="{{asset('uploads/'.$item->produto->imagem_nome)}}" alt="{{$item->produto->imagem_nome}}" style="width:150px;" >
            </td>
            <td>
                {{$item->produto->nome}}
            </td>
            <td class="text-right">
                {{$item->qtde}}
            </td>
            <td class="text-right">
                {{number_format($item->produto->preco_venda, 2, ',', '.')}}
            </td>
            <td class="text-right">
                {{number_format($item->produto->preco_venda * $item->qtde, 2, ',', '.')}}
            </td>
            <td class="col-sm-2 text-right">
                @if($item->avaliado)
                    {{ number_format($item->produto->avaliacao_total / $item->produto->avaliacao_qtde,2) }}
                @elseif($pedido->pago == 1 AND $pedido->enviado == 1 )
                    {{ Form::open (['route' => ['cliente.avaliar', $item->id]]) }}
                        {!! Form::selectRange('avaliacao', 1, 10, null, array('class' => 'form-control field')) !!}</br>
                        {{ Form::submit('Avaliar', ['class'=>'btn btn-primary btn-sm col-sm-12']) }}
                    {{ Form::close() }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop