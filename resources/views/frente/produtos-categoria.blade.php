@extends('layouts.frente-loja')

@section('conteudo')
<div class='col-sm-12'>
    <h2 class="page-header text-info">
        Produtos da categoria {{$categoria->nome}}
    </h2>
</div>
@foreach ($categoria->produtos as $produto)
<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <!-- atributo data-lightbox para o plugin lightbox -->
        <img src="{{asset('uploads/'.$produto->imagem_nome)}}" data-lightbox="roadtrip">
        <div class="caption">
            <h3>{{$produto->nome}}</h3>
            <h4 class="text-muted">{{$produto->marca->nome}}</h4>
            <p>{{str_limit($produto->descricao,100)}}</p>
            <p><button type="button" class="btn btn-primary btn-lg getid" value='{{$produto->id}}' data-toggle="modal" data-target="#myModal">Mais Detalhes</button></p>
        </div>
    </div>
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
@endforeach
@stop