@extends('layouts.admin')

@section('conteudo')

<div class="panel panel-default ">
  	<div class="panel-heading">
    	<h3 class="panel-title">Atualizar Cliente</h3>
  	</div>
  	<div class="panel-body">
  		@if(Session::has('mensagem_sucesso'))
			{!! 'OK' !!}
  		@endif
		<div class="form-group">
				<h3>Editar Informações do cliente: {!! $cliente->name !!}</h3>
				{!! Form::model($cliente, ['method'=>'POST', 'url'=>'admin/cliente/atualizar/'.$cliente->id]) !!}

					{!! Form::label('name', 'Nome', ['class'=>'input-group']) !!}
					{!! Form::input('text', 'name', null, ['class'=>'form-control', 'autofocus']) !!}

	
					{!! Form::label('endereco', 'Endereço', ['class'=>'input-group']) !!}
					{!! Form::input('text', 'endereco', null, ['class'=>'form-control', 'autofocus']) !!}
					<br/>
					{!! Form::submit('Salvar', ['class'=>'btn btn-primary', 'style'=>'margin-top:2px']) !!}

					<a href="{{Route('admin.cliente.listar')}}"><div class="btn btn-success  glyphicon glyphicon-share-alt"><strong>Cancelar</strong> </div></a>

				{!! Form::close() !!}
		</div>	
	</div>
</div>

@stop
	