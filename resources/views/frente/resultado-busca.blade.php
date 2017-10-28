@extends('layouts.frente-loja')

@section('conteudo')
<br/>
<br/>
<div class='col-sm-12'>
    <div class="page-header text-muted">
        {{$produtos->total()}} encontrado(s) com o termo de busca 
        <span class="label label-info">{{$termo}}</span>
    </div>
</div>

<div class="col-sm-12 text-center">
    {!! $produtos->appends(['termo-pesquisa' => $termo])->links() !!}
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Produto</th>
            <th class="text-right">Valor Unitário</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)

        <tr>
            <td>
                <img src="{{asset('uploads/'.$produto->imagem_nome)}}" alt="{{$produto->imagem_nome}}" style="width:150px;" >
            </td>
            <td>
                {{$produto->nome}}
            </td>
            <td class="text-right">
                {{number_format($produto->preco_venda, 2, ',', '.')}}
            </td>
            <td class="text-right">
                <button data-toggle="modal" data-target="#myModal" value="{{$produto->id}}" class='btn btn-primary getid'>Mais detalhes</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


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
                <p class='text-left' >Quantidade</p>
                <input type="numeric" value='1' name='quant' class="col-sm-2">
                <br/>
                <br/>
               <button type="submit" name="botao" value="@if(isset($produto->id)){{$produto->id}} @endif" class="btn btn-primary btn-lg  pull-left add_carrinho" > Adicionar ao carrinho</button>
            <button type="button" class="btn btn-default  pull-right" data-dismiss="modal">Fechar</button>
            </form>
          </div>
        </div>

      </div>
    </div>


<div class="col-sm-12 text-center">
    {!! $produtos->links() !!}
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
                //console.log(id);
                //console.log($('.add_carrinho').val());
                //console.log(id.id);
                },
            });
            
        });
});
</script>
@stop