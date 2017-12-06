@extends('layouts.frente-loja')

@section('conteudo')
<div class="col-md-6 col-md-offset-4">
    <div class="panel panel-success">
        <div class="panel-heading">Liberar Cardápio</div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{route('reservar.numero.form.post')}}">
                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('numero') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Número Da Mesa</label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="numero" value="{{ old('numero') }}">

                        @if ($errors->has('numero'))
                        <span class="help-block">
                            <strong>{{ $errors->first('numero') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in"></i>Liberar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
