@extends('layouts.frente-loja')

@section('conteudo')
<br/>
    @if(\Session::get('cliente')=='')
        <div class="alert alert-info alert-dismissable text-center">
            <strong>Ola!</strong>Gostaria de se cadastrar no nosso sistema?<br/><a class='btn btn-primary btn-sm'  href="{{url('cadastrar_cliente')}}">Cadastrar</a> <br/> Ou acessar sua conta?<br/><a href="{{url('login_cliente')}}" class='btn btn-info btn-sm'>Acessar</a> 
        </div>
    @endif
    <a class="btn btn-info" href="{{url('volte_sempre_liberar',\Session::get('id_mesa'))}}">Sair da mesa</a>
    <br/>
    <br/>
    <h1 class='hidden-xs'>Produtos em Destaque:</h1>
    <h1 style='margin-top:50px;' class='hidden-lg hidden-md hidden-sm '>Produtos em Destaque:</h1>
    <div class='col-sm-12'>
        <div class="page-header text-muted">
            {{count($produto_destacado)}} produtos em destaque
        </div>
    </div>
    <div class="col-md-12">
        @foreach($produto_destacado->chunk(3) as $chunked)
        <div class="row">
        @foreach($chunked as $produto_destacado)
            <div class="col-sm-6 col-md-4">
                <div class="panel panel-primary">
                    <div class="thumbnail">
                        <img style='height:300px; width:300px;' src="{{asset('uploads/'.$produto_destacado->imagem_nome)}}" alt="{{$produto_destacado->imagem_nome}}" data-lightbox="roadtrip">
                        <div class="caption">
                            <div><h3>{{$produto_destacado->nome}}</h3></div>
                            <h4 class="text-muted">{{$produto_destacado->marca->nome}}</h4>
                            <p>{{str_limit($produto_destacado->descricao,100)}}</p>
                            <button type="button" class="btn btn-primary btn-lg getid" value='{{$produto_destacado->id}}' data-toggle="modal" data-target="#myModal">Mais Detalhes</button>
                            <h4>R$:{{$produto_destacado->preco_venda}}</h4>
                        </div>
                    </div>
                </div>
          </div>
        @endforeach
        </div>
        @endforeach
    </div>
    <br/>
	<h2>Cardápio</h2>
    <div class="col-md-12">
	@foreach($produto->chunk(3) as $linha)
        <div class='row'>
        @foreach($linha as $produto)
       	    <div class="col-sm-6 col-md-4">
    			<img style='height:200px; width:200px;' src="{{asset('uploads/'.$produto->imagem_nome)}}" data-lightbox="roadtrip" alt="">
    			<div class="card-body">
    				<h4 class="card-title">
    					{{$produto->nome}}
    				</h4>
    				<p class="card-text">{{str_limit($produto->descricao,100)}}</p>
    				<h4 class="card-text">R${{$produto->preco_venda}}</h4>
    				<!-- Trigger the modal with a button -->
    				<button type="button" class="btn btn-primary btn-lg getid" value='{{$produto->id}}' data-toggle="modal" data-target="#myModal">Mais Detalhes</button>
    			</div>	
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
		    <!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	           <h4 class="modal-title">Modal Header</h4>
	    </div>
	    <div class="modal-body">
	           <p class='conteudo'></p>
	           <p class='valor'></p>
	           <img style='height:200px; width:200px;' class="imagem" />
               <div class='col-md-12'>
               <p>Média de avaliações<p>
               <h5 class="alert alert-success text-center avaliado col-md-3"><strong></strong>
               </h5>
               </div>
            <form class="action_carrinho"  action="{{route('adicionar')}}">
	    </div>
	    <div class="modal-footer">
                <p class='text-left'>Quantidade</p>
                <input style="width: 60px; margin-right: 10px;" type="numeric" value="1" name="quant" class="col-xs-1 form-control text-center quant">
                <input class="btn btn-primary btn-sm col-md-1 col-sm-2 col-xs-2 increment" type="button" value="+" onclick="$('#alvo').val(parseInt($('#alvo').val())+1)">
                <input class="btn btn-primary btn-sm col-md-1 col-sm-2 col-xs-2 decrement" type="button" value="-" onclick="$('#alvo').val(parseInt($('#alvo').val())-1)">
                <br/>
                <br/>
	            <button type="submit" name="botao" value="" class="btn btn-primary btn-lg  pull-left add_carrinho" > Adicionar ao carrinho</button>
            <button type="button" class="btn btn-default  pull-right" data-dismiss="modal">Fechar</button>
            </form>
	    </div>
	    </div>
	  </div>
	</div>

    <!-- Modal carrinho -->
    <div id="carrinho_id" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Carrinho</h4>
          </div>
          <div class="modal-body">
            <table style="display: block !important;" class="table table-responsive">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th class="text-right">Preço Unitário</th>
                        <th>Quantidade</th>
                        <th></th>
                        <th><a href="{{route('carrinho.esvaziar')}}" class='btn btn-warning btn-sm'>Esvaziar carrinho</a></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $item)
                    <tr class="teste">
                        <td>
                            <img src="{{asset('uploads/'.$item->produto->imagem_nome)}}" alt="{{$item->produto->imagem_nome}}" data-lightbox="roadtrip" style="width:70px;" >
                        </td>
                        <td>
                            {{$item->produto->nome}}
                        </td>
                        <td class="text-center">
                            {{number_format($item->produto->preco_venda, 2, ',', '.')}}
                        </td>
                        <td class="text-center quant_item"> 
                             <input style="width: 40px; height: 25px; margin-right: 3px;" type="numeric" value="{{$item->qtde}}" name="quant" class="col-sm-2 col-xs-2 form-control btn-xs text-center qun">
                                
                                <button style="margin-right: 1px; margin-left: 2px;" class="btn btn-primary btn-sm col-md-2 col-sm-2 col-xs-2 text-center increment" type="submit" value="{{$item->produto->id}}">+ </button>
                                
                                <button style="margin-left: 3px;" class="btn btn-primary btn-sm col-md-2 col-sm-2 col-xs-2 decrement" type="submit" value="{{$item->produto->id}}"> -</button>
                        </td>
                        <td> 
                        <a href="{{route('remover', $item->produto->id, $item->qtde)}}" 
                                style="margin-bottom: 3px; margin-right: 15px;" class="btn btn-danger btn-xs pull-right">Excluir item</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">
                            Total
                        </td>
                        <td>
                            <h4 class="text-center text-danger total">
                                R${{number_format($total,2,',','.')}}
                            </h4>
                        </td>
                    </tr>
                </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default  pull-right" data-dismiss="modal">Fechar</button>
                <button type="submit" name="botao" class="btn btn-primary btn-lg  pull-left add_carrinho" > Confirmar pedido</button>
          </div>
        </div>
      </div>
    </div>
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(function() {
    $.ajaxSetup({
        headers:{
            'X-CSRF-Token':$('input[name="_token"]').val()
        }
    });
        $('.getid').click(function(){
            var id = $(this).attr('value');
            $.ajax({
                type: "GET",
                url: 'http://localhost:8000/mesa/produto/'+id,
                data: {id: id},
                success: function(id){
                avaliado = id.avaliacao_total/id.avaliacao_qtde;
                $('.modal-title').html(id.nome);
                $('.conteudo').html(id.descricao);
                $('.valor').html('R$: '+id.preco_venda);
                $(".imagem").attr("src",'http://localhost:8000/uploads/'+id.imagem_nome);
                $('.add_carrinho').val(id.id);
                if(Number.isNaN(avaliado)){
                  $('.avaliado').html('Não avaliado');  
                }else{
                  $('.avaliado').html(avaliado.toFixed(2));
                }
  				//console.log($('.add_carrinho').val());
                //console.log(id.id);
                },
            });
            
        });
});
</script>

<!-- Increment -->

<script type="text/javascript">
$(function() {
    $.ajaxSetup({
        headers:{
            'X-CSRF-Token':$('input[name="_token"]').val()
        }
    });
        $('.increment').on("click",function(){
            var id = $(this).attr('value');
            //var qtde = $('.quant').attr('value');
            //alert(qtde);
            console.log(id);
            $.ajax({
                type: "GET",
                url: 'http://localhost:8000/increment_teste/'+id,
                data: {id : id},
                success: function(total) {
                $('.total').html('R$'+total);
                //$('.increment').html(id.id);
                //console.log(total);
                //console.log(id.id);
                //console.log($('.add_carrinho').val());
                //console.log(id.id);
                },
            });
            
        });
});
</script>
<!-- Decrement -->
<script type="text/javascript">
$(function() {
    
        $('.add_carrinho').click(function(){
            window.location.href =  "http://localhost:8000/finalizar_cardapio/";    
            
        });
});
</script>
<script>
    $(".increment").on('click',function(){
        var value = $('.quant').val();
        $('.quant').val(parseInt($('.quant').val())+1); return false;
    });
    $(".decrement").click(function(){
       if($(".quant").val()!=0){$('.quant').val(parseInt($('.quant').val())-1);} return false;
    });
</script>


<script type="text/javascript">
$(function() {
    
        $('.cadastrar').click(function(){
            window.location.href =  "http://localhost:8000/cadastrar_cliente/";    
            
        });
});
</script>

@stop