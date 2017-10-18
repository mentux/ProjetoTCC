<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Mesa;
use Shoppvel\Models\Venda;
use Shoppvel\Models\VendaItem;
use Shoppvel\Models\CarrinhoItem;
use Shoppvel\Models\Carrinho;
use Shoppvel\Models\Produto;
use Illuminate\Support\Facades\Response;
use Shoppvel\Providers\RouteServiceProvider;
use Route;
use DB;


class MesaController extends Controller{
     private $carrinho = null;

    function __construct() {
        parent::__construct();
        $this->carrinho = new Carrinho();
    }

    
    function listar() {
        $mesas['mesas'] = Mesa::paginate(10);
            return view('admin.mesas.mesa-listar', $mesas);
    }
    public function mesa_form(){ 
    	return view('admin.mesas.mesa');
    }
    public function salvar(Request $request){
        $consulta = Mesa::where('numero',$_REQUEST['numero'])->count();
        if($consulta == 1){
            return redirect()->back()
           ->with('mensagens-danger', 'Erro ao Atualizar a Mesa, Já Existe!!!')
           ->withInput();
        }else{
            $mesa = new Mesa();
            $mesa->create($request->all());
            \Session::flash('mensagens-sucesso', 'Cadastrado com Sucesso');
            return $this->listar();
        }
    }
    function editar($id) {
        $mesas['mesa'] = Mesa::find($id);
            return view('admin.mesas.mesa', $mesas);
    }
    public function atualizar(Request $request, $id) {
        $data = $request->all();
        if(Mesa::find($id)->update($data)){
           return redirect()->action('MesaController@listar')->with('mensagens-sucesso', 'Atualizado com Sucesso!');
        } else {
           return redirect()->back()
           ->with('mensagens-erro', 'Erro!!!')
           ->withInput();
        }
    }
    function excluir($id) {
        $mesas['mesa'] = Mesa::find($id)->delete();
        \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
            return redirect()->action('MesaController@listar');
    }

    public function getMesaId(Request $request,$id){ 
        $mesa = Mesa::find($id);
        if($mesa->status == 1){
           return redirect('/')->withErrors('Essa mesa não foi reservada'); 
       }else{
        $produto = Produto::all();
        $produto_destacado = Produto::where('destacado',1)->get();
        $itens = $this->carrinho->getItens();// o esquema funciona como se fosse esse carrinho
        $total = $this->carrinho->getTotal();
        \Session::put('id_mesa',$id);
        return view('frente.cardapio',['mesa'=>$mesa,'produto'=>$produto,'itens'=>$itens,'total'=>$total,'produto_destacado'=>$produto_destacado]);
       }
        
    }

    public function ReservarMesa($id){
        $mesa = Mesa::find($id);
        $mesa->status = 2;
        $mesa->save();
        return redirect('/')->with('mensagens-sucesso','Mesa reservada com sucesso');
        
        
    }

    public function getProdutoModal($id){
        $produto = Produto::find($id);
        return Response::json($produto);

    }
    public function Adicionar(Request $request) {
        $id_mesa = \Session::get('id_mesa');
        $id_produto = $request->get('botao');
        $quantidade_form = $request->input('quant');
        $itens = $this->carrinho->getItens();

        if($estoque = Produto::find($id_produto)->qtde_estoque - $quantidade_form = intval($quantidade_form)){
            if($estoque < 0){
                $estoque = Produto::find($id_produto)->qtde_estoque;
                return redirect('getmesa/'.$id_mesa)->withErrors('Desculpe o Incoviniente, Mas a Quantidade do Produto Escolhida e Maior que o Estoque: '. $estoque);
            }
        }elseif($estoque == -1){
            return back()->withErrors('Desculpe o Incoviniente, Não Temos mais Estoque para o Produto desejado :"(');
        }
        $est = Produto::find($id_produto)->qtde_estoque;
        if ($id_produto == null){
            return redirect('getmesa/'.$id_mesa)->with('mensagens-erro', 'erro');
        }
        foreach($itens as $i => $item){
            if($id_produto == $itens[$i]->produto->id){
                $itens[$i]->qtde += $quantidade_form;
            return redirect('getmesa/'.$id_mesa)->with('mensagens-sucesso', 'Produto adicionado com sucesso');
            }else{
                while($itens[$i]->produto->id != $id_produto){
                    $i+=1;
                    if(isset($itens[$i])){
                        if($id_produto == $itens[$i]->produto->id){
                            $itens[$i]->qtde += $quantidade_form;
                            return redirect('getmesa/'.$id_mesa)->with('mensagens-sucesso', 'Produto adicionado com sucesso');  
                        }
                    }else{
                      $this->carrinho->add($id_produto, $quantidade_form);
                      return redirect('getmesa/'.$id_mesa)->with('mensagens-sucesso', 'Produto adicionado com sucesso');  
                    }  
                }
            }
        }
        if($request->get('qtde') <= Produto::find($id_produto)->qtde_estoque){
            $this->carrinho->add($id_produto, $quantidade_form);
                return redirect('getmesa/'.$id_mesa)->with('mensagens-sucesso', 'Produto adicionado com sucesso');
        }

        return redirect('getmesa/'.$id_mesa)->with('mensagens-sucesso', 'Produto adicionado com sucesso 3'. $quantidade_form);
    }
    public function Remover($id){
    
        $this->carrinho->deleteItem($id);
        if(Route::getCurrentRoute()->getPath() == 'getmesa/{id}'){
            
            return redirect()->route('cardapio');
        }else{
            return redirect()->back()->with('mensagens-sucesso', 'Produto Removido do Carrinho');
        }
    }


    public function FecharPedido(Request $request){
        $itens = $this->carrinho->getItens();
        if(count($itens) == 0){
            return redirect()->back()->with('mensagens-danger', 'Nenhum produto adicionado no carrinho.');
        }else{
        $pedido = new Venda();
        DB::beginTransaction();
        $pedido->user_id = \Session::get('id_mesa');
        $pedido->data_venda = \Carbon\Carbon::now();
        $pedido->valor_venda = $this->carrinho->getTotal();
        //$pedido->pagseguro_transaction_id = $req->transaction_id;
        $pedido->id_mesa = \Session::get('id_mesa');
        $pedido->status = 1;
        $pedido->save();
        
        foreach ($this->carrinho->getItens() as $idx => $itemCarrinho) {
            $itemVenda = new VendaItem();
            $itemVenda->produto_id = $itemCarrinho->produto->id;
            $itemVenda->qtde = $itemCarrinho->qtde;
            $itemVenda->preco_venda = $itemCarrinho->produto->preco_venda;
            
            $itemCarrinho = Produto::find($itemCarrinho->produto->id);
            $itemCarrinho->qtde_estoque;
            $item=$itemCarrinho;
            $result=0;
    
            if($result = $item->qtde_estoque - $itemVenda->qtde){

                if($result < 0){
                    return redirect('getmesa/'.\Session::get('id_mesa'))->with('mensagens-danger', 'Quantidade Escolhida é maior que o estoque: '. $itemCarrinho->qtde_estoque);
                }
            }else{
                $pedido->itens()->save($itemVenda);
                $itemCarrinho->produto->decrement('qtde_estoque', $itemCarrinho->qtde);
            }
        }
        
       
        DB::commit();

        $this->carrinho->esvaziar();

        return redirect('mesa_pedido/'.$pedido->id_venda)->with('mensagens-sucesso', 'Pedido realizado com sucesso.');
        }
        
        
    }

    public function MesaPedido($id_pedido){
        $pedido = Venda::find($id_pedido);
        return view('frente.mesa_pedido',['pedido'=>$pedido]);
    }

    public function MesaVolteSempre(){
        $id = \Session::get('id_mesa');
        $pegar_mesa = Mesa::find($id);
        return view('frente.volte_sempre',compact('pegar_mesa'));
    }

    public function VolteSempreLiberar($id){
        $id = \Session::get('id_mesa');
        $mesa = Mesa::find($id);
        $mesa->status = 1;
        $mesa->save();
        return redirect('volte_sempre');

    }         
}