@extends('layouts.admin')

@section('conteudo')	
<link href="{{asset('bootstrap/css/mycss/menu.css')}}" rel="stylesheet">
	<div class="alert alert-danger">  
			<h3 >Deseja relamente excluir a mesa {{ $mesa->numero }} ?</h3>
	</div>
	
	 	
	 	<a href="{{url('admin/mesa/'.$mesa->id_mesa . '/excluir')}}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span><strong>Excluir Mesa</strong></a>

	 	<a href="{{Route('admin.mesa.listar')}}"><div class="btn btn-success btn-sm glyphicon glyphicon-share-alt"> <strong>Cancelar</strong> </div></a>



@stop