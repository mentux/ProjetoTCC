<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Venda;
use Shoppvel\Models\VendaItem;
use Illuminate\Support\Facades\Response;
use DB;
use DateTime;
use Shoppvel\User;


class CozinhaController extends Controller{

    public function logout($id){
        if($id != \Session::get('id')){
            return redirect()->back()->with('mensagens-danger','Erro');
        }elseif($id == \Session::get('id')){
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        \Session::forget('cozinha');
        \Session::forget('id');
        \Session::forget('role');
        \Session::forget('nome');
        return redirect('login');
        }
    }

    public function getCozinhaDetalhes($id){
        $venda = Venda::find($id);
        return view('frente.cozinha.detalhes',['venda'=>$venda]);
    }
	public function dashboard(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $pendente = Venda::where('status',1)->where('data_venda',$data_hoje)->get();
        $andamento = Venda::where('status',2)->where('data_venda',$data_hoje)->get();
        $prontos = Venda::where('status',3)->where('data_venda',$data_hoje)->get();
        $array = ['pendente'=>$pendente,'andamento'=>$andamento,'prontos'=>$prontos];
        return view('frente.cozinha.cozinha_dashboard',$array);
    }
    public function Paginacao(){
        $pendente = Venda::where('status',1)->where('data_venda',$data_hoje)->paginate(1);
        $andamento = Venda::where('status',2)->where('data_venda',$data_hoje)->paginate(1);
        $prontos = Venda::where('status',3)->where('data_venda',$data_hoje)->paginate(1);
        return Response::json(array(["pendente"=>$pendente,"andamento"=>$andamento,"prontos"=>$prontos]));
    }
	public function getPendentes(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',1)->paginate(5);
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}
    public function getPendentesHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',1)->paginate(5);
        return view('frente.cozinha.pedido',['venda'=>$venda]);
    }
	public function getAndamentos(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',2)->paginate(5);
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}
    public function getAndamentosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',2)->paginate(5);
        return view('frente.cozinha.pedido',['venda'=>$venda]);
    }
	public function getProntos(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',3)->paginate(5);
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}
    public function getProntosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y : H:i');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',3)->paginate(5);
        return view('frente.cozinha.pedido',['venda'=>$venda]);
    }
	public function putAndamento(Request $request, $id) {

        
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->status = 2;
        $pedido->save();
        
        return redirect('pedidos_andamento')->with('mensagens-sucesso', 'Status alterado com sucesso');
    }
    public function putMudaPendente($id){
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->status = 2;
        $pedido->save();
        $status = $pedido->status;
        return Response::json($status);
    }
    public function putMudaPronto($id){
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->status = 3;
        $pedido->save();
        $status_pronto = $pedido->status;
        return Response::json($status_pronto);
    }
    public function putPronto(Request $request, $id) {
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->status = 3;
        $pedido->save();
        
        return redirect('pedidos_pronto')->with('mensagens-sucesso', 'Status alterado com sucesso');
    }
    public function getItensPedidoPendentes($id){
        
        ////Itens Respectivos referentes ao Pedido
        $itens = DB::table('itens_vendas')->select('produtos.nome','produtos.imagem_nome','itens_vendas.produto_id','itens_vendas.venda_id','itens_vendas.qtde')->join('produtos','produtos.id','=','itens_vendas.produto_id')->where(['itens_vendas.venda_id' => $id])->get();
        $data = Venda::where('id_venda',$id)->select('vendas.created_at AS data')->join('mesa','mesa.id_mesa','=','vendas.id_mesa')->get();
        ////
        //Formatação Data do Pedido
        $data_nova = \Carbon\Carbon::parse($data[0]->data)->format('d/m/Y : H:i:s');
        ////
        //// Cabeçalho do Pedido
        $cabecalho = Venda::where('id_venda',$id)->select('vendas.created_at AS data','vendas.id_venda','vendas.status AS venda_status','vendas.valor_venda','mesa.numero')->join('mesa','mesa.id_mesa','=','vendas.id_mesa')->get();
        //inner join com a mesa pra pegar o numero.
        //// Retorno para o Modal
        return Response::json(array(["itens" => $itens, "cabecalho" => $cabecalho, "data" => $data_nova]));
    }  
    public function getNovosPedidosPendente(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');

        $data = Venda::where('data_venda',$data_hoje)->select('vendas.id_venda','vendas.status','vendas.created_at AS data')->join('mesa','mesa.id_mesa','=','vendas.id_mesa')->where('vendas.status',1)->get();
        $data = \Carbon\Carbon::parse($data[0]->data)->format('d/m/Y : H:i:s');

        $venda = DB::table('vendas')->select('vendas.valor_venda','vendas.status','vendas.id_venda','vendas.data_venda','mesa.numero')->join('mesa','mesa.id_mesa','=','vendas.id_mesa')->where('vendas.data_venda',$data)->where('vendas.status',1)->get();

        return Response::json(array(["venda"=>$venda,"data"=>$data]));
    }
}