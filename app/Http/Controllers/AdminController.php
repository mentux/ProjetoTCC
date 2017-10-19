<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Carrinho;
use Shoppvel\Models\Produto;
use Shoppvel\Models\Venda;
use Shoppvel\Models\Mesa;
use Shoppvel\Models\VendaItem ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {

    public function logout_admin(){
        \Session::forget('admin');
        \Session::forget('id');
        \Session::forget('role');
        \Session::forget('nome');
        return redirect('login');
    }

    public function getDashboard() {
            $models['qtdePedidos']['total'] = Venda::count();
            $models['qtdePedidos']['pendentes-pagamento'] = Venda::naoPagas()->count();
            $models['qtdePedidos']['pagos'] = Venda::pagas()->count();
            $models['qtdePedidos']['finalizados'] = Venda::finalizadas()->count();
            return view('admin.dashboard', $models);    
            
    }
    
    public function getPerfil() {
        return view('admin.perfil');
    }
    
    public function getPedidos(Request $req, $id = null) {
        //alterado aqui 13/10 19:38
        if ($id == null) {
            if ($req->has('status') == false) {
                $models['tipoVisao'] = 'Todos';
                $models['pedidos'] = Venda::orderBy('data_venda','DESC')->paginate(10);
            } else {
                if ($req->status == 'nao-pagos') {
                    $models['tipoVisao'] = 'Não Pagos';
                    $models['pedidos'] = Venda::where('pago',null)->orderBy('data_venda','DESC')->paginate(10);
                } else if ($req->status == 'pagos') {
                    $models['tipoVisao'] = 'Pagos';
                    $models['pedidos'] = Venda::where('pago',1)->orderBy('data_venda','DESC')->paginate(10);
                } else if ($req->status == 'finalizados') {
                    $models['tipoVisao'] = 'Finalizados/Enviados';
                    $models['pedidos'] = Venda::where('enviado',1)->orderBy('data_venda','DESC')->paginate(10);
                }
            }
            return view('admin.pedidos-listar', $models);
        }

        $models['pedido'] = Venda::find($id);
        return view('admin.pedido-detalhes', $models);
    }
    
    public function putPedidoPago(Request $request, $id) {
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->pago = TRUE;
        $pedido->save();
        
        return redirect()->route('admin.pedidos', '?status=pagos')->with('mensagens-sucesso', 'Pedido pago');
    }
    
    public function putPedidoFinalizado(Request $request, $id) {
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->enviado = TRUE;
        $pedido->save();
        
        return redirect()->route('admin.pedidos', '?status=finalizados')->with('mensagens-sucesso', 'Pedido finalizado');
    }

    public function getTodosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $models['pedidos'] = Venda::orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);
        $models['tipoVisao'] = 'Todos os pedidos de hoje';
        return view('admin.pedidos-listar', $models);
    }

        public function getPendentesHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $models['pedidos'] = Venda::where('pago',null)->orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);
        $models['tipoVisao'] = 'Não Pagos hoje';
        return view('admin.pedidos-listar', $models);
    }


     public function getPagosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $models['pedidos'] = Venda::where('pago',1)->orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);
        $models['tipoVisao'] = 'Pagos hoje';
        return view('admin.pedidos-listar', $models);
    }

    public function getFinalizadosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $models['pedidos'] = Venda::where('enviado',1)->orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);//alterado os get(); para paginate(10);
        $models['tipoVisao'] = 'Finalizados/Enviados Hoje';
        return view('admin.pedidos-listar', $models);
    }



}
