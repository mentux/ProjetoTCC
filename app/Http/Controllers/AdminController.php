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
                $models['pedidos'] = Venda::orderBy('data_venda','DESC')->get();
            } else {
                if ($req->status == 'nao-pagos') {
                    $models['tipoVisao'] = 'Não Pagos';
                    $models['pedidos'] = Venda::where('pago',0)->orderBy('data_venda','DESC')->get();
                } else if ($req->status == 'pagos') {
                    $models['tipoVisao'] = 'Pagos';
                    $models['pedidos'] = Venda::where('pago',1)->orderBy('data_venda','DESC')->get();
                } else if ($req->status == 'finalizados') {
                    $models['tipoVisao'] = 'Finalizados/Enviados';
                    $models['pedidos'] = Venda::where('enviado',1)->orderBy('data_venda','DESC')->get();
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

    public function listarMesasOcupadas(){
        $mesas = DB::table('mesa')->select('mesa.id_mesa','mesa.numero','vendas.status','vendas.pago','vendas.enviado')->join('vendas','vendas.id_mesa','=','mesa.id_mesa')->where('vendas.status',3)->where('vendas.pago',1)->where('vendas.enviado',1)->where('mesa.status',2)->get();
        return view('admin.mesas_ocupadas',['mesas'=>$mesas]);
    }

    public function putLiberarMesa(Request $request, $id) {
        $mesa = Mesa::find($id);
        
        if ($mesa == null) {
            return back()->withErrors('Mesa não encontrada');
        }
        
        $mesa->status = 1;
        $mesa->save();
        
        return redirect('mesas_ocupadas')->with('mensagens-sucesso', 'Mesa liberada');
    }
}
