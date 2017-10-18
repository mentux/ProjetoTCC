@extends('layouts.frente-loja')

@section('conteudo')
<br/>
<div class='container'>
    <div class="col-sm-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="text-center">Pendente</h3>
            </div>
            <div class="panel-body text-center">
                <h1 class="text-info">
                @if($pedido->status==1)<h2 class='text-danger'>Status Atual</h2>@endif	
                </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="text-center">Em Andamento</h3>
            </div>
            <div class="panel-body text-center">
                <h1 class="text-info">
                @if($pedido->status==2)<h2 class='text-info'>Status Atual</h2>@endif
                </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="text-center">Pronto</h3>
            </div>
            <div class="panel-body text-center">
                <h1 class="text-info">
                @if($pedido->status==3)<h2 class='text-success'>Status Atual</h2>@endif
                </h1>
            </div>
        </div>
    </div>
</div>	
   @if($pedido->status == 3)
   <div class='container'>
	   <h3 class='text-success text-center' >Bom apetite :)</h3>

	   <div class="container">
            <a class="btn btn-success btn-lg col-sm-3 col-md-offset-3" href="{{url('getmesa',\Session::get('id_mesa'))}}">Voltar para o cardápio</a>
            <a class="btn btn-danger btn-lg col-sm-1 col-sm-offset-1" href="{{url('volte_sempre_liberar',\Session::get('id_mesa'))}}">Sair</a>
        </div>
   </div>
   @endif

   <script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
    <script type="text/javascript">
        var id = '<?php echo $pedido->id_venda ?>';
        setTimeout(
            function(){
                window.location = "http://localhost:8000/mesa_pedido/"+id; 
            },
        2000);
       </script>
@endsection