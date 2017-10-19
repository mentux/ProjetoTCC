@extends('layouts.frente-loja')

@section('conteudo')
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
                <br/>
            <form class="action_carrinho"  action="{{route('adicionar')}}">
	    </div>
	    <div class="modal-footer">
                <p class='text-left' >Quantidade</p>
                <input type="numeric" value="1" name="quant" class="col-sm-2">
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
                        <td class="text-center"> 
                            {{$item->qtde}}
                        </td>
                        <td> 
                        <a href="{{route('remover', $item->produto->id)}}" 
                                class="btn btn-danger btn-xs pull-right">Excluir item</a>
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
                            <h4 class="text-center text-danger">
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
                success: function(id) {
                $('.modal-title').html(id.nome);
                $('.conteudo').html(id.descricao);
                $('.valor').html('R$: '+id.preco_venda);
                $(".imagem").attr("src",'http://localhost:8000/uploads/'+id.imagem_nome);
                $('.add_carrinho').val(id.id);
  				//console.log($('.add_carrinho').val());
                //console.log(id.id);
                },
            });
            
        });
});
</script>
<script type="text/javascript">
$(function() {
    
        $('.add_carrinho').click(function(){
            window.location.href =  "http://localhost:8000/finalizar_cardapio/";    
            
        });
});
</script>
@stop