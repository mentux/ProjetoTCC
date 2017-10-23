@extends('layouts.frente-loja')

@section('conteudo')

<div class='container'>
	@if(Session::has('login_error'))
	<div class='alert alert-danger'>
		<p style='margin-bottom:10px;' class='text-danger text-center'>{{Session::get('login_error')}}</p>
	</div>
	@endif
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Formulário de login</strong></div>
            <div class="panel-body">
                <div class='container'>
                    <div class='col-lg-5 col-md-5 col-sm-10'>
                        <form class="form-horizontal" role="form" method="POST" action="">
                            {!! csrf_field() !!}
                              <div class='form-group'>
                                <div class='form-group'>
                                    <a class='btn btn-primary' href="{{url('getmesa/'.\Session::get('id_mesa'))}}">Voltar ao Cardápio</a>
                                </div>
                              </div>
                              <div class='form-group'>
                                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label">E-mail</label>

                                    <input type="email" class="form-control" placeholder="Digite seu E-mail" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <strong class='text-danger'>{{ $errors->first('email') }}</strong>
                                    @endif
                                  </div>
                              </div>
                              <div class='form-group'>
                                  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label">Senha</label>

                                    <input type="password" class="form-control" placeholder="Crie uma Senha" name="password">
                                    @if ($errors->has('password'))
                                        <strong class='text-danger'>{{ $errors->first('password') }}</strong>
                                    @endif
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="form-group">
                                      <button type="submit" class="btn btn-info">
                                          <i class="fa fa-user-circle-o" aria-hidden="true"></i>Entrar
                                      </button>
                                  </div>
                              </div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection