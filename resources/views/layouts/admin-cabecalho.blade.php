<link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{asset('bootstrap/css/assets/css/style.css')}}" rel="stylesheet">
<link href="{{asset('bootstrap/css/assets/css/font-awesome.min.css') }}" rel="stylesheet">
<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>


<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">L & C - Área Administrativa.</a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
          <ul class="nav navbar-nav">
                <li class="active"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <!--<li><a href="{{route('admin.pedidos')}}">Pedidos</a></li> deixei comentado pq nos menus laterais ja tem os links de cada status dos pedidos-->
                @if(session('admin') != '')
                <li><a href="{{route('admin.categoria.listar')}}">Categorias</a></li>
                <li><a href="{{route('admin.marca.listar')}}">Marcas</a>
                <li><a href="{{route('admin.produto.listar')}}">Produtos</a>
                <li><a href="{{route('admin.mesa.listar')}}">Mesas</a>
                @endif
                @if(session('admin'))      
                @if(session('admin') == '')
                <li><a href="{{ url('/login') }}">Login</a></li>
                @else
                <li>
                <a href="{{route('admin.dashboard')}}">
                   {{ \Session::get('nome')}}
                </a>
                </li>
                <li>
                <a href="{{url('logout_admin',\Session::get('id'))}}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                </li>
                @endif
                @endif
                @if(session('admin/caixa'))      
                @if(session('admin/caixa') == '')
                <li><a href="{{ url('/login') }}">Login</a></li>
                @else
                <li>
                <a href="{{route('admin.dashboard')}}">
                   {{ \Session::get('nome_admin_caixa')}}
                </a>
                </li>
                <li>
                <a href="{{url('logout_admin_caixa',\Session::get('id_admin_caixa'))}}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                </li>
                @endif
                @endif
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<hr class="clearfix"/>
