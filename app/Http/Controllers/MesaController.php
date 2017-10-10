<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Mesa;
use Shoppvel\Models\Venda;
use Shoppvel\Models\VendaItem;
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
        //dd($request->id); 
        $mesa = Mesa::find($id);
        $produto = Produto::all();
        $itens = $this->carrinho->getItens();
        $total = $this->carrinho->getTotal();
        return view('frente.cardapio',['mesa'=>$mesa,'produto'=>$produto,'itens'=>$itens,'total'=>$total]);
    }

    public function getProdutoModal($id){
        $produto = Produto::find($id);
        return Response::json($produto);

    }
    public function Adicionar(Request $request) {
        $id_produto = $request->get('botao');
        $itens = $this->carrinho->getItens();
        //$carrinho_item = array($itens['CarrinhoItem']);
        try{
            foreach($itens as $item){
                //dd($item->produto->id);
                $est = Produto::find($id_produto);
                if($id_produto == null){
                    return \Redirect::back()->with('mensagens-erro', 'Erro, Não há produto Selecionado');
                }elseif($item->produto->id != $id_produto){

                    $this->carrinho->add($id_produto);
                    return \Redirect::back()->with('mensagens-sucesso', 'Produto adicionado ao carrinho 1');    
                }else{
                    if($item->produto->id == $id_produto){
                    $item->qtde++;
                    return \Redirect::back()->with('mensagens-sucesso', 'Produto adicionado ao carrinho 2'); 
                    }
                }

            }   
        }catch(Exception $e){
            return redirect()->back()
           ->with('mensagens-erro', 'Erro!!!')
           ->withInput();
        }

        if($this->carrinho->add($id_produto)){   
            return \Redirect::back()->with('mensagens-sucesso', 'Produto adicionado ao carrinho');
        }else{
            return redirect()->back()
           ->with('mensagens-danger', 'Erro não é possivel adicionar Produto desejado')
           ->withInput();    
        }
        
    }
    //dd($itens[0]->CarrinhoItem->produto->id);
            //$flag=0;//avisar se encontrou ou nn;
            //procura o id se é igual    
            /*for($i=0;$i<count($itens);$i++){
                dd($itens);
                if($itens['id'][$i]==$id_produto){
                $item->qtde++;
                        return \Redirect::back()->with('mensagens-sucesso', 'Produto adicionado ao carrinho 1');
                $flag=1;
                }
            }
            if($flag!=1){
                    $this->carrinho->add($id_produto);
                        return \Redirect::back()->with('mensagens-sucesso', 'Produto adicionado ao carrinho 1');    
            }*/

    /*public function Adicionar(Request $request){
        $id_produto = $request->get('botao');
        $itens = $this->carrinho->getItens();
        foreach($itens as $item){
        }*/
    
    public function Remover($id){
        $this->carrinho->deleteItem($id);
        if(Route::getCurrentRoute()->getPath() == 'getmesa/{id}'){
            
            return redirect()->route('cardapio');
        }else{
            return redirect()->back()->with('mensagens-sucesso', 'Produto Removido do Carrinho');
        }
    }


    public function FecharPedido(Request $request){
            $pedido = new Venda();
        
        DB::beginTransaction();
        
        $pedido->user_id = $request->id;
        $pedido->data_venda = \Carbon\Carbon::now();
        $pedido->valor_venda = $this->carrinho->getTotal();
        //$pedido->pagseguro_transaction_id = $req->transaction_id;
        $pedido->id_mesa = $request->id;
        $pedido->save();
        
        foreach ($this->carrinho->getItens() as $idx => $itemCarrinho) {
            $itemVenda = new VendaItem();
            $itemVenda->produto_id = $itemCarrinho->produto->id;
            $itemVenda->qtde = $itemCarrinho->qtde;
            $itemVenda->preco_venda = $itemCarrinho->produto->preco_venda;
                     
            $pedido->itens()->save($itemVenda);
            
            $itemCarrinho->produto->decrement('qtde_estoque', $itemCarrinho->qtde);
        }
        
       
        DB::commit();

        $this->carrinho->esvaziar();

        return redirect('/')->with('mensagens-sucesso', 'Pedido realizado com sucesso.');
        
        }
          
}