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
                <div class="panel-body" id="novo">
                    <p class='text-danger text-center txt_pendente'><strong>Nenhum Pedido Pendente no Momento</strong></p>
                    <table class="table table-responsive novos_pendente hidden">
                        <thead>
                            <th>Pedido</th>
                            <th>Mesa</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody class="pend">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Listagem pedidos em Andamento -->
        <div class="col-sm-4 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">Em Andamento</h3>
                </div>  
                <div  class="panel-body andamen">
                    @if(count($andamento) == 0)
                    <p class='text-info text-center txt_andamen'><strong>Nenhum pedido em andamento  no momento</strong></p>
                    @else
                    <table id="andamento_pag" class="table table-responsive">
                        <thead id="th_andamen">
                            <th>Pedido</th>
                            <th>Mesa</th>
                            <th></th>
                        </thead>
                        @foreach($andamento as $a)
                        <tbody class="and">
                            <tr class="{{$a->id_venda}}">
                                <td>{{$a->id_venda}}</td>
                                <td>{{$a->mesa->numero}}</td>
                                <td><button class='btn btn-primary btn-xs detalhes' value="{{$a->id_venda}}" data-toggle="modal" data-target="#myModal" onclick="zeraTime()">Detalhes</button></td>
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
                    <table id="pronto_pag" style="display: block !important;" class="table table-responsive">
                        <thead id="th_pronto">
                            <th>Pedido</th>
                            <th><strong>Mesa</strong></th>
                            <th></th>
                        </thead>
                        @foreach($prontos as $pron)
                        <tbody class='pron'>
                            <td>{{$pron->id_venda}}</td>
                            <td>{{$pron->mesa->numero}}</td>
                            <td><button class='btn btn-primary btn-xs detalhes' value="{{$pron->id_venda}}" data-toggle="modal" data-target="#myModal" onclick="zeraTime()">Detalhes</button></td>
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
        <button type="button" class="btn btn-default  pull-right fechar" onclick="fechar()" data-dismiss="modal">Fechar</button>
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
                                                $(".txt_andamen").addClass('hidden');
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
                                            $(".pronto_tab").append("<table style='display: block !important;' class='table table-responsive'>" + "<thead class='th_andamen'>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" + "</th>" + "</thead>" + "<tbody class='pront_din'>" + "</tbody>" + "</table>");
                                            $(".pront_din").append(tr);
                                            $(".txt_andamen").addClass('hidden');
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
    var time = 5000;
    var tid = setInterval(mycode, time);
    function mycode() {
        $(function(){
                if($('.itens').empty('tr:tabela_item')){
                $.ajax().abort();
                }
                if($('.cabecalho_mesa').empty('tr:tabela_item')){
                    $.ajax().abort();
                }
                if($('.novos_pendente').empty('tbody:and')) {
                    $.ajax().abort();
                }
                $.ajax({
                    type: "GET",
                    url: '{{route("novos_pedidos_pendente")}}',
                    data: {},
                    success: function(venda) {
                        //$(".novos_pendente").empty();
                        //$(".novos_pendente tbody").empty();
                        if($('.novos_pendente').length == 0){
                            $('.txt_pendente').removeClass('hidden');
                            $('.novos_pendente').addClass('hidden');
                            //$('.tab').addClass('hidden');
                            //$('.txt_pendente').removeClass('hidden');
                        }else{
                            $('.txt_pendente').addClass('hidden');
                            $('.novos_pendente').removeClass('hidden');
                            $('.novos_pendente').attr('style', 'display: block !important');
                        }
                        if($('.and').length == true){                         
                            $('.txt_andamen').removeClass('hidden');
                            //$('#andamento_pag').removeClass('hidden');
                            //$('.tb_andamen').removeClass('hidden');
                            $('.th_andamen').addClass('hidden');
                            //$('#tb_andamen').removeClass('hidden');
                            //$('.tab').addClass('hidden');
                            //$('.txt_pendente').removeClass('hidden');
                        }
                        $(".novos_pendente").append("<thead class='pendente_thead'>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" + "</th>" + "</thead>");
                        $.each(venda[0].venda,function(key, value){

                            $(".novos_pendente").append("<tbody class='pend'>" +value.id_venda+ "<tr class="+value.id_venda+">" + "<td>" + value.id_venda + "</td>" + "<td>" + value.numero+ "</td>" + "<td>" + "<button class='btn btn-primary btn-xs detalhes' value='"+ value.id_venda+" ' data-toggle='modal' data-target='#myModal' onclick='zeraTime()'>" + 'Detalhes' + "</button>" + "</td>" + "</tr>" + "</tbody>");
                        });
                        $('.detalhes').on('click',function(){
                            
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
                                    $('.cabecalho').empty();
                                    $('.itens').empty();
                                    
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
                                    $('.cabecalho').children().remove();
                                    $('.cabecalho').append("<tr class='cabecalho_mesa'>" + "<td class='data'>"+ itens[0].data +"</td>" +"<td>"+ itens[0].cabecalho[0].numero+"</td>"+"<td>R$"+ itens[0].cabecalho[0].valor_venda+"</td>" + "<td>" + "<button value='"+ itens[0].cabecalho[0].id_venda +"' class='"+botao_classe+"'>" + status_cabecalho + "</button>" + "</td>" + "</tr>");
                                    // fim
                                    $(function(){
                                        $.ajaxSetup({
                                            headers:{
                                                'X-CSRF-Token':$('input[name="_token"]').val()
                                            }
                                        });
                                        $('.pendente').on("click",function(){
                                            var id = $(this).attr('value');
                                            console.log($('.and'));
                                            if($('.novos_pendente').length == true){
                                                                                    
                                                $('.txt_pendente').removeClass('hidden');
                                                $('.novos_pendente').addClass('hidden');
                                                $('.pendente_thead').addClass('hidden');
                                                //$('.tab').addClass('hidden');
                                                //$('.txt_pendente').removeClass('hidden');
                                            }else{
                                                $('.txt_pendente').addClass('hidden');
                                                $('.novos_pendente').removeClass('hidden');
                                                $('.novos_pendente').empty();
                                                $('.novos_pendente').attr('style', 'display: block !important');
                                            }
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
                                                            $(".andamen").append("<table style='display: block !important;' class='table table-responsive'>" + "<thead class='tb_andamen'>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" +''+ "</th>" + "</thead>" + "<tbody class='and'>" + "</tbody" + "</table>");
                                                            $(".and").append(tr);
                                                            $(".txt_andamen").addClass('hidden');
                                                            $(".pend").find('.'+id).remove();
                                                        // Caso já contenha um Pedido, somente adiciona-o na tabela
                                                        }else{
                                                            $(".and").last().append(tr);
                                                        }
                                                    }
                                                },
                                            });
                                            if($('.and').length == false){                      
                                                $('.txt_andamen').removeClass('hidden');
                                                $('#andamento_pag').addClass('hidden');
                                                $('.tb_andamen').addClass('hidden');
                                                $('#tb_andamen').addClass('hidden');
                                                //$('.tab').addClass('hidden');
                                                //$('.txt_pendente').removeClass('hidden');
                                            }else{
                                                $('.txt_andamen').addClass('hidden');
                                                $('#andamento_pag').removeClass('hidden');
                                                $('.tb_andamen').removeClass('hidden');
                                                $('#tb_andamen').removeClass('hidden');
                                                //$('#andamento_pag').attr('style', 'display: block !important');
                                            }
                                        });
                                    });
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
                                                        if($('.andamen').length == true){                         
                                                            $('.txt_andamen').removeClass('hidden');
                                                            $('#andamento_pag').addClass('hidden');
                                                            $('.tb_andamen').addClass('hidden');
                                                            $('#tb_andamen').addClass('hidden');
                                                            //$('.tab').addClass('hidden');
                                                            //$('.txt_pendente').removeClass('hidden');
                                                        }else{
                                                            $('.txt_andamen').addClass('hidden');
                                                            $('#andamento_pag').removeClass('hidden');
                                                            $('.tb_andamen').removeClass('hidden');
                                                            $('#tb_andamen').removeClass('hidden');
                                                            //$('#andamento_pag').attr('style', 'display: block !important');
                                                        }
                                                        var tr = $(".and").find('.'+id);
                                                        $('.andamento').attr("class",'btn btn-success pronto');
                                                        $('.pronto').text("Pronto");
                                                        if($(".pron").size('')==0){
                                                            $(".pronto_tab").append("<table style='display: block !important;' class='table table-responsive'>" + "<thead>" + "<th>" + 'Pedido' + "</th>"+ "<th>" + 'Mesa' + "</th>" + "<th>" + "</th>" + "</thead>" + "<tbody class='pront_din'>" + "</tbody>" + "</table>");
                                                            $(".pront_din").append(tr);
                                
                                                            $(".pronto_texto").remove();
                                                            $(".primei").find('.'+id).remove();
                                                        }else{
                                                            $(".pron").last().append(tr);
                                                        }   
                                                    }
                                                    if($('.and').length == true){                         
                                                            $('.txt_andamen').removeClass('hidden');
                                                            $('#andamento_pag').addClass('hidden');
                                                            $('.tb_andamen').addClass('hidden');
                                                            $('#tb_andamen').addClass('hidden');
                                                            //$('.tab').addClass('hidden');
                                                            //$('.txt_pendente').removeClass('hidden');
                                                        }else{
                                                            $('.txt_andamen').addClass('hidden');
                                                            $('#andamento_pag').removeClass('hidden');
                                                            $('.tb_andamen').removeClass('hidden');
                                                            $('#tb_andamen').removeClass('hidden');
                                                            //$('#andamento_pag').attr('style', 'display: block !important');
                                                        }
                                                },
                                            });
                                            
                                        });
                                    });
                                    //$('.itens').children().remove();
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
        function zeraTime(){
            clearInterval(tid);
        }
        function fechar(){
            window.tid=setInterval(mycode,5000);    
        }
</script>
@stop