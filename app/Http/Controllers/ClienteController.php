<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Carrinho;
use Shoppvel\Models\Produto;
use Shoppvel\Models\Venda;
use Shoppvel\Models\VendaItem;
use Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Shoppvel\User;

use Crypt;

class ClienteController extends Controller {

    public function getDashboard(){
        $id = \Session::get('id_cliente');
        $models['qtdePedidos']['total'] = Venda::where('user_id',$id)->count();
        $models['qtdePedidos']['pendentes-pagamento'] = Venda::where('user_id',$id)->where('pago', false)->count();
        $models['qtdePedidos']['pagos'] = Venda::where('user_id',$id)->where('pago', true)->count();
        $models['qtdePedidos']['finalizados'] = Venda::where('user_id',$id)->where('enviado', true)->count();
        return view('frente.cliente.dashboard', $models);
    }
    
    public function getPerfil() {
        return view('frente.cliente.perfil');
    }
    
    public function getPedidos(Request $req, $id = null) {
        $id_cliente = \Session::get('id_cliente');
        if ($id == null) {
            if ($req->has('status') == false) {
                $models['tipoVisao'] = 'Todos';
                $models['pedidos'] = Venda::where('user_id',$id_cliente)->paginate(10);
            } else {
                if ($req->status == 'nao-pagos') {
                    $models['tipoVisao'] = 'Não Pagos';
                    $models['pedidos'] = Venda::where('user_id',$id_cliente)->where('pago', false)->paginate(10);
                } else if ($req->status == 'pagos') {
                    $models['tipoVisao'] = 'Pagos';
                    $models['pedidos'] = Venda::where('user_id',$id_cliente)->where('pago', true)->paginate(10);
                } else if ($req->status == 'finalizados') {
                    $models['tipoVisao'] = 'Finalizados/Enviados';
                    $models['pedidos'] = Venda::where('user_id',$id)->where('enviado', true)->paginate(10);
                }
            }
            return view('frente.cliente.pedidos-listar', $models);
        }

        $models['pedido'] = Venda::find($id);
        return view('frente.cliente.pedido-detalhes', $models);
    }
    
    public function postAvaliar(Request $req, $itemVendaId) {
        $this->validate($req,[
            'avaliacao'=>'required|integer'
        ]);
        $itemVenda = VendaItem::find($itemVendaId);
        $produto = $itemVenda->produto;
        
        DB::beginTransaction();

        $itemVenda->avaliado = true;
        $itemVenda->save();
        
        $produto->increment('avaliacao_qtde');
        $produto->increment('avaliacao_total', $req->avaliacao);
        $produto->save();
        
        DB::commit();
        
        return back()->with('mensagens-sucesso', 'Produto avaliado com sucesso!');
    }


    public function NovoCliente(Request $request){
        if(\Session::get('cliente')!= ''){
            return redirect('getmesa/'.\Session::get('id_mesa'));
        }
        if($_SERVER['REQUEST_METHOD']=='POST'){
        $this->validate($request,[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:3',
            'password_confirm'=>'required|min:3|same:password',
            'cpf'=>'required|min:11|max:11|unique:users',
            'endereco'=>'required|min:5',
            ],[
                    'name.required'=>'É Nescessário Preeencher o Campo Nome',
                    'name.min'=>'O Campo Nome não Pode ser Inferiror á 3 caracteres',
                    'email.required'=>'É Nescessário Preeencher o Campo E-mail',
                    'email.email'=>'O E-mail não é um E-mail Válido',
                    'email.unique:users'=>'Este E-mail já está Cadastrado',
                    'password.required'=>'A Senha é Obrigatória',
                    'password.min'=>'Número Mínimo de Caracteres é 3',
                    'password_confirm.required'=>'É Necessário Preencher o campo Confirmar sua Senha',
                    'password_confirm.confirmed'=>'As Senhas não Coincidem',
                    'password_confirm.min'=>'O Campo Confirmar sua Senha não Pode ser Inferiror á 3 caracteres',
                    'password_confirm.same'=>'As senhas Não são Iguais',
                    'cpf.required'=>'O Campo de CPF precisa ser Preeenchido',
                    'cpf.min'=>'O Campo CPF Não pode ser Inferior a 11 Caractares',
                    'cpf.max'=>'O Campo CPF Não pode ser Posterior a 11 Caractares',
                    'endereco.required'=>'É Nescessário Preencher o Campo Endereço',
                    'endereco.min'=>'O Campo Endereço Não pode ser Inferiror 3 Caractares',
            ]);
            $todos = $request->all();
            $cliente = new User($todos);
            $cliente->role = 'cliente';
            $cliente->password = Crypt::encrypt($request->input('password'));
            $cliente->save();
            \Session::put('cliente',$cliente);
            \Session::put('nome_cliente',$cliente->name);
            \Session::put('id_cliente',$cliente->id);
            \Session::put('role_cliente',$cliente->role);
            return redirect('getmesa/'.\Session::get('id_mesa'))->with('mensagens-sucesso','Bem vindo');
        }
        return view('frente.cliente.cadastrar_form');
    }

    public function logout_cliente(){
        \Session::forget('cliente');
        \Session::forget('id_cliente');
        \Session::forget('role_cliente');
        \Session::forget('nome_cliente');
        return redirect('getmesa/'.\Session::get('id_mesa'));
    }

    public function login_cliente(Request $request){
        if(\Session::get('cliente')!= ''){
            return redirect('getmesa/'.\Session::get('id_mesa'));
        }
       if($_SERVER['REQUEST_METHOD']=='POST'){
        $this->validate($request, [
        'email' => 'required|max:255',
        'password' => 'required|max:255'
        ]
    );

        $model = User::where('email',$_REQUEST['email'])->count();
        if($model == 1){
            $dados = User::where('email',$_REQUEST['email'])->first();
            $senha_descriptografada = Crypt::decrypt($dados->password);
            if($senha_descriptografada == $_REQUEST['password']){
                $dados->password = Crypt::encrypt($senha_descriptografada);
                $dados->save();
                if($dados->role == 'cliente'){
                    \Session::put('cliente',$dados);
                    \Session::put('id_cliente',$dados->id);
                    \Session::put('nome_cliente',$dados->name);
                    \Session::put('role_cliente',$dados->role);
                    return redirect('getmesa/'.\Session::get('id_mesa'))->with("mensagens-sucesso",'Seja Bem vindo ');  
                    }else{
                        return redirect('login_cliente')->with("login_error",'Login ou senha incorretos,tente novamente.');
                    }
                }
            
            }else{
                  return redirect('login_cliente')->with("login_error",'Login ou senha incorretos,tente novamente.');
                }
       }
       return view('frente.cliente.login_cliente'); 
    }
}
