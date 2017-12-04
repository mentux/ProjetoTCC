@extends('layouts.admin')

@section('conteudo')

	<link href="{{asset('bootstrap/css/mycss/menu.css')}}" rel="stylesheet">
	<div class="alert alert-danger">  
		<h3 >Deseja relamente excluir o Usuário {!! $usuario->name !!}?</h3>
	</div>
	
	{!! Form::open(['method'=>'DELETE', 'url'=>'admin/usuario/'.$usuario->id.'/deletar', 'style'=>'display: inline;']) !!}
	 	
	 	<button type="submit" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> <strong>Excluir Usuário</strong></button>

	 	<a href="{{Route('admin.usuarios')}}"><div class="btn btn-success btn-sm glyphicon glyphicon-share-alt"> <strong>Cancelar</strong> </div></a>

	{!! Form::close() !!}

@stop
