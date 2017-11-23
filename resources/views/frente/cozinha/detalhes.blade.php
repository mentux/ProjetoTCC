@extends('layouts.cozinha_cabecalho')

@section('cozinha')
<h2>Pedido numero  {{$venda->id_venda}}</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Data/Hora</th>
            <th>Mesa</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{$venda->data_venda->format('d/m/Y : H:i')}}
            </td>
            <td>
                {{$venda->mesa->numero}}
            </td>
            <td>
                {{$venda->valor_venda}}
            </td>
            @if($venda->status == '1')
            <td class='text-danger'>
                Pendente
            </td>
            @elseif($venda->status == '2')
            <td class='text-info'>
                Em Andamento
            </td>
            @else
            <td class='text-success'>
                Pronto
            </td>
            @endif
        </tr>
    </tbody>
</table>

<h3>Itens  {{$venda->itens->count()}}</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Produto</th>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor Unitário</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venda->itens as $item)
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
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop