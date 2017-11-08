<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

use Shoppvel\Models\Venda;

use Shoppvel\Models\VendaItem;

use Illuminate\Support\Facades\Response;

use DB;
use DateTime;


class CozinhaController extends Controller{

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

    ////////////////////////////////////

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


    ////////////////////////////////////


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
        $data_nova = \Carbon\Carbon::parse($data[0]->data)->format('m/d/Y : H:i:s');
        ////
        //// Cabeçalho do Pedido
        $cabecalho = Venda::where('id_venda',$id)->select('vendas.created_at AS data','vendas.id_venda','vendas.status AS venda_status','vendas.valor_venda','mesa.numero')->join('mesa','mesa.id_mesa','=','vendas.id_mesa')->get();
        //fiz inner join com a mesa pra pegar o numero.
        //// Retorno para o Modal
        return Response::json(array(["itens" => $itens, "cabecalho" => $cabecalho, "data" => $data_nova]));
    }  
}