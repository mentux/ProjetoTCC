@extends('layouts.cliente')

@section('conteudo')
<h2>Pedidos - {{$tipoVisao}} - {{$pedidos->count()}} </h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Data</th>
            <th class="text-right">Valor</th>
            <th class="text-right">Status Local</th>
            <th class="text-right">Enviado / Finalizado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
        <tr>
            <td>
                {{$pedido->data_venda->format('d/m/Y')}}
            </td>
            <td>
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
            <td class="text-right text-muted small">
                <a class='btn btn-primary' href="{{route('cliente.pedidos', $pedido->id_venda)}}">Mais detalhes</a>
            </td>
        </tr>
        @empty
        <tr class="info">
            <td colspan="8" >
                Nenhum pedido para o status solicitado !
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
{{$pedidos->render()}}
@stop