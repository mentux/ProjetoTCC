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
            @if($pedido->user_id != '')
            <th style="color:#006400;">Cadastrado</th>
            @else
            <th style="color: #8B0000;">Não Cadastrado</th>
            @endif
            
            <th class="text-right">Status</th>
            @if($pedido->pagseguro_transaction_id != '')
            <th></th>
            <th class="text-right">Pagseguro</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{$pedido->mesa->numero}}
            </td>
            <td>
                {{$pedido->created_at->format('d/m/Y - H:i:s')}}
            </td>
            <td>
                
            </td>
            <td class='text-right text-success'>
               R$: {{number_format($pedido->valor_venda, 2, ',', '.')}}
            </td>
            <td></td>
            @if($pedido->user_id != '')
            <td  value ="1" style="color:#006400;" class="text-center sim">
               Sim
            </td>
            @else
            <td value ="2" style="color: #8B0000;" class="nao">
                Não
            </td>
            @endif
            <td class="text-right small">
                {!! $pedido->enviado 
                    ? '<span class="text-success">PAGO / FINALIZADO</span>' 
                    : '<b class="text-warning">Aguardando Atualização de Status de Pagamento</b>'
                !!}
            </td>
            <td class="text-right">
                {{$pedido->status_pagamento}}
            </td>
            @if($pedido->pagseguro_transaction_id != '')
            <td class="text-right">
                {{$pedido->pagseguro_transaction_id}}
            </td>
            @endif
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
@if($pedido->entrada == '' AND $pedido->troco == '')
<div class="col-md-6">
    <p>Formas de pagamento:</p>
    <div class="form-group">
        <label class="radio-inline">
            <input checked  value="1" type="radio" name="pagamento">Dinheiro
        </label>
        <label class="radio-inline">
            <input value="2" type="radio" name="pagamento">Cartão
        </label>
        <p class="mensagem_error">{{$errors->first('servico',':message')}}</p>
    </div>
</div>
@endif
<div id='pag_dinheiro'>
    <table class="table table-striped">
    <thead>
        <tr>
            <th class="text-left">Entrada</th>
            <th>Total</th>
            @if($pedido->pago =='' and $pedido->user_id=='')
                <th style="margin-left: 30px;">Troco</th>
            @endif
            @if($pedido->user_id!='' and $pedido->pago == 1)
                <th style="margin-left: 30px;">Troco</th>
                <th class="text-center">Desconto</th>
            @elseif($pedido->user_id!='' and $pedido->pago =='')
                <th></th>
                <th class="text-center">Desconto</th>
                <th style="margin-left: 30px;">Troco</th>
            @elseif($pedido->user_id=='' and $pedido->pagseguro_transaction_id != '')
                <th>Troco</th>
                <th class="text-center">Desconto</th>
                <th class="text-center">Novo Total</th>
                <th class="text-center">Novo Troco</th>
            @elseif($pedido->user_id =='' and $pedido->pago ==1)
            <th>Troco</th>
            @endif
            @if($pedido->user_id =='' and $pedido->pago=='')
                <th></th>
                <th></th>
                <th></th>
            @elseif($pedido->user_id !='' and $pedido->pago==1)
                <th class="text-center ">Novo Total</th>
                <th class="text-center ">Novo Troco</th>
            @endif
                <th></th>
                <th class="text-center hidden novo_d">Desconto</th>
                <th class="text-center hidden novo_t">Novo Total</th>
                <th class="text-center hidden novo_t-d">Novo Troco</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @if($pedido->entrada == '' AND $pedido->troco == '')
                <td>
                    <input style="width:180px;" type='text' autocomplete="off" name='entrada' value="" class='form-control entrada_valor' onkeyup="somenteNumeros(this);" placeholder="Digite o Valor de Entrada">
                </td>
            @else
                <td>{{$pedido->entrada}}</td>
            @endif
            @if($pedido->entrada == '' AND $pedido->troco == '')
                <td class='total'>{{$pedido->valor_venda}}</td>
                @if($pedido->user_id!='')
                    <td id="desconto"><input style="width:180px;" type='text' autocomplete="off" name='desconto' value="" class='form-control desconto' onkeyup="somenteNumeros(this);" placeholder="Desconto" disabled="on"></td>
                    
                    <td class="desconto"><input type="button" value="Desconto" class="btn btn-info desconto_total" name="desconto" disabled="on"></input></td>
                @endif
                <td id='troco'></td>
                <td><button disabled="on" class='btn btn-success confirmar'>Confirmar</button></td>
            @else
                <td>{{number_format($pedido->valor_venda, 2, ',', '.')}}</td>
                <td>{{$pedido->troco}}</td>
                <td class="text-center">{{$pedido->desconto}}</td>
                <td class="text-center">{{$pedido->total_novo}}</td>
                <td class="text-center">{{$pedido->troco_novo}}</td>
            @endif
                <th class="novo_total_desc hidden text-center">Desconto</th>
                <th class="novo_total hidden text-center">Novo Total</th>
                <th class="novo_desc hidden text-center">Novo Troco</th>
        </tr>
    </tbody>
</table>
</div>
<div id='pag_cartao'>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Total</th>
                <th class="col-md-3 col-sm-4 col-xs-5"></th>
            
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$pedido->valor_venda}}</td>
                @if(isset($pagseguro))
                
                <td><a href="{{$pagseguro['info']->getLink()}}" class="btn btn-success pull-right">
               
                Pagar com PagSeguro
                </a></td>
                @else
                <td>erro pagseguro
                </td>
                @endif
            </tr>
        </tbody>
    </table>
</div>
@stop
<!-- Troco Automático-->
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script type="text/javascript">
// Funço onde excluir
function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
          campo.value = "";
        }
    }
$(document).ready(function (somenteNumeros) {
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
                if((troco_retorno).toFixed(2).length < 0){
                    $('.confirmar').prop('disabled', false);
                }
                if((troco_retorno.toFixed(2))>0){
                    $('#troco').text(troco_retorno.toFixed(2));
                    $('.confirmar').prop('disabled', false);
                }
                if ((troco_retorno.toFixed(2))==0) {
                    //console.log(troco_retorno);
                    $('#troco').show().text('R$:'+troco_retorno);
                    $('.confirmar').prop('disabled', false);
                }
                if ((troco_retorno.toFixed(2))<0) {
                    $('#troco').show().text('Valor Inferior ao Total');
                    $('.confirmar').prop('disabled', true);
                }
                console.log($('#troco').text()!='');
                if($('#troco').text()!=''){
                    $('.desconto').prop('disabled',false);
                    $('.desconto_total').prop('disabled',false);
                }
                if($('#troco').text()=='Valor Inferior ao Total'){
                    $('.desconto').prop('disabled',true);
                    $('.desconto_total').prop('disabled',true);
                }
            },
        2000);
    });
});
</script>
<script type="text/javascript">
$(document).ready(function () {
    $(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token':$('input[name="_token"]').val()
            }
        });
        $(".desconto_total").on('click',function(){
            if($('#troco').text()!=''){
                $('.desconto').prop('disabled',false);
            }
            else{
                $('.desconto').prop('disabled',true);
            }
            var val_entrada = $('.desconto').val();
            var id = "{{$pedido->id_venda}}";
            var desconto = $('.desconto').val();
            var troco = $('#troco').text();
            $.ajax({
                type: "GET",
                url: '{{route("desconto")}}'+'/'+id,
                data: {val_entrada:val_entrada, desconto:desconto, troco:troco},
                success: function(desconto) {
                    //console.log(desconto[0].desconto);
                    //console.log(desconto[0].total);
                    console.log(desconto[0].novo_desc);
                    $('.novo_total').text(desconto[0].total);
                    $('.novo_desc').text(desconto[0].novo_desc);
                    $('.novo_total_desc').text(desconto[0].desconto);

                    $('.novo_t').removeClass('hidden');
                    $('.novo_d').removeClass('hidden');
                    $('.novo_t-d').removeClass('hidden');

                    $('.novo_total_desc').removeClass('hidden');
                    $('.novo_total').removeClass('hidden');
                    $('.novo_desc').removeClass('hidden');
                },
            });
        });
    });
$(".confirmar").click('change',function() {
        var cad_sim = $('.sim').attr('value');
        var cad_nao = $('.nao').attr('value');
        ///*********************///
        var desconto = $('.novo_total_desc').text();
        var total_n = $('.novo_total').text();
        var troco_n = $('.novo_desc').text();
        console.log(desconto);
        var id_pedido = '{{$pedido->id_venda}}';
        if($('.troco').text() == "R$:0"){
            var troco = $('#troco').text('0');   
        }
        var troco = $('#troco').text();
        var entrada = $('.entrada_valor').val();
        console.log(troco);
        window.location.href = 
        '{{route("troco.salvar")}}'+'/'+id_pedido+'/'+troco+'/'+entrada+'/'+desconto+'/'+total_n+'/'+troco_n+'/';    
    });
});
</script>

<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#pag_cartao').hide();
   $('input[name="pagamento"]').click(function () {
    if($('input[name="pagamento"]:checked').val() == '1') {
        forma_pagamento_dinheiro()
    }else{
       forma_pagamento_cartao() 
    }
    });

});
function forma_pagamento_dinheiro(){

    if($('input[name="pagamento"]:checked').val() == '1') {
        $('#pag_dinheiro').show();
        $('#pag_cartao').hide();
    }
    else {
        $('#pag_dinheiro').hide();
        $('#pag_cartao').show();
    }
}

function forma_pagamento_cartao(){
    if($('input[name="pagamento"]:checked').val() == '2') {
        $('#pag_cartao').show();
        $('#pag_dinheiro').hide();
    }
    else {
        $('#pag_dinheiro').show();
        $('#pag_cartao').hide();
    }
}
</script>