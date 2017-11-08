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
        <link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="{{asset('bootstrap/css/ie10-viewport-bug-workaround.css')}}" rel="stylesheet">
        <link href="{{asset('bootstrap/css/lightbox.css')}}" rel="stylesheet">
        {!! HTML::style('bootstrap/css/assets/css/style.css') !!}
        <!-- Custom styles for this template -->
        <link href="{{asset('bootstrap/css/nav-justified.css')}}" rel="stylesheet">
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
            <br><br>
            
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1"    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Cozinha
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li>
                            <a href="{{url('cozinha_dashboard')}}">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{url('pedidos_pendentes')}}">
                                Pedidos Pendentes
                            </a>
                        </li>
                        <li>
                            <a href="{{url('pedidos_andamento')}}">
                                Pedidos em andamento
                            </a>
                        </li>
                        <li>
                            <a href="{{url('pedidos_pronto')}}">
                                Pedidos Prontos
                            </a>
                        </li>
                        <li>
                            <a href="{{url('logout_cozinha')}}">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-10">
                    @include('layouts.messages')

                    @yield('cozinha')
                </div>
            </div>
        
            <!-- Site footer -->

        </div>

       


        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/ie10-viewport-bug-workaround.js')}}"></script>
        <script src="{{asset('bootstrap/js/lightbox.js')}}"></script>
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    </body>
</html>
