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
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
  <script type="text/javascript">
    setTimeout(
          function(){
              window.location = "http://localhost:8000"; 
          },
      5000);
     </script>
@stop