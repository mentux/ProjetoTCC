@extends('layouts.cozinha_cabecalho')

@section('cozinha')

<div style="padding-top: 30px;" class='container'>
    <div class='row'>
        <!-- Listagem pedido Pendentes -->
        <div class="col-sm-4 col-xs-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="text-center">Pendente</h3>
                </div>
                <div class="panel-body">
                    @if(count($pendente) == 0)
                    <p class='text-danger text-center txt_pendente'><strong>Nenhum pedido pendente no momento</strong></p>
                    @else
                    <table style="display: block !important;" class="table table-responsive novos_pendente">
                        <thead>
                            <th>Pedido</th>
                            <th>Mesa</th>
                            <th></th>
                            <th></th>
                        </thead>
                        @foreach($pendente as $p)
                        <tbody class="pend">
                            <tr class="{{$p->id_venda}}">
                                <td>{{$p->id_venda}}</td>
                                <td>{{$p->mesa->numero}}</td>
                                <td><button class='btn btn-primary btn-xs detalhes' value="{{$p->id_venda}}" data-toggle="modal" data-target="#myModal" >Detalhes</button></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <!-- Listagem pedidos em Andamento -->
        <div class="col-sm-4 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">Em Andamento</h3>
                </div>  
                <div class="panel-body andamen">
                    @if(count($andamento) == 0)
                    <p class='text-info text-center txt_andamen'><strong>Nenhum pedido em andamento  no momento</strong></p>
                    @else
                    <table style="display: block !important;" class="table table-responsive">
                        <thead>
                            <th>Pedido</th>
                            <th>Mesa</th>
                            <th></th>
                            <th></th>
                        </thead>
                        @foreach($andamento as $a)
                        <tbody class="and">
                            <tr class="{{$a->id_venda}}">
                                <td>{{$a->id_venda}}</td>
                                <td>{{$a->mesa->numero}}</td>
                                <td><button class='btn btn-primary btn-xs detalhes' value="{{$a->id_venda}}" data-toggle="modal" data-target="#myModal" >Detalhes</button></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <!-- Listagem de pedido Prontos -->
        <div class="col-sm-4 col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="text-center">Pronto</h3>
                </div>
                <div class="panel-body text-center pronto_tab">
                    @if(count($prontos) == 0)
                    <p class='text-success text-center pronto_texto'><strong>Nenhum pronto no momento</strong></p>
                    @else
                    <table style="display: block !important;" class="table table-responsive">
                        <thead>
                            <th>Pedido</th>
                            <th><strong>Mesa</strong></th>
                            <th></th>
                        </thead>
                        @foreach($prontos as $pron)
                        <tbody class='pron'>
                            <td>{{$pron->id_venda}}</td>
                            <td>{{$pron->mesa->numero}}</td>
                            <td><button class='btn btn-primary btn-xs detalhes' value="{{$pron->id_venda}}" data-toggle="modal" data-target="#myModal" >Detalhes</button></td>
                        </tbody>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal do mais detalhes do pedido -->
<div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Mesa</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class='cabecalho'>
                   
                </tbody>
            </table>
        </div>
        <div class='modal-itens'>
            <table class="table table-striped teste">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody class='itens'>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default  pull-right" data-dismiss="modal" onclick="voltar_timer()" >Fechar</button>
        </div>
        </div>
      </div>
    </div>
</div>
<!--Ajax Modal -->
<!-- Verificação do tr no <tbody>, se não houver filhos(<tr>,<td>) então para a requesição ajax-->
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script src="{{asset('bootstrap/js/jquery-ui.js')}}"></script>
<script type="text/javascript">
$(function() {
    $.ajaxSetup({
        headers:{
            'X-CSRF-Token':$('input[name="_token"]').val()
        }
    });
        $('.detalhes').click(function(){

            var id = $(this).attr('value');
            if($('.itens').empty('tr:tabela_item')){
                $.ajax().abort();
            }
            if($('.cabecalho_mesa').empty('tr:tabela_item')){
                $.ajax().abort();
            }
            $.ajax({
                type: "GET",
                url: '{{route("pedido.pendentes")}}'+'/'+id,
                data: {},
                success: function(itens){
                //var param = itens;
                var tabela = $('.tabela_item').find();
                var status_cabecalho = itens[0].cabecalho[0].venda_status;
                var botao_classe = '';

                if(status_cabecalho == 1){
                    status_cabecalho = 'Pendente';
                    botao_classe = 'btn btn-danger pendente';
                }
                if(status_cabecalho == 2){
                    status_cabecalho = 'Em Andamento';
                    botao_classe = 'btn btn-info andamento';
                }
                if(status_cabecalho == 3){
                    status_cabecalho = 'Pronto';
                    botao_classe = 'btn btn-success pronto';
                }
                $('.cabecalho').append("<tr class='cabecalho_mesa'>" + "<td class='data'>"+ itens[0].data +"</td>" +"<td>"+ itens[0].cabecalho[0].numero+"</td>"+"<td>R$"+ itens[0].cabecalho[0].valor_venda+"</td>" + "<td>" + "<button value='"+ itens[0].cabecalho[0].id_venda +"' class='"+botao_classe+"'>" + status_cabecalho + "</button>" + "</td>" + "</tr>");
                // fim
                $.each(itens[0]['itens'],function(key, value){
                
                $('.itens').append("<tr class='tabela_item'>" + "<td>" + "<img style='width: 50px;' src='/uploads/"+ value.imagem_nome +"' data-lightbox='roadtrip'/>" + "</td>" +"<td>" + value.nome +  "</td>" + "<td>" + value.qtde + "</td>" + "</tr>");
                });
                $(function(){
                        $.ajaxSetup({
                            headers:{
                                'X-CSRF-Token':$('input[name="_token"]').val()
                            }
                        });
                            $('.pendente').on("click",function(){
                                var id = $(this).attr('value');
                                $.ajax({
                                    type: "GET",
                                    url: '{{route("status_muda_pendente")}}'+'/'+id,
                                    data: {},
                                    success: function(status) {
                                        var status_cabecalho = itens[0].cabecalho[0].venda_status;
                                        var botao_classe = '';

                                        if(status == 2){
                                            var tr = $('.pend').find('.'+id);
                                            $('.pendente').attr("class",'btn btn-info andamento');
                                            $('.andamento').text("Em Andamento");
                                            if($(".and").size('')==0){
                                                $(".andamen").append("<table style='display: block !important;' class='table table-responsive'>" + "<thead>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" +''+ "</th>" + "</thead>" + "<tbody class='and'>" + "</tbody" + "</table>");
                                                $(".and").append(tr);
                                                $(".txt_andamen").remove();
                                                $(".pend").find('.'+id).remove();
                                            // Caso já contenha um Pedido, somente adiciona-o na tabela
                                            }else{
                                                $(".and").last().append(tr);
                                            }
                                        } 
                                    },
                                });
                                
                            });
                });
                    /// End Ajax Button Pending to Progress
                $(function(){
                    $.ajaxSetup({
                        headers:{
                            'X-CSRF-Token':$('input[name="_token"]').val()
                        }
                    });
                        $('.andamento').on("click",function(){
                            var id = $(this).attr('value');
                            $.ajax({
                                type: "GET",
                                url: '{{route("status_muda_pronto")}}'+'/'+id,
                                data: {},
                                success: function(status_pronto) {
                                    var status_cabecalho = itens[0].cabecalho[0].venda_status;
                                    var botao_classe = '';

                                    if(status_pronto == 3){
                                        var tr = $(".and").find('.'+id);
                                        $('.andamento').attr("class",'btn btn-success pronto');
                                        $('.pronto').text("Pronto");
                                        if($(".pron").size('')==0){
                                            $(".pronto_tab").append("<table style='display: block !important;' class='table table-responsive'>" + "<thead>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" + "</th>" + "</thead>" + "<tbody class='pront_din'>" + "</tbody>" + "</table>");
                                            $(".pront_din").append(tr);
                                            $(".txt_andamen").remove();
                                            $(".pronto_texto").remove();
                                            $(".primei").find('.'+id).remove();
                                        }else{
                                            $(".pron").last().append(tr);
                                        }   
                                    }
                                },
                            });
                            
                        });
                    });
                },
            });
            
        });
});
</script>
<script type="text/javascript">
    var time = 10000;
    var tid = setInterval(mycode, time);
    function mycode() {
        $(function(){
            $.ajaxSetup({
                headers:{
                    'X-CSRF-Token':$('input[name="_token"]').val()
                }
            });
                if($('.itens').empty('tr:tabela_item')){
                $.ajax().abort();
                }
                if($('.cabecalho_mesa').empty('tr:tabela_item')){
                    $.ajax().abort();
                }
                if($('.novos_pendente').empty()) {
                    $.ajax().abort();
                }
                $.ajax({
                    type: "GET",
                    url: '{{route("novos_pedidos_pendente")}}',
                    data: {},
                    success: function(venda) {
                                 
                        $.each(venda[0].venda,function(key, value){

                            var id = venda[0].venda_id;
                            var tr = $(".pend").find(id);
                            
                            
                            if(id != tr){   
                                if(value.venda_id == value.produto_id || value.venda_id != tr){
                                $(".novos_pendente").append("<tbody class='pend'>" +value+ "<tr class="+value.id_venda+">" + "<td>" + value.id_venda + "</td>" + "<td>" + value.numero+ "</td>" + "<td>" + "<button class='btn btn-primary btn-xs detalhes' value='"+ value.id_venda+" ' data-toggle='modal' data-target='#myModal'>" + 'Detalhes' + "</button>" + "</td>" + "</tr>" + "</tbody>");
                                    
                            
                                }
                            }
                        });


                        $('.detalhes').click(function(){
                            clearInterval(tid);
                            var id = $(this).attr('value');
                            if($('.itens').empty('tr:tabela_item')){
                                $.ajax().abort();
                            }
                            if($('.cabecalho_mesa').empty('tr:tabela_item')){
                                $.ajax().abort();
                            }
                            $.ajax({
                                type: "GET",
                                url: '{{route("pedido.pendentes")}}'+'/'+id,
                                data: {},
                                success: function(itens){
                                    
                                    var tabela = $('.tabela_item').find();
                                    var status_cabecalho = itens[0].cabecalho[0].venda_status;
                                    var botao_classe = '';

                                    if(status_cabecalho == 1){
                                        status_cabecalho = 'Pendente';
                                        botao_classe = 'btn btn-danger pendente';
                                    }
                                    if(status_cabecalho == 2){
                                        status_cabecalho = 'Em Andamento';
                                        botao_classe = 'btn btn-info andamento';
                                    }
                                    if(status_cabecalho == 3){
                                        status_cabecalho = 'Pronto';
                                        botao_classe = 'btn btn-success pronto';
                                    }
                                    $('.cabecalho').append("<tr class='cabecalho_mesa'>" + "<td class='data'>"+ itens[0].data +"</td>" +"<td>"+ itens[0].cabecalho[0].numero+"</td>"+"<td>R$"+ itens[0].cabecalho[0].valor_venda+"</td>" + "<td>" + "<button value='"+ itens[0].cabecalho[0].id_venda +"' class='"+botao_classe+"'>" + status_cabecalho + "</button>" + "</td>" + "</tr>");
                                    // fim
                                    $.each(itens[0]['itens'],function(key, value){
                                    
                                    $('.itens').append("<tr class='tabela_item'>" + "<td>" + "<img style='width: 50px;' src='/uploads/"+ value.imagem_nome +"' data-lightbox='roadtrip'/>" + "</td>" +"<td>" + value.nome +  "</td>" + "<td>" + value.qtde + "</td>" + "</tr>");
                                    });

                                },
                            });
                        });
                    },
                });
                
            });
        };
    function abortTimer() {
        clearInterval(tid);
    }
    function voltar_timer(){
        var time = 10000;
        setInterval(mycode, time);

    }
</script>

@stop