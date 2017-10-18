@extends('layouts.frente-loja')

@section('conteudo')


<div class="container">
	<h1 class="col-md-offset-2 text-info">Obrigado pela preferência e volte sempre! :) </h1>
</div>
	@if($pegar_mesa->status == 1)
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script type="text/javascript">
	   	setTimeout(
	        function(){
	            window.location = "http://localhost:8000/volte_sempre"; 
	        },
	    3000);
	   </script>
	@else
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script type="text/javascript">
		var id = '<?php echo $pegar_mesa->id_mesa;?>';
		setTimeout(
	        function(){
	            window.location = "http://localhost:8000/getmesa/"+id; 
	        },
	    3000);
	   </script>	
	@endif
@endsection