@extends('layouts.admin')


@section('conteudo')

<div class="panel panel-default" style="display: block !important;">
  	<div class="panel-heading">
    	<h3 class="panel-title">Lista de Categoria</h3>
  	</div>
  	<div class="panel-body">
  	@if(Session::has('mensagem_sucesso'))
		{!! 'OK' !!}
  	@endif
	  	<table class="table table-hover table-striped table-responsive" style="display: block !important;">
		    <caption> 
		        <a href="{{ route('admin.categoria.criar') }}" class="btn btn-primary btn-sm">
		            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nova Categoria 
		        </a>
		    </caption>
		    		<th>id</th>
		            <th>Nome</th>
		            <th>Categoria Principal</th>
		            <th>Editar</th>
		            <th>Excluir</th>
		        </tr>
		    </thead>
		    <tbody>
		    @foreach($listcategorias as $cat)
		    <tr>
		        <td>{{$cat->id}}</td>
		        <td>{{$cat->nome}}</td>
		        <td>@if($cat->categoria_id != "")
		         		{{$cat->pai->nome}}
		         	@else
		         		{{"Categoria Principal"}}
		         	@endif

		         </td>
		        
		        <td>
		            <a href="{{ url('admin/categoria/'.$cat->id . '/editar') }}" class="btn btn-info btn-sm">
		                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar 
		            </a>
		        </td>
			    <td>
			        @if(count($cat->produtos) > 0)
						<a href="{{ url('admin/categoria/'.$cat->id = -1 . '/excluir') }}" class="btn btn-danger btn-sm">
			                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Excluir 
			            </a>
			        @else
			            <a href="{{ url('admin/categoria/'.$cat->id . '/excluir') }}" class="btn btn-danger btn-sm">
			                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Excluir 
			            </a>
			        @endif
		        </td>
		    </tr>
		    @endforeach
		    </tbody>
		</table>
			{{$listcategorias->links()}}
	</div>
</div>

@stop