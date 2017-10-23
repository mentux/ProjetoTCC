@extends('layouts.frente-loja')

@section('conteudo')
<div class='container'>
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Formulário de cadastro</strong></div>
            <div class="panel-body">
                <div class='container'>
                    <div class='col-lg-5 col-md-5 col-sm-10'>
                        <form class="form-horizontal" role="form" method="POST" action="">
                            {!! csrf_field() !!}
                              <div class='form-group'>
                                <div class='form-group'>
                                    <a class='btn btn-info' href="{{url('getmesa/'.\Session::get('id_mesa'))}}">Voltar ao Cardápio</a>
                                </div>
                              </div>
                              <div class='form-group'>
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                  <label class="control-label">Nome</label>

                                  <input type="text" class="form-control" placeholder="Digite seu Nome" name="name" value="{{ old('name') }}">
                                  @if ($errors->has('name'))
                                      <strong class='text-danger'>{{ $errors->first('name') }}</strong>
                                  @endif
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
                              <div class='form-group'>
                                  <div class="form-group{{ $errors->has('password_confirm') ? ' has-error' : '' }}">
                                    <label class="control-label">Confirmar Senha</label>

                                    <input type="password" class="form-control" placeholder="Digite sua senha novamente" name="password_confirm">
                                    @if ($errors->has('password_confirm'))
                                        <strong class='text-danger'>{{ $errors->first('password_confirm') }}</strong>
                                    @endif
                                  </div>
                              </div>
                              <div class='form-group'>
                                  <div class="form-group{{ $errors->has('cpf') ? ' has-error' : '' }}">
                                    <label class="control-label">CPF</label>

                                    <input type="text" class="form-control" placeholder="Digite seu cpf" name="cpf">
                                    @if ($errors->has('cpf'))
                                        <strong class='text-danger'>{{ $errors->first('cpf') }}</strong>
                                    @endif
                                  </div>
                              </div>
                              <div class='form-group'>
                                  <div class="form-group{{ $errors->has('endereco') ? ' has-error' : '' }}">
                                    <label class="control-label">Endereço</label>

                                    <input type="text" class="form-control" placeholder="Digite seu endereço" name="endereco">
                                    @if ($errors->has('endereco'))
                                        <strong class='text-danger'>{{ $errors->first('endereco') }}</strong>
                                    @endif
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="form-group">
                                      <button type="submit" class="btn btn-primary">
                                          <i class="fa fa-user-circle-o" aria-hidden="true"></i>Cadastrar
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