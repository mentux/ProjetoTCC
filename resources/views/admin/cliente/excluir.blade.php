@extends('layouts.admin')

@section('conteudo')

	<link href="{{asset('bootstrap/css/mycss/menu.css')}}" rel="stylesheet">
	<div class="alert alert-danger">  
		<h3 >Deseja relamente excluir o Cliente {!! $cliente->name !!}?</h3>
	</div>
	
	{!! Form::open(['method'=>'DELETE', 'url'=>'admin/cliente/'.$cliente->id.'/deletar', 'style'=>'display: inline;']) !!}
	 	
	 	<button type="submit" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> <strong>Excluir Cliente</strong></button>

	 	<a href="{{Route('admin.cliente.listar')}}"><div class="btn btn-success btn-sm glyphicon glyphicon-share-alt"> <strong>Cancelar</strong> </div></a>

	{!! Form::close() !!}

@stop
