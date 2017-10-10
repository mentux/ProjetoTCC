@extends('layouts.cozinha_cabecalho')

@section('cozinha')
<h2>Pedido numero  {{$venda->id_venda}}</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Mesa</th>
            <th>Valor</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                2
            </td>
            <td>
                {{$venda->valor_venda}}
            </td>
            <td>
                {{$venda->status}}
            </td>
           
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
                <img src="{{route('imagem.file',$item->produto->imagem_nome)}}" alt="{{$item->produto->imagem_nome}}" style="width:150px;" >
            </td>
            <td>
                <a href="{{route('produto.detalhes', $item->produto->id)}}">
                    {{$item->produto->nome}}
                </a>
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