<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Carrinho;
use Shoppvel\Models\Produto;
use Shoppvel\Models\Venda;
use Shoppvel\Models\Mesa;
use Shoppvel\User;
use Shoppvel\Models\VendaItem ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller {

    public function logout_admin($id){
        if($id != \Session::get('id')){
            return redirect()->back()->with('mensagens-danger','Erro');
        }elseif($id == \Session::get('id') ){
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        \Session::forget('admin');
        \Session::forget('id');
        \Session::forget('role');
        \Session::forget('nome');
        \Session::flush();
        return redirect('login'); 
        }  
    }

    public function logout_admin_caixa($id){
        if($id != \Session::get('id_admin_caixa')){
            die('aqui');
            return redirect()->back()->with('mensagens-danger','Erro');
        }elseif($id == \Session::get('id_admin_caixa')){
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        \Session::forget('admin/caixa');
        \Session::forget('id_admin_caixa');
        \Session::forget('nome_admin_caixa');
        \Session::forget('role_admin_caixa');
        return redirect('login');
        }
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
        $models['tipoVisao'] = 'Pagos hoje';
        $models['pedidos'] = Venda::where('pago',1)->orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);
        return view('admin.pedidos-listar', $models);
    }

    public function getFinalizadosHoje(){
        $data_hoje = \Carbon\Carbon::today()->parse()->format('d/m/Y');
        $models['pedidos'] = Venda::where('enviado',1)->orderBy('data_venda','DESC')->where('data_venda',$data_hoje)->paginate(10);//alterado os get(); para paginate(10);
        $models['tipoVisao'] = 'Finalizados/Enviados Hoje';
        return view('admin.pedidos-listar', $models);
    }

    public function listarClientes(){
        $cliente = User::orderBy('name')->where('role','cliente')->paginate(10);
        return view('admin.cliente.listar',['cliente'=>$cliente]);
    }

    public function atualizarCliente(Request $request,$id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $cliente = User::find($id);
            $cliente->name     = $request->input('name');
            $cliente->endereco = $request->input('endereco');
            $cliente->update();
            return redirect('admin/cliente/listar')->with('mensagens-sucesso','Atualizado com sucesso');
        }
        $cliente = User::find($id);
        return view('admin.cliente.form',['cliente'=>$cliente]);

    }

    public function excluirCliente($id){
        $cliente = User::find($id);
        return view('admin.cliente.excluir',['cliente'=>$cliente]);
    }

    public function deletarCliente($id){
        $vendas = Venda::where('user_id',$id)->count();
        if($vendas > 1){
           return redirect('admin/cliente/listar')->with('mensagens-danger','Não é possível excluir este cliente,pois ele gerou pedidos.'); 
        }else{
            $cliente = User::find($id);
            $cliente->delete();
            return redirect('admin/cliente/listar')->with('mensagens-sucesso','Excluido com sucesso.');
        }
        
    }

    public function salvar_Troco($id_pedido = null,$troco = null ,$entrada = null,$desconto = null, $total_n = null, $troco_n = null){
        $consulta = Venda::where('id_venda',$id_pedido)->count();
        $entrada = str_replace(',', '.',$entrada);
        if($desconto == ''){
            return redirect()->back()->with('mensagens-danger','Erro');
        }
        if($total_n == ''){
            return redirect()->back()->with('mensagens-danger','Erro');
        }
        if($troco_n == ''){
            return redirect()->back()->with('mensagens-danger','Erro');
        }
        if($id_pedido == ''){
            return redirect()->back()->with('mensagens-danger','Erro');
        }
        if(!is_numeric($desconto)) {
            return redirect()->back()->with('mensagens-danger','É necessário digitar apenas numeros no campo(Desconto)');
        }
        if(!is_numeric($total_n)) {
            return redirect()->back()->with('mensagens-danger','Apenas Numeros no Campo(Novo Total)');
        }
        if(!is_numeric($troco_n)) {
            return redirect()->back()->with('mensagens-danger','Apenas Numeros no Campo(Novo Total)');
        }
        if(!is_numeric($entrada)){
            return redirect()->back()->with('mensagens-danger','É necessário digitar apenas numeros no campo(entrada)');
        }
        if($id_pedido == ''){
            return redirect()->back()->with('mensagens-danger','Erro');
        }
        if($entrada == null){
            return redirect()->back()->with('mensagens-danger','O campo (Entrada) é obrigatório');
        }
        if(!is_numeric($entrada)){
            return redirect()->back()->with('mensagens-danger','É necessário digitar apenas numeros no campo(entrada)');
        }
        if($troco == null){
            return redirect()->back()->with('mensagens-danger','É obrigatório salvar o troco.');
        }
        if($consulta != 1 ){
            return redirect()->back()->with('mensagens-danger','Pedido não encontrado');
        }
        if($desconto < 0){
           return redirect()->back()->with('mensagens-danger','O Desconto Não Pode Conter 0 ou Numeros Negativos.'); 
        }
        if($total_n < 0){
           return redirect()->back()->with('mensagens-danger','O Desconto Não Pode Conter Numeros Negativos.'); 
        }
        if($troco_n < 0){
           return redirect()->back()->with('mensagens-danger','O Novo Troco Não Pode Conter Numeros Negativos.'); 
        }
        if($entrada == $troco){
            return redirect()->back()->with('mensagens-sucesso','Salvo com Sucesso');
            $pedido = Venda::find($id_pedido);
            $pedido->troco = $troco;
            $pedido->entrada = str_replace(',','.',$entrada);
            $pedido->pago = 1;
            $pedido->enviado = 1;
            $pedido->save();
            return redirect('admin/pedidos/'.$pedido->id_venda)->with('mensagens-sucesso','Salvo com Sucesso');
        }
        if($troco < 0){
           return redirect()->back()->with('mensagens-danger','O troco não pode conter 0 ou numeros negativos.'); 
        }

        if($entrada < $troco){
            return redirect()->back()->with('mensagens-danger','A entrada não pode ser menor do que o total.'); 
        }
        if(!is_numeric($troco)){
            return redirect()->back()->with('mensagens-danger','O troco deve conter somente numeros');
        }
        $pedido = Venda::find($id_pedido);
        if($pedido->user_id != ''){
            $pedido->desconto    = number_format($desconto, 2, '.','');
            $pedido->total_novo  =  number_format($total_n, 2, '.','');
            $pedido->troco_novo  =    number_format($troco_n, 2, '.','');
            $pedido->troco = number_format($troco, 2,'.','');
            $pedido->entrada = str_replace(',','.',$entrada);
            $pedido->pago = 1;
            $pedido->enviado = 1;
            $pedido->save();
            return redirect('admin/pedidos/'.$pedido->id_venda)->with('mensagens-sucesso','Salvo com Sucesso');    
        }else{
            $pedido->troco = $troco;
            $pedido->entrada = str_replace(',','.',$entrada);
            $pedido->pago = 1;
            $pedido->enviado = 1;
            $pedido->save();
            return redirect('admin/pedidos/'.$pedido->id_venda)->with('mensagens-sucesso','Salvo com Sucesso');
        }
        
    }
    public function DescontoVenda(Request $request, $id){
        $entrada = $request->input('val_entrada');
        $desconto = $request->input('desconto');
        
        $venda = Venda::find($id);
        $total = $venda->valor_venda;
        $venda->valor_venda = $venda->valor_venda * $desconto / 100;
        $venda = number_format($venda->valor_venda, 2,'.', '');
        $total2 = $total-$venda;
        $novo_desc = number_format($total2 * $desconto / 100, 2, '.','');
        $total_novo = $total-$novo_desc;
        return Response::json(array(['desconto'=>$venda,'total'=>$total2,'novo_desc'=>$novo_desc]));

    }
}
