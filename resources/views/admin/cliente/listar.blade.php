@extends('layouts.admin')


@section('conteudo')

<div class="panel panel-default ">
  	<div class="panel-heading">
    	<h3 class="panel-title">Lista de Clientes</h3>
  	</div>
  	<div class="panel-body">
  	@if(Session::has('mensagem_sucesso'))
		{!! 'OK' !!}
  	@endif
	  	<table class="table table-hover table-striped">
		    		<th>id</th>
		            <th>Nome</th>
		            <th>E-mail</th>
		            <th>Endereço</th>
		            <th>Atualizar</th>
		            <th>Excluir</th>
		        </tr>
		    </thead>
		    <tbody>
		    @foreach($cliente as $c)
		    <tr>
		        <td>{{$c->id}}</td>
		        <td>{{$c->name}}</td>
		        <td>{{$c->email}}</td>
		        <td>{{$c->endereco}}</td>
		        <td>
		            <a href="{{ url('admin/cliente/atualizar',$c->id) }}" class="btn btn-info btn-sm">
		                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> editar 
		            </a>
		        </td>
		        <td><a href="{{ url('admin/cliente/excluir',$c->id) }}" class="btn btn-danger btn-sm">
			    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> excluir 
			    </a></td>
		    </tr>
		    @endforeach
		    </tbody>
		</table>
			{{$cliente->links()}}
	</div>
</div>

@stop