<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

use Shoppvel\Models\Venda;


class CozinhaController extends Controller{

    public function getCozinhaDetalhes($id){
        $venda = Venda::find($id);
        return view('frente.cozinha.detalhes',['venda'=>$venda]);
    }


	public function dashboard(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $pendente = Venda::where('status',1)->where('data_venda',$data_hoje)->count();
        $andamento = Venda::where('status',2)->where('data_venda',$data_hoje)->count();
        $prontos = Venda::where('status',3)->where('data_venda',$data_hoje)->count();
        $array = ['pendente'=>$pendente,'andamento'=>$andamento,'prontos'=>$prontos];
        return view('frente.cozinha.cozinha_dashboard',$array);
    }

	public function getPendentes(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',1)->get();
		return view('frente.cozinha.pedido',['venda'=>$venda]);

	}

    public function getPendentesHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',1)->get();
        return view('frente.cozinha.pedido',['venda'=>$venda]);

    }

	public function getAndamentos(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',2)->get();
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}

    public function getAndamentosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',2)->get();
        return view('frente.cozinha.pedido',['venda'=>$venda]);
    }

	public function getProntos(){
		$venda = Venda::orderBy('id_venda','DESC')->where('status',3)->get();
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}

    public function getProntosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $venda = Venda::orderBy('id_venda','DESC')->where('data_venda',$data_hoje)->where('status',3)->get();
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

    public function putPronto(Request $request, $id) {
        $pedido = Venda::find($id);
        
        if ($pedido == null) {
            return back()->withErrors('Pedido não encontrado!');
        }
        
        $pedido->status = 3;
        $pedido->save();
        
        return redirect('pedidos_pronto')->with('mensagens-sucesso', 'Status alterado com sucesso');
    }


    
}
