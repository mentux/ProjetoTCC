@extends('layouts.frente-loja')

@section('conteudo')
<br/>
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
    		
   @if($pedido->status == 3)
	   <h3 class='text-success text-center' >Bom apetite :)</h3>
	   <h3 class='text-success text-center' >O sistema ira retornar ao cardápio automaticamente.</h3> 
	   <script type="text/javascript">
	   var id = '<?php echo $pedido->id_mesa;?>';
	   	setTimeout(
	        function(){
	            window.location = "http://localhost:8000/getmesa/"+id; 
	        },
	    5000);
	   </script>
   @endif
@endsection