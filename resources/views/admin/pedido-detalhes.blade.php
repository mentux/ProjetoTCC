@extends('layouts.admin')

@section('conteudo')
<h2>Pedido - {{$pedido->id_venda}} </h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Mesa</th>
            <th>Data</th>
            <th class="text-right"></th>
            <th class="text-right">Valor</th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right">Status</th>
            <th class="text-right"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{$pedido->mesa->numero}}
            </td>
            <td>
                {{$pedido->data_venda->format('d/m/Y : H:i')}}
            </td>
            <td>
                
            </td>
            <td class='text-right text-success'>
               R$: {{number_format($pedido->valor_venda, 2, ',', '.')}}
            </td>
            <td class="text-right">
                {{$pedido->metodo_pagamento}}
            </td>
            <td class="text-right">
                {{$pedido->status_pagamento}}
            </td>
            <td class="text-right small">
                {!! $pedido->enviado 
                    ? '<span class="text-success">PAGO / FINALIZADO</span>' 
                    : '<b class="text-warning">Aguardando atualização de status de pagamento</b>'
                !!}
            </td>
            <td class="text-right text-muted small">
                
            </td>
        </tr>
    </tbody>
</table>

<h3>Itens - {{$pedido->itens->count()}}</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor Unitário</th>
            <th class="text-right">Total do item</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedido->itens as $item)
        <tr>
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
        </tr>
        @endforeach
    </tbody>
</table>
<h3>Caixa</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Entrada</th>
            <th>Total</th>
            <th style="margin-left: 30px;" class="col-md-3 col-sm-4 col-xs-5">Troco</th>
        
        </tr>
    </thead>
    <tbody>
        <tr>
            @if($pedido->entrada == '' AND $pedido->troco == '')
                <td>
                    <input style="width:180px;" type='text' name='entrada' value="" class='form-control entrada_valor'>
                </td>
            @else
                <td>{{$pedido->entrada}}</td>
            @endif
            @if($pedido->entrada == '' AND $pedido->troco == '')
                <td class='total'>{{$pedido->valor_venda}}</td>
                <td class='troco' ></td>
                <td><button class='btn btn-success confirmar' >Confirmar</button>
            @else
                <td>{{number_format($pedido->valor_venda, 2, ',', '.')}}</td>
                <td>{{$pedido->troco}}</td>
            @endif
        </tr>
    </tbody>
</table>
@stop
<!-- Troco Automático-->
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".entrada_valor").keyup('change', function(event) {
        var data = $(this).val();
        if(data<0){
            $(data).text('0,00');
        }
        var data=$(this).val().replace(",",".");
        var troco = $('.total').on().text();
        troco_retorno = data-troco;
        setTimeout(
            function(){
                if((troco_retorno.toFixed(2))>0){
                    $('.troco').text(troco_retorno.toFixed(2));
                }
                if ((troco_retorno.toFixed(2))==0) {
                    console.log($('.troco').text(0));
                }
                if ((troco_retorno.toFixed(2))<0) {
                    $('.troco').show(5000).text('Valor de Entrada é Inválido');
                    $('.troco').hide(1000).text(0);
                }
            },
        2000);
    });
});
</script>


<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".confirmar").click(function() {
        var id_pedido = '{{$pedido->id_venda}}';
        var troco = $('.troco').text();
        var entrada = $('.entrada_valor').val();
        window.location.href =  '{{route("troco.salvar")}}'+'/'+id_pedido+'/'+troco+'/'+entrada;    
    });
});
</script>
<!-- Fiz um funcao de click no botao de confirmar onde ele chama a rota pra salvar o troco e a entrada no banco,chamo a rota,passo por parametro o id do pedido pro controller achar no banco o pedido,pego o troco e a entrada,dou um save la e salvo tudo no banco e ainda fiz uma validacao no formulario do caixa pra ele nao mandar denovo,caso ja tenha sido setado o troco e a entrada,só mostro e escondo o formulario,não é a melhor forma de fazer até pq ta faltando as validações na parte do controller mas ta funfando,teria que pensar em uma forma melhor ou deixar assim e validar tudo no controller,vemos isso mais tarde,flwz aí meu to indo capotar aqui /diegoo/ -->
