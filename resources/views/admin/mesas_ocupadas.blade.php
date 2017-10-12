@extends('layouts.admin')

@section('conteudo')
<h2>Mesas Ocupadas</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Mesa</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($mesas as $m)
        <tr>   
            <td>
                {{$m->numero}}
            </td>
            <td class="text-left small">
                {{ Form::open (['route' => ['liberar_mesa', $m->id_mesa], 'method' => 'PUT']) }}
                        {{ Form::submit('Liberar mesa', ['class'=>'btn btn-warning']) }}
                {{ Form::close() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@stop