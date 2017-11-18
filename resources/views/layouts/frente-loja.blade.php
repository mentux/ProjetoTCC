<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>L & C</title>
        <!-- Bootstrap core CSS -->
        <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('bootstrap/css/animate.css')}}" rel="stylesheet">
        <link href="{{asset('bootstrap/css/bootstrap-dropdownhover.min.css')}}">
        <link href="{{asset('bootstrap/css/assets/css/font-awesome.min.css') }}" rel="stylesheet">
        {!! HTML::style('bootstrap/css/assets/css/style.css') !!}
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        
        <!-- Custom styles for this template -->
        <link href="{{asset('bootstrap/css/nav-justified.css')}}" rel="stylesheet">
        <link href="{{asset('bootstrap/css/lightbox.css')}}" rel="stylesheet">
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        
        <!--<script src="{{asset('bootstrap/js/ie10-viewport-bug-workaround.js')}}"></script>-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            @include('layouts.frente-cabecalho')
            <!-- Example row of columns -->
                @if((Route::getCurrentRoute()->getPath()) == 'getmesa/{id}' OR (Route::getCurrentRoute()->getPath()) == 'categoria/{id?}' OR(Route::getCurrentRoute()->getPath()) == 'produto/buscar')
                <div class="col-lg-2">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            </br>
                            </br>
                            <h3>Categorias</h3>
                                <a class='btn btn-primary hidden-xs' href="{{url('getmesa/'.\Session::get('id_mesa'))}}">Voltar ao Cardápio</a>
                                <br/>
                                <br/>
                                <div class="col-lg-2">
                                    <div class="dropdown"><!-- remover causo precise e fechamento depois da ul-->
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" data-hover="dropdown">
                                            Categorias
                                            <span class="caret"></span>
                                        </button>
                                        <a class='btn btn-primary hidden-sm hidden-md hidden-lg' href="{{url('getmesa/'.\Session::get('id_mesa'))}}">Voltar ao Cardápio</a>
                                        <ul class="dropdown-menu" data-hover="dropdown" aria-labelledby="dropdownMenu1">
                                            @foreach ($listcategorias as $cat)
                                            @if ($cat->categoria_id=='')
                                            <li>
                                                <a href="{{route('categoria.listar', $cat->id)}}">
                                                    {{$cat->nome}}
                                                </a>
                                            </li>
                                            @endif
                                            @endforeach
                                            <li class="dropdown">
                                                <a href="#">Sub Categorias</a>
                                                    <ul class="dropdown-menu dropdownhover-right">
                                                        @foreach ($listcategorias as $cat2)
                                                        @if ($cat2->categoria_id!='')
                                                        <li>
                                                            <a href="{{route('categoria.listar', $cat2->id)}}">
                                                                {{$cat2->nome}}
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-10">
                <br/>
                    @if((Route::getCurrentRoute()->getPath()) == 'cadastrar_cliente' OR ((Route::getCurrentRoute()->getPath()) == 'login_cliente'))  
                    @else
                    @include('layouts.messages')
                    @endif
                    @yield('conteudo')
                </div> <!-- /container -->
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/ie10-viewport-bug-workaround.js')}}"></script>
        <script src="{{asset('bootstrap/js/lightbox.js')}}"></script>
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/bootstrap-dropdownhover.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/script.js')}}"></script>
        <script src="{{asset('bootstrap/js/flip.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/menu.js')}}"></script>
    </body>
</html>
