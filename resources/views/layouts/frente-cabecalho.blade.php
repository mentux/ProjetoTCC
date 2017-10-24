<div class="navbar navbar-inverse navbar-fixed-top" style="padding-bottom: 1px" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">L & C</a>
            @if((Route::getCurrentRoute()->getPath()) == '/' OR (Route::getCurrentRoute()->getPath()) == 'login')
            <a style="font-size:10px; margin-left:5px;" class="navbar-brand" href="{{url('/')}}">Home</a>
            @endif
        </div>
        @if((Route::getCurrentRoute()->getPath()) == 'getmesa/{id}')
        {!! Form::open(array('route' => 'produto.buscar', 'class'=>'navbar-form navbar-right')) !!} 
        <div class="form-group">
            {!! Form::text('termo-pesquisa', null,['placeholder'=>'Pesquisar',
            'class'=>'form-control']) !!}
        </div>
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        @endif
        @if((Route::getCurrentRoute()->getPath()) == 'categoria/{id?}')
        {!! Form::open(array('route' => 'produto.buscar', 'class'=>'navbar-form navbar-right')) !!} 
        <div class="form-group">
            {!! Form::text('termo-pesquisa', null,['placeholder'=>'Pesquisar',
            'class'=>'form-control']) !!}
        </div>
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        @endif
        {!! Form::close() !!}
        <div class="navbar-collapse collapse navbar-right">
        @if((Route::getCurrentRoute()->getPath()) == 'mesa_pedido/{id_pedido}')
        @else
          <ul class="nav navbar-nav">
                @if((Route::getCurrentRoute()->getPath()) == 'getmesa/{id}')
                <li><a data-toggle="modal" data-target="#carrinho_id" class="carrinho">Carrinho</a></li>
                @else
                @endif


                @if (session('cozinha') != '')
                    <li class="small">
                        <a href="{{url('cozinha_dashboard')}}">
                            {{ \Session::get('nome') }}
                        </a>
                    </li>
                @endif



                @if (session('recepcao') != '' AND (Route::getCurrentRoute()->getPath()) == '/' )
                    <li class="small">
                        <a href="#">
                            {{ \Session::get('nome_recepcao') }}
                        </a>
                    </li>
                    <li class="small">
                        <a href="{{url('logout_recepcao')}}">
                            Sair
                        </a>
                    </li>
                @endif



                @if(session('cliente') != '')
                    <li class='small'>
                        <a href="{{route('cliente.dashboard')}}">{{\Session::get('nome_cliente')}}</a>
                    </li>
                @endif
              </ul>
            </li>
        @endif
          </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>


<hr class="clearfix"/>
