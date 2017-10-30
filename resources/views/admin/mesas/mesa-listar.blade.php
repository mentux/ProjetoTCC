@extends('layouts.admin')

@section('conteudo')

<div class="panel panel-default ">
  	<div class="panel-heading">
    	<h3 class="panel-title">Lista de Mesas</h3>
  	</div>
  	<style>
  		td{
  			text-align:center;
  		}
  		th{
  			text-align: center;
  		}
  	</style>
  	<div class="panel-body">
  	@if(Session::has('mensagem_sucesso'))
		{!! 'OK' !!}
  	@endif
	  	<table class="table table-hover table-striped">
		    <caption> 
		        <a href="{{ route('admin.mesa.criar') }}" class="btn btn-primary btn-sm">
		            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nova Mesa 
		        </a>
		    </caption>
		    <thead>
		        <tr>
		            <th>id</th>
		            <th>Número</th>
		            <th style="margin-right: 10px;">Editar</th>
		            <th class="pull-left">Excluir</th>
		        </tr>
		    </thead>
		    <tbody>
		    @foreach($mesas as $mesa)
		    <tr>
		        <td>{{$mesa->id_mesa}}</td>
		        <td>{{$mesa->numero}}</td>
		        
		        <td>
		            <a  href="{{ url('admin/mesa/'.$mesa->id_mesa . '/editar') }}" class="btn btn-info btn-sm">
		                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <strong>Editar</strong> 
		            </a>		            
		        </td>
		        <td class="pull-left">
		        	<a href="{{ url('excluir_mesa_selecionar',$mesa->id_mesa)}}" class="btn btn-danger btn-sm">
		                <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> <strong>Excluir</strong> 
		            </a>
		        </td>
		    </tr>
		    @endforeach
		    </tbody>
		</table>
		<!--Colocar os links para realizar o paginate -->
		{{ $mesas->links() }}
	</div>
</div>

@stop