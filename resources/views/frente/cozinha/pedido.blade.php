@extends('layouts.cozinha_cabecalho')

@section('cozinha')
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
    <script type='text/javascript' src="{{ asset('bootstrap/js/jquery-ui.js') }}"></script>
    <link href="{{ asset('bootstrap/css/jquery-ui.css') }}" rel="stylesheet">
    <script>
    $( function() {
        var dates = $('.datepickers').datepicker({ dateFormat: 'dd/mm/yy' }).val();
    });
  </script>
    @if((Route::getCurrentRoute()->getPath()) == 'pedidos_pendentes')
    <h2 class="container">Pedidos Pendentes </h2>
    <div class="container">
      <form action="" method="GET">
            <div class="form-group">
                <label class="control-label">Data Inicial</label>
                 <input placeholder="dia/mes/ano" autocomplete="off"  type="text" class="form-control datepickers" name="data_inicial" value="">
            </div>
            <div class="form-group">
                <label class="control-label">Data Final</label>
                 <input placeholder="dia/mes/ano"  autocomplete="off" type="text" class="form-control datepickers" name="data_final" value="">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </div>
        </form>
    </div> 
    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_andamento')
    <h2 class="container">Pedidos em Andamento</h2>
    <div class="container">
      <form action="" method="GET">
            <div class="form-group">
                <label class="control-label">Data Inicial</label>
                 <input placeholder="dia/mes/ano"  type="text" class="form-control datepickers" name="data_inicial" value="">
            </div>
            <div class="form-group">
                <label class="control-label">Data Final</label>
                 <input placeholder="dia/mes/ano" type="text" class="form-control datepickers" name="data_final" value="">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </div>
        </form>
    </div> 
    @elseif((Route::getCurrentRoute()->getPath()) == 'pedidos_pronto')
    <h2 class="container">Pedidos Prontos</h2>
    <div class="container">
      <form action="" method="GET">
            <div class="form-group">
                <label class="control-label">Data Inicial</label>
                 <input placeholder="dia/mes/ano"  type="text" class="form-control datepickers" name="data_inicial" value="">
            </div>
            <div class="form-group">
                <label class="control-label">Data Final</label>
                 <input placeholder="dia/mes/ano" type="text" class="form-control datepickers" name="data_final" value="">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </div>
        </form>
    </div> 
    @endif
@if(isset($venda))
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Numero</th>
                <th>Mesa</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($venda as $pedido)
            <tr> 
                <td>{{$pedido->data_venda->format('d/m/Y : H:i')}}</td>
                <td>
                    {{$pedido->id_venda}}
                </td>  
                <td>
                    {{$pedido->mesa->numero}}
                </td>
                @if($pedido->status === 1)
                <td>
                    Pendente
                </td>
                @elseif($pedido->status === 2)
                <td>
                    Em andamento
                </td>
                @else
                <td>
                    Pronto
                </td>
                @endif
                <td class="text-left small">
                 <a href="{{route('cozinha.detalhes',$pedido->id_venda)}}" class="btn btn-primary" >Mais detalhes</a>
                </td>
                @if($pedido->status == 1)
                <td class="text-left small">
                    {{ Form::open (['route' => ['status_pendente', $pedido->id_venda], 'method' => 'PUT']) }}
                            {{ Form::submit('Preparar', ['class'=>'btn btn-warning']) }}
                    {{ Form::close() }}
                </td>
                @elseif($pedido->status == 2)
                <td class='text-left small'>
                {{ Form::open (['route' => ['status_pronto', $pedido->id_venda], 'method' => 'PUT']) }}
                            {{ Form::submit('Finalizar', ['class'=>'btn btn-warning']) }}
                    {{ Form::close() }}
                </td>
                @else
                <td class="text-left small">
                    
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(isset($_REQUEST['data_inicial'],$_REQUEST['data_final']))
        {{ $venda->appends(["data_inicial" => $_REQUEST['data_inicial'], "data_final" => $_REQUEST['data_final']])->links() }}

    @else
        {{ $venda->appends(["data_inicial" => \Carbon\Carbon::today()->format('d/m/Y'), "data_final" => \Carbon\Carbon::today()->format('d/m/Y')])->links() }}
    @endif
</div>
@endif
@stop