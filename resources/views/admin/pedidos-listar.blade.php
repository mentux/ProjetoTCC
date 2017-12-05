@extends('layouts.admin')

@section('conteudo')
<script type='text/javascript' src="{{ asset('bootstrap/js/jquery-ui.js') }}"></script>
<link href="{{ asset('bootstrap/css/jquery-ui.css') }}" rel="stylesheet">
  <script>
  $( function() {
    var dates = $('.datepickers').datepicker({ dateFormat: 'dd/mm/yy' }).val();
  });
  </script> 
<h2>Pedidos - {{$tipoVisao}} @if(isset($pedidos))- {{$pedidos->count()}} @endif</h2>
<form action="" method="GET">
    <div class="form-group">
        @if(!\Request::has('status'))
        @elseif($tipoVisao == 'Não Pagos')
        <input id="1" type="hidden" name="status" value="nao-sdasdasdass">
        @elseif($tipoVisao == 'Pagos')
        <input id="2" type="hidden" name="status" value="pagos">
        @else
        <input id="3" type="hidden" name="status" value="finalizados">
        @endif
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
@if(isset($pedidos))
<table class="table table-striped">
    <thead>
        <tr>
            <th>Numero do pedido</th>
            <th>Data</th>
            <th class="text-right">Mesa</th>
            <th class="text-right">Valor</th>
            <!--<th class="text-right">Status no Pagseguro</th>-->
            <th class="text-right">Status Local</th>
            <th class="text-right">Enviado / Finalizado</th>
            <!-- <th class="text-right">Id no Pagseguro</th> -->
            <th class="text-right"></th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
        <tr>
             <td>
                {{$pedido->id_venda}}
                
            </td>
            <td>
                {{$pedido->data_venda->format('d/m/Y : H:i')}}
            </td>
            <td class="text-center">
                {{$pedido->mesa->numero}}
            </td>
            <td class="text-right">
                {{number_format($pedido->valor_venda, 2, ',', '.')}}
            </td>
            <!--<td class="text-right">
                {{$pedido->status_pagamento}}
            </td>-->
            <td class="text-right small">
                @if ($pedido->pago)
                    @if ($pedido->enviado)
                        <span class="text-success">FINALIZADO</span>
                    @else
                        <span class="text-warning">PRONTO PARA ENVIAR</span>
                    @endif
                @else
                    <b class="text-warning">Aguardando atualização de status de pagamento</b>
                @endif
            </td>
            <td class="text-right small">
                {!! $pedido->enviado 
                    ? '<span class="text-success">ENVIADO / FINALIZADO</span>' 
                    : '<b class="text-warning">Aguardando atualização de status de pagamento</b>'
                !!}
            </td>
            <!--<td class="text-right small">
                @if ($pedido->pago == false)
                    {{ Form::open (['route' => ['admin.pedido.pago', $pedido->id_venda], 'method' => 'PUT']) }}
                        {{ Form::submit('Baixa de Pagamento', ['class'=>'btn btn-success btn-sm col-sm-12']) }}
                    {{ Form::close() }}
                @endif é pq quando vc acessa essas listagens,ele manda as infos por get,por isso tem um segundo if la,se vc tirar,da erro dizendo que as datas iniciais e finais estao mandando vazio,nao sei pq da isso,ele ta mandando get toda hora nn seria post ? post perde os parametros de busca put?nao testei,mas deve ser mesma coisa,pq vc ta mandando info
                @if ($pedido->pago && $pedido->enviado == false)
                    {{ Form::open (['route' => ['admin.pedido.finalizado', $pedido->id_venda], 'method' => 'PUT']) }}
                        {{ Form::submit('Marcar Finalizado', ['class'=>'btn btn-warning btn-sm col-sm-12']) }}
                    {{ Form::close() }}
                @endif
            </td>-->
            <td>
                <a class='btn btn-primary' href="{{route('admin.pedidos', $pedido->id_venda)}}">Mais detalhes</a> 
            </td>
        </tr>
        @empty
        <tr class="info">
            <td colspan="8" >
                Nenhum pedido para o status solicitado !
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif
@if(isset($pedidos))

@if(!\Request::has('status'))

{{ $pedidos->appends(["data_inicial" => $_REQUEST['data_inicial'], "data_final" => $_REQUEST['data_final']])->links() }}

@elseif(\Request::has('status') == 'nao-pagos')

{{ $pedidos->appends(["status"=>'nao-pagos',"data_inicial" => $_REQUEST['data_inicial'], "data_final" => $_REQUEST['data_final']])->links() }}

@elseif(\Request::has('status') == 'pagos')

{{ $pedidos->appends(["status"=>'pagos',"data_inicial" => $_REQUEST['data_inicial'], "data_final" => $_REQUEST['data_final']])->links() }}

@else

{{ $pedidos->appends(["status"=>'finalizados',"data_inicial" => $_REQUEST['data_inicial'], "data_final" => $_REQUEST['data_final']])->links() }}

@endif

@endif

@stop