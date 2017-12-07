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
  <h3 class="col-md-12 col-lg-12 col-sm-12 col-xs-12">Mesas Disponíveis</h3>
    <strong class="col-md-12 col-lg-12 col-sm-12 col-xs-12">Legenda:<button style="margin-right:5px;" class="btn btn-primary btn-sm">Mesa Livre</button><button style="margin-right:5px;" class="btn btn-success btn-sm">Número Da Mesa Reservada</button><button class="btn btn-danger btn-sm">Mesa Reservada</button></strong>
  @foreach($mesa as $m)
      <a @if($m->status == '2') class='btn btn-danger btn-lg reservada' @elseif($m->reservar_numero == TRUE) class='btn btn-success btn-lg' @else class='btn btn-primary btn-lg' @endif  href="{{url('reservar_mesa',$m->id_mesa)}}"  >{{$m->numero}}</a>
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
<script type="text/javascript">
  $(".reservada").prop('disabled', true);
</script>
<script type="text/javascript">
  function draw() {
  var canvas = document.getElementById('canvas');
  if (canvas.getContext) {
    var ctx = canvas.getContext('2d');

    ctx.fillRect(25, 25, 100, 100);
    ctx.clearRect(45, 45, 60, 60);
    ctx.strokeRect(50, 50, 50, 50);
  }
}
</script>
@stop