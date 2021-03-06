@extends('layouts.admin')

@section('conteudo')
</br>
<div class="panel panel-default ">
  	<div class="panel-heading">
    	<h3 class="panel-title">Nova Produto</h3>
  	</div>
  	<div class="panel-body">
  		@if(Session::has('mensagem_sucesso'))
			{!! mensagem_sucesso !!}
  		@endif
		<div class="form-group">
			@if(Request::is('*/editar'))
				<h3>Editar Produto {!! $produto->nome !!}</h3>
				{!! Form::model($produto, ['method'=>'PATCH', 'url'=>'admin/produto/atualizar/'.$produto->id,'files' => true]) !!}
			@else
				{{ Form::open( array('route' => 'admin.produto.salvar', 'class'=>'form-horizontal', 'files' => true)) }}
			@endif

			        {!! Form::label('categoria_id', 'Categoria', ['class'=>'col-sm-2 form-label']) !!}
			        {!! Form::select('categoria_id', $listcategorias->lists('nome','id'), null, ['class'=>'form-control', 'placeholder'=>'Categoria']) !!}<br>

			        {!! Form::label('marca_id', 'Marca', ['class'=>'col-sm-2 form-label']) !!}
			        {!! Form::select('marca_id', $marcas->lists('nome','id'), null, ['class'=>'form-control', 'placeholder'=>'Marca']) !!}<br>

					{!! Form::label('nome', 'Produto', ['class'=>'col-sm-2 forml-label']) !!}
					{!! Form::input('text', 'nome', null, ['class'=>'form-control', 'placeholder'=>'Nome']) !!}<br>

					{!! Form::label('descricao', 'Descrição', ['class'=>'col-sm-2 form-label']) !!}
					{!! Form::input('textarea', 'descricao', null, ['class'=>'form-control', '', 'placeholder'=>'Descrição']) !!}<br>

					{!! Form::label('qtde_estoque', 'Quantidade', ['class'=>'col-sm-2 form-label']) !!}
					{!! Form::input('number', 'qtde_estoque', null, ['class'=>'form-control', '', 'placeholder'=>'Quantidade']) !!}<br>

					{!! Form::label('preco_venda', 'Preço Venda', ['class'=>'col-sm-2 form-label']) !!}
					{!! Form::input('null', 'preco_venda', null, ['class'=>'form-control', '', 'placeholder'=>'Preço de Venda']) !!}<br>

					{!! Form::label('destacado', 'Destacado', ['class'=>'col-sm-2 form-label'])!!}
					{!! Form::select('destacado', ['1'=>'Sim','0'=>'Não'], null, ['class'=>'form-control']) !!}<br>
					@if(Request::is('*/editar'))
						@if($produto->imagem_nome != 'sem_imagem.jpg')
						<div class="col-md-12">
							<a style='margin-bottom:10px;' class='btn btn-danger btn-lg' href="{{url('excluir_imagem',$produto->id)}}">Excluir imagem</a>
							<img style='height:150px; width:150px; margin-bottom: 50px;' class='form-control' src="{{asset('uploads/'.$produto->imagem_nome)}}">
						</div>
						@elseif($produto->imagem_nome == 'sem_imagem.jpg' AND  Request::is('*/editar'))
							{!! Form::label('imagem_nome', 'Imagem', ['class'=>'col-md-12 col-sm-2 form-label']) !!}
							{!! Form::input('file', 'imagem_nome', 'null', ['class'=>'form-control','','placeholder'=>'Imagem do Produto']) !!}
							<br/>
						@endif
					@else
					<div class="col-md-12">{!! Form::label('imagem_nome', 'Imagem', ['class'=>'col-md-12 col-sm-2 form-label']) !!}</div>
					{!! Form::input('file', 'imagem_nome', 'null', ['class'=>'form-control','','placeholder'=>'Imagem do Produto']) !!}</div>
					<br/>
					@endif
					<div class="col-md-12">
					{!! Form::submit('Salvar', ['class'=>'btn btn-primary']) !!}

						<a href="{{Route('admin.produto.listar')}}"><div class="btn btn-success btn-sm  glyphicon glyphicon-share-alt col-md-offset-1"> <strong>Cancelar</strong></div></a>
					</div>

				{!! Form::close() !!}

		</div>	
	</div>
</div>

@stop