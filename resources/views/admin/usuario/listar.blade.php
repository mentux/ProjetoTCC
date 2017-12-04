@extends('layouts.admin')

@section('conteudo')

<div class="panel panel-default" style="display: block !important;">
  	<div class="panel-heading">
    	<h3 class="panel-title">Lista de Usuários</h3>
  	</div>
  	<div class="panel-body">
  		@if(Session::has('mensagem_sucesso'))
			{!! 'OK' !!}
  		@endif
  		<div class="table-hover table-striped table-responsive" style="display: block !important;">
		  	<table class="table">
		  		@if(session('admin')!= '')
			    <caption> 
			        <a href="{{route('admin.usuario.form')}}" class="btn btn-primary btn-sm">
			            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Usuário
			        </a>
			    </caption>
			    @endif
			    		<th>Id</th>
			            <th>Nome</th>
			            <th>Email</th>
			            <th>Tipo</th>
			            <th>Status</th>
			            <th>Editar</th>
			            <th>Excluir</th>
			        </tr>
			    </thead>
			    @foreach($usuarios as $u)
			    <tbody>
			    <tr>
			        <td>{{$u->id}}</td>
			        <td>{{$u->name}}</td>
			        <td>{{$u->email}}</td>
			        @if($u->role == 'admin')
			        <td>Admin</td>
			        @elseif($u->role == 'cozinha')
			        <td>Cozinha</td>
			        @elseif($u->role == 'recepcao')
			        <td>Recepção</td>
			        @elseif($u->role == 'admin/caixa')
			        <td>Admin/Caixa</td>
			        @endif
			        @if($u->status == 1)
			        <td class="text-danger">Offline</td>
			        @else
			        <td class="text-success">Online</td>
			        @endif
			        @if(\Session::get('id_admin_caixa') != $u->id AND session('admin') == '')
			        <td class='text-danger' >
			            Não tem permissão de alterar as informações de outros usuários
			        </td>
			        @else
			        <td>
			            <a href="{{route('admin.usuario.form.atualizar',$u->id)}}" class="btn btn-info btn-sm">
			                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <strong>Editar</strong> 
			            </a>
			        </td>
			        @endif
			        @if(\Session::get('id') == $u->id)
				    <td class="text-danger">
						Não pode deletar a sí mesmo
			        </td>
			        @else
			        @if(session('admin') == '')
			        <td class="text-danger">
						Não tem permissão para excluir usuários.
			        </td>
			        @else
			        <td>
						<a href="{{route('admin.usuario.excluir',$u->id)}}" class="btn btn-danger btn-sm">
			                <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> <strong>Excluir</strong> 
			            </a>
			        </td>
			        @endif
			        @endif
			    </tr>
			    </tbody>
			    @endforeach
			</table>
			{{ $usuarios->links() }}
		</div>
	</div>
</div>
@endsection