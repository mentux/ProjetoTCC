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
        $pendente = Venda::where('status',1)->count();
        $andamento = Venda::where('status',2)->count();
        $prontos = Venda::where('status',3)->count();
        $array = ['pendente'=>$pendente,'andamento'=>$andamento,'prontos'=>$prontos];
        return view('frente.cozinha.cozinha_dashboard',$array);
    }

	public function getPendentes(){
		$venda = Venda::where('status',1)->get();
		return view('frente.cozinha.pedido',['venda'=>$venda]);

	}

	public function getAndamentos(){
		$venda = Venda::where('status',2)->get();
		return view('frente.cozinha.pedido',['venda'=>$venda]);
	}

	public function getProntos(){
		$venda = Venda::where('status',3)->get();
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
