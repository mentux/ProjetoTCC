@extends('layouts.admin')

@section('conteudo')

<div class="panel panel-default ">
  	<div class="panel-heading">
    	<h3 class="panel-title">Novo Usuário</h3>
  	</div>
  	<div class="panel-body">
  		@if(Session::has('mensagem_sucesso'))
			{!! 'OK' !!}
  		@endif
  		<form action=""  method="POST">
  			{!! csrf_field() !!}
  			@if(Request::is('*/form'))
  			<div class='form-group'>
		        <div class="form-group">
		          	<label class="control-label">Tipo de usuário</label>

		          	<select class="form-control" name="tipo_user">
		          		<option value="cozinha">Cozinha</option>
		          		<option value="recepcao">Recepção</option>
		          		<option value="admin/caixa">Admin/Caixa</option>
		          	</select>
		          	@if ($errors->has('tipo_user'))
		              <strong class='text-danger'>{{ $errors->first('tipo_user') }}</strong>
		          	@endif
		        </div>
		    </div>
  			@endif
  			@if(isset($usuario) AND $usuario->role == 'admin/caixa' AND session('admin/caixa') != '')
  			<div class='form-group'>
		        <strong>Admin/Caixa</strong>
		    </div>
  			@elseif(isset($usuario) AND $usuario->role != \Session::get('role'))
			<div class='form-group'>
		        <div class="form-group">
		          	<label class="control-label">Tipo de usuário</label>

		          	<select class="form-control" name="tipo_user">
		          		<option @if(isset($usuario) AND $usuario->role == 'cozinha') selected @endif value="cozinha">Cozinha</option>
		          		<option @if(isset($usuario) AND $usuario->role == 'recepcao') selected @endif  value="recepcao">Recepção</option>
		          		<option @if(isset($usuario) AND $usuario->role == 'admin/caixa') selected @endif  value="admin/caixa">Admin/Caixa</option>
		          	</select>
		          	@if ($errors->has('tipo_user'))
		              <strong class='text-danger'>{{ $errors->first('tipo_user') }}</strong>
		          	@endif
		        </div>
		    </div>
		    @elseif(isset($usuario) AND $usuario->role == 'admin' AND !Request::is('*/form'))
		    <div class='form-group'>
		        <strong>Admin</strong>
		    </div>
		    @endif
	        <div class='form-group'>
	            <div class="form-group">
	              <label class="control-label">Nome</label>

	              <input type="text" class="form-control" placeholder="Digite seu Nome" name="name" @if(isset($usuario)) value="{{$usuario->name}}" @else value="{{old('name')}}"  placeholder="Digite seu Nome" @endif>
	              @if ($errors->has('name'))
	                  <strong class='text-danger'>{{ $errors->first('name') }}</strong>
	              @endif
	            </div>
	        </div>
	        @if(Request::is('*/form'))
	        <div class='form-group'>
	            <div class="form-group">
	              <label class="control-label">E-mail</label>

	              <input type="email" class="form-control" name="email" @if(isset($usuario)) value="{{$usuario->email}}" @else value="{{old('email')}}"  placeholder="Digite seu E-mail" @endif>
	              @if ($errors->has('email'))
	                  <strong class='text-danger'>{{ $errors->first('email') }}</strong>
	              @endif
	            </div>
	        </div>
	        @endif
	        @if(Request::is('*/form'))
	        <div class='form-group'>
	            <div class="form-group">
	              <label class="control-label">Senha</label>

	              <input type="password" class="form-control" placeholder="Digite uma Senha" name="password" value="{{ old('password') }}">
	              @if ($errors->has('password'))
	                  <strong class='text-danger'>{{ $errors->first('password') }}</strong>
	              @endif
	            </div>
	        </div>
	        @endif
	        @if(Request::is('*/form'))
	        <div class='form-group'>
	            <div class="form-group">
	              <label class="control-label">CPF</label>

	              <input type="text" class="form-control"  name="cpf" @if(isset($usuario)) value="{{$usuario->cpf}}" @else value="{{old('cpf')}}"  placeholder="Digite seu CPF" @endif>
	              @if ($errors->has('cpf'))
	                  <strong class='text-danger'>{{ $errors->first('cpf') }}</strong>
	              @endif
	            </div>
	        </div>
	        @endif
	        <div class='form-group'>
	            <div class="form-group">
	              <label class="control-label">Endereço</label>

	              <input type="text" class="form-control" name="endereco" @if(isset($usuario)) value="{{$usuario->endereco}}" @else value="{{old('endereco')}}"  placeholder="Digite seu Endereço" @endif>
	              @if ($errors->has('endereco'))
	                  <strong class='text-danger'>{{ $errors->first('endereco') }}</strong>
	              @endif
	            </div>
	        </div>
	        <div class="form-group">
	        	<div class="form-group">
	        		<input type="submit" class="btn btn-primary" @if(Request::is('*/form')) value="Cadastrar" @else value="Atualizar" @endif>

					<a href="{{Route('admin.usuarios')}}"><div class="btn btn-success  glyphicon glyphicon-share-alt">Cancelar </div></a>
				</div>
			</div>
		</form>	
	</div>
</div>
@stop