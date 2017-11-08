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
         $this->validate($request,[
        'numero'=>'required|numeric',
        ],[
                'numero.required'=>'É nescessário preencher o campo Número',
                'numero.numeric'=>'O campo Número deve ser númerico',
        ]);
        if($request->numero < 0){
            return redirect()->back()->with('mensagens-danger', 'Não é possível deixar o campo Número com valor negativo.');
        }
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
        $this->validate($request,[
        'numero'=>'required|numeric',
        ],[
                'numero.required'=>'É nescessário preencher o campo Número',
                'numero.numeric'=>'O campo Número deve ser númerico',
        ]);
        if($request->numero < 0){
            return redirect()->back()->with('mensagens-danger', 'Não é possível deixar o campo Número com valor negativo.');
        }

        if(Mesa::find($id)->update($data)){
           return redirect()->action('MesaController@listar')->with('mensagens-sucesso', 'Atualizado com Sucesso!');
        } else {
           return redirect()->back()
           ->with('mensagens-erro', 'Erro!!!')
           ->withInput();
        }
    }
    function excluir($id) {
        $mesa = Mesa::find($id);
        if($mesa->status == 2){
            return redirect('admin/mesa/listar')->with('mensagens-danger','A mesa esta reservada');
        }else{
            $mesa->delete();
            \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
            return redirect()->action('MesaController@listar');
        }
        
    }

    public function excluir_mesa_selecionar($id){
        $mesa = Mesa::find($id);
        return view('admin.mesas.mesa-excluir',['mesa'=>$mesa]);
    }

    public function getMesaId(Request $request,$id){
        $mesa = Mesa::find($id);
        $consulta = Mesa::where('id_mesa',$id)->count();
        if($consulta == 0){
            return redirect('/');
        
        }elseif($mesa->status == 1){
           return redirect('/')->with('mensagens-danger','Essa mesa não foi reservada'); 
       }else{
        $produto = Produto::all();
        $produto_destacado = Produto::where('destacado',1)->get();
        $itens = $this->carrinho->getItens();
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
        $this->validate($request,[
                'quant'=>'integer|required'
            ],[
                'quant.integer'=>'Apenas números',
                'quant.required'=>'É obrigatório o Preenchimento do Campo Quantidade'
            ]);
        $id_mesa = \Session::get('id_mesa');
        $id_produto = $request->get('botao');
        $quantidade_form = $request->input('quant');
        $itens = $this->carrinho->getItens();

        if($quantidade_form <= 0){
            return redirect('getmesa/'.$id_mesa)->with('mensagens-danger', 'Não é possível adicionar um produto com quantidade inferior a 0 no carrinho.');  
        }

        if($estoque = Produto::find($id_produto)->qtde_estoque - $quantidade_form = intval($quantidade_form)){
            if($estoque < 0){
                $estoque = Produto::find($id_produto)->qtde_estoque;
                return redirect('getmesa/'.$id_mesa)->withErrors('Desculpe o Incoviniente, Mas a Quantidade do Produto(s) Escolhida e Maior que o Estoque: '. $estoque);
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
        //cara,vc mexeu nesse metodo? pq ele nao ta dando mais aquele pt,ta gerando certinho,vou fazer mais uns testes aqui antes de dormir,nao mexi muito,fiquei vendo uns videos de airsoft pq hoje vo atirar de novo com a m4 predator la no palladium,to empolgado XD
        $itens = $this->carrinho->getItens();

        if(count($itens) == 0){
            return redirect()->back()->with('mensagens-danger', 'Nenhum produto adicionado no carrinho.');
        }else{
        $pedido = new Venda();
        DB::beginTransaction();
        if(\Session::get('id_cliente') == ''){
            $pedido->user_id = null;
        }else{
            $pedido->user_id = \Session::get('id_cliente');
        }
        $pedido->data_venda = \Carbon\Carbon::now();
        $pedido->valor_venda = $this->carrinho->getTotal();
        $pedido->id_mesa = \Session::get('id_mesa');
        $pedido->status = 1;
        $pedido->save();
        foreach ($this->carrinho->getItens() as $idx => $itemCarrinho) {
            $itemVenda = new VendaItem();
            $itemVenda->produto_id = $itemCarrinho->produto->id;
            $itemVenda->qtde = $itemCarrinho->qtde;
            $itemVenda->preco_venda = $itemCarrinho->produto->preco_venda;
             
            $prod = Produto::find($itemCarrinho->produto->id);
            $prod->qtde_estoque;
            $item=$prod;
            if($result = $item->qtde_estoque - $itemVenda->qtde){
                if($itemCarrinho->qtde == 0){
                    return redirect('getmesa/'.\Session::get('id_mesa'))->with('mensagens-danger', 'Não foi possível fechar o pedido,pois tem produto(os) com quantidade zero no carrinho');

                }
                if($result < 1){
                    return redirect('getmesa/'.\Session::get('id_mesa'))->with('mensagens-danger', 'Não foi possível fechar o pedido,pois ultrapassou a quantidade de estoque dos produtos adicionados no carrinho.');
                }   
            }
            $pedido->itens()->save($itemVenda);
            $itemCarrinho->produto->decrement('qtde_estoque', $itemCarrinho->qtde);
                     
                
               
        }
       
        DB::commit();

        $this->carrinho->esvaziar();

        return redirect('mesa_pedido/'.$pedido->id_venda)->with('mensagens-sucesso', 'Pedido realizado com sucesso.');
        }   
    }
    public function IncrementDelete($id){
        $itens = $this->carrinho->getItens();
        $total = $this->carrinho->getTotalCarrinho();
        
        foreach($itens as $i => $item){
            if($id == $itens[$i]->produto->id){
                $itens[$i]->qtde += 1;
                $teste=$itens[$i]->produto->preco_venda;
                $total+=$teste;
                return Response::json(number_format($total,2,',','.'));
            }else{
                while($id != $itens[$i]->produto->id){
                    $i+=1;
                    if(isset($itens[$i])){
                        if($id == $itens[$i]->produto->id){
                            $itens[$i]->qtde += 1;
                            $teste=$itens[$i]->produto->preco_venda;
                            $total+=$teste;
                            return Response::json(number_format($total,2,',','.'));
                        }
                    }
                }
            }
        }    
    }

    public function Decrement($id){
        $itens = $this->carrinho->getItens();
        $total = $this->carrinho->getTotalMenos();
        foreach($itens as $i => $item){
            if($id == $itens[$i]->produto->id){
                $itens[$i]->qtde -= 1;
                $teste=$itens[$i]->produto->preco_venda;
                $total-=$teste;
                return Response::json(number_format($total,2,',','.'));
            }else{
                while($id != $itens[$i]->produto->id){
                    $i+=1;
                    if(isset($itens[$i])){
                        if($id == $itens[$i]->produto->id){
                            $itens[$i]->qtde -= 1;
                            $teste=$itens[$i]->produto->preco_venda;
                            $total-=$teste;
                            return Response::json(number_format($total,2,',','.'));
                        }
                    } 
                }
            }
        }    
    }

    public function MesaPedido($id_pedido){
        $pedido = Venda::find($id_pedido);
        return view('frente.mesa_pedido',['pedido'=>$pedido]);
    }

    public function MesaVolteSempre(){
        $id = \Session::get('id_mesa');
        $pegar_mesa = Mesa::find($id);
        \Session::forget('cliente');
        \Session::forget('id_cliente');
        \Session::forget('role_cliente');
        \Session::forget('nome_cliente');
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