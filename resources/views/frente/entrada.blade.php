@extends('layouts.frente-loja')

@section('conteudo')

<!-- TEMPORARIAMENTE -->
<style>
.btn-lg{
   font-size: 90px;
   border-radius: 5px;
   margin: 30px;
   margin-left:10px;
   width:150px;
}
</style>
<br/>
<div class='col-md-12'>
  <h3 style='text-indent:10px;' >Mesas disponíveis</h3>
  @foreach($mesa as $m)
      <a href="{{url('reservar_mesa',$m->id_mesa)}}" class='btn btn-primary btn-lg' >{{$m->numero}}</a>
  @endforeach
</div>
<!--<div class='col-sm-12'>
    <div class="page-header text-muted">
        {{count($produtos)}} produtos em destaque
    </div>
</div>
<div class="col-md-12">
@foreach($produtos->chunk(3) as $chunked)
<div class="row">
@foreach($chunked as $produto)
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-primary">
            <div class="thumbnail">
                <img src="{{route('imagem.file',$produto->imagem_nome)}}" alt="{{$produto->imagem_nome}}" data-lightbox="roadtrip">
                <div class="caption">
                    <div><h3>{{$produto->nome}}</h3></div>
                    <h4 class="text-muted">{{$produto->marca->nome}}</h4>
                    <p>{{str_limit($produto->descricao,100)}}</p>
                    <p><a href="{{route('produto.detalhes', $produto->id)}}" class="btn btn-primary" role="button">Detalhes</a></p>
                </div>
            </div>
        </div>
  </div>
@endforeach
</div>
@endforeach-->
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
  <script type="text/javascript">
    setTimeout(
          function(){
              window.location = "http://localhost:8000"; 
          },
      3000);
     </script>
@stop