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
    
    public function getPedidos(Request $req, $id = null){
        if ($id == null) {
            $data_inicial = null;
            $data_final = null;
            if ($req->has('status') == false) {
                $models['tipoVisao'] = 'Todos';
                if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $this->validate($req,[
                    'data_inicial'=>'required|date_format:d/m/Y',
                    'data_final'=>'required|date_format:d/m/Y',
                ],[
                    'data_inicial.required'=>'É Nescessário Colocar a Data Inicial',
                    'data_inicial.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                    'data_final.required'=>'É Nescessário Colocar a Data Final',
                    'data_final.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                ]);
                
                $models['pedidos'] = Venda::orderBy('data_venda','ASC')->where('data_venda','>=',$req->input('data_inicial'))->where('data_venda','<=',$req->input('data_final'))->where('status',3)->paginate(10);
                }
            }elseif($req->status == 'nao-pagos'){
                    $models['tipoVisao'] = 'Não Pagos';
                    if($_SERVER['REQUEST_METHOD'] == 'GET'){
                    $this->validate($req,[
                        'data_inicial'=>'required|date_format:d/m/Y',
                        'data_final'=>'required|date_format:d/m/Y',
                        'status'=>'in:nao-pagos'
                    ],[
                        'data_inicial.required'=>'É Nescessário Colocar a Data Inicial',
                        'data_inicial.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                        'data_final.required'=>'É Nescessário Colocar a Data Final',
                        'data_final.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                    ]);
                        $data_inicial = str_replace(' ', '', $req->input('data_inicial'));
                        $data_final =   str_replace(' ', '', $req->input('data_final'));
                        $req->status = $req->input('status');
                        if($data_inicial != '' AND $data_final != ''){
                        $models['pedidos'] = Venda::where('pago',null)->orderBy('data_venda','ASC')->where('data_venda','>=',$data_inicial)
                    ->where('data_venda','<=',$data_final)->where('status',3)->paginate(10);
                        }

                    }

            }elseif($req->status == 'pagos'){
                $models['tipoVisao'] = 'Pagos';
                    if($_SERVER['REQUEST_METHOD'] == 'GET'){
                       $this->validate($req,[
                        'data_inicial'=>'required|date_format:d/m/Y',
                        'data_final'=>'required|date_format:d/m/Y',
                    ],[
                        'data_inicial.required'=>'É Nescessário Colocar a Data Inicial',
                        'data_inicial.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                        'data_final.required'=>'É Nescessário Colocar a Data Final',
                        'data_final.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                    ]);
                        $data_inicial = str_replace(' ', '', $req->input('data_inicial'));
                        $data_final =   str_replace(' ', '', $req->input('data_final'));
                        $req->status = $req->input('status');
                        if($data_inicial != '' AND $data_final != ''){
                            $models['pedidos'] = Venda::where('pago',1)->orderBy('data_venda','ASC')->where('data_venda','>=',$data_inicial)
                    ->where('data_venda','<=',$data_final)->where('status',3)->paginate(10);
                        }
                    }
            }elseif($req->status == 'finalizados'){
                $models['tipoVisao'] = 'Finalizados';
                    if($_SERVER['REQUEST_METHOD'] == 'GET'){
                        $this->validate($req,[
                        'data_inicial'=>'required|date_format:d/m/Y',
                        'data_final'=>'required|date_format:d/m/Y',
                    ],[
                        'data_inicial.required'=>'É Nescessário Colocar a Data Inicial',
                        'data_inicial.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                        'data_final.required'=>'É Nescessário Colocar a Data Final',
                        'data_final.date_format'=>'O Formato da Data deve ser dia/mes/ano',
                    ]);
                        $data_inicial = str_replace(' ', '', $req->input('data_inicial'));
                        $data_final =   str_replace(' ', '', $req->input('data_final'));
                        $req->status = $req->input('status');
                        if($data_inicial != '' AND $data_final != ''){
                            $models['pedidos'] = Venda::where('enviado',1)->orderBy('data_venda','ASC')->where('data_venda','>=',$data_inicial)
                            ->where('data_venda','<=',$data_final)->where('status',3)->paginate(10);
                        }
                    }       
            }else{ 
                return redirect()->back();
            }
            return view('admin.pedidos-listar', $models);
        }

        $models['pedido'] = Venda::find($id);
        \Session::put('id_pedido',$models['pedido']->id_venda);
        $models['pagseguro'] = $this->checkout();
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
        $pedido_validacao = Venda::find($id_pedido);

        
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
        if($pedido_validacao->user_id == ''){
            $desconto = 0;
            $total_n = 0;
            $troco_n = 0;
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

    ///////////////////////////CRUD usuários//////////////////////////////////////////
    public function listarUsuarios(){
        $usuarios = User::where('role','admin')->orWhere('role','cozinha')->orWhere('role','recepcao')->orWhere('role','admin/caixa')->orderBy('id')->paginate(10);
        return view('admin.usuario.listar',['usuarios'=>$usuarios]);

    }

    public function atualizar_usuario(Request $request,$id){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $user = User::find($id);
            if($user->role == 'admin' OR $user->role == 'admin/caixa'){
                $this->validate($request,[
                    'name'=>'required|min:3',
                    'endereco'=>'required|min:5',
                ],[
                    'name.required'=>'É Nescessário Preeencher o Campo Nome',
                    'name.min'=>'O Campo Nome não Pode ser Inferiror á 3 caracteres',
                    'endereco.required'=>'É Nescessário Preencher o Campo Endereço',
                    'endereco.min'=>'O Campo Endereço Não pode ser Inferiror 3 Caractares',
                ]);
            }else{
                $this->validate($request,[
                    'name'=>'required|min:3',
                    'endereco'=>'required|min:5',
                    'tipo_user'=>'required',
                ],[
                    'name.required'=>'É Nescessário Preeencher o Campo Nome',
                    'name.min'=>'O Campo Nome não Pode ser Inferiror á 3 caracteres',
                    'endereco.required'=>'É Nescessário Preencher o Campo Endereço',
                    'endereco.min'=>'O Campo Endereço Não pode ser Inferiror 3 Caractares',
                    'tipo_user.required'=>'erro',
                ]);
            }
            $tipos = ['tipo'=>$request->input('tipo_user')];
            if(in_array("cozinha", $tipos) OR in_array("recepcao", $tipos) OR $user->role == 'admin' OR in_array("admin/caixa", $tipos) ){
                $form_infos = $request->all();
                if($user->role == 'admin'){
                \Session::put('nome',$request->input('name'));
                $user->role = 'admin';
                }else{
                $user->role = $request->input('tipo_user');    
                }
                $user->update($form_infos);
                return redirect('admin/usuarios')->with('mensagens-sucesso','Atualizado com sucesso');
            }else{
             return redirect()->back()->with('mensagens-danger','erro');
            }
        }
        if(\Session::get('id_admin_caixa') == $id){

         $usuario = User::find($id);
        return view('admin.usuario.form',['usuario'=>$usuario]);

        }elseif(\Session::get('role') == ''){

        return redirect('admin/usuarios')->with('mensagens-danger','Acesso negado');
        }else{

            $usuario = User::find($id);
        return view('admin.usuario.form',['usuario'=>$usuario]);
        }
    }


    public function excluir_user($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            if($id == \Session::get('id')){
                return redirect()->route('admin.usuarios')->with('mensagens-danger','Não pode deletar a si mesmo');
            }else{
                $usuario = User::find($id);
                return view('admin.usuario.excluir',["usuario"=>$usuario]);
            }
        }   
    }

    public function deletar_user($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $usuario = User::find($id);
            if($usuario->status == 2){
                return redirect()->route('admin.usuarios')->with('mensagens-danger','Usuário esta conectado,não é possivel deletar.');
            }
            if($usuario->id == \Session::get('id')){
                return redirect()->route('admin.usuarios')->with('mensagens-danger','Não pode deletar a si mesmo');
            }
            $usuario->delete();
            return redirect()->route('admin.usuarios')->with('mensagens-sucesso','Deletado com sucesso');
        }
    }

    public function cadastrar_novo_usuario(Request $request){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
               $this->validate($request,[
                'name'=>'required|min:3',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:3',
                'cpf'=>'required|min:11|max:11|unique:users',
                'endereco'=>'required|min:5',
                'tipo_user'=>'required',
                ],[
                    'name.required'=>'É Nescessário Preeencher o Campo Nome',
                    'name.min'=>'O Campo Nome não Pode ser Inferiror á 3 caracteres',
                    'email.required'=>'É Nescessário Preeencher o Campo E-mail',
                    'email.email'=>'O E-mail não é um E-mail Válido',
                    'email.unique:users'=>'Este E-mail já está Cadastrado',
                    'password.required'=>'A Senha é Obrigatória',
                    'password.min'=>'Número Mínimo de Caracteres é 3',
                    'cpf.required'=>'O Campo de CPF precisa ser Preeenchido',
                    'cpf.min'=>'O Campo CPF Não pode ser Inferior a 11 Caractares',
                    'cpf.max'=>'O Campo CPF Não pode ser Posterior a 11 Caractares',
                    'endereco.required'=>'É Nescessário Preencher o Campo Endereço',
                    'endereco.min'=>'O Campo Endereço Não pode ser Inferiror 3 Caractares',
                    'tipo_user.required'=>'erro',
                ]);
                $tipos = ['tipo'=>$request->input('tipo_user')];
                if(in_array("cozinha", $tipos) OR in_array("recepcao", $tipos) OR in_array("admin/caixa", $tipos) ){
                    $infos = $request->all();
                    $usuario = new User($infos);
                    $usuario->role = $tipos['tipo'];
                    $usuario->password = Crypt::encrypt($request->input('password'));
                    $usuario->save();
                    return redirect()->route('admin.usuarios')->with('mensagens-sucesso','cadastrado com sucesso');
                }else{
                 return redirect()->back()->with('mensagens-danger','erro');
                }    
            }
            return view('admin.usuario.form');
        }
    }
    ///////////////////////////////////PAGSEGURO////////////////////////////////////////
    public function pagar_pagseguro(Request $request){
        $id_pedido = \Session::get('id_pedido');
        if ($request->has('transaction_id') === FALSE) {
            return back()->withErrors('Problemas ao receber a chave de trasação do Pagseguro, '
                    . 'este pedido não foi gravado');
        }
        if($id_pedido == null){
            return redirect()->back()->with('mensagens-danger','erro');
        }
        $pedido = Venda::find($id_pedido);
        DB::beginTransaction();
        $pedido->pagseguro_transaction_id = $request->transaction_id;
        $pedido->pago = 1;
        $pedido->enviado = 1;
        $pedido->entrada = 0;
        $pedido->troco = 0;
        $pedido->desconto = 0;
        $pedido->total_novo = 0;
        $pedido->troco_novo = 0;
        $pedido->save();
        DB::commit(); 
        return redirect('admin/pedidos/'.$id_pedido)->with('mensagens-sucesso','pagamento realizado com sucesso');
    }
    
    protected function checkout($id_pedido = null){
        $id_pedido = \Session::get('id_pedido');
        $pedido = Venda::find($id_pedido);
        $cliente = User::find($pedido->user_id);
        $models = null;
        if ($cliente) {

            $itens_pedido = DB::table('itens_vendas')->select('produtos.preco_venda','produtos.nome','itens_vendas.produto_id','itens_vendas.venda_id','itens_vendas.qtde')->join('produtos','produtos.id','=','itens_vendas.produto_id')->where(['itens_vendas.venda_id' => $id_pedido])->get();

            $itens = [];

            foreach ($itens_pedido as $item) {
                $itens[] = [
                    'id' => $item->produto_id,
                    'description' => $item->nome,
                    'quantity' => $item->qtde,
                    'amount' => $item->preco_venda,
                ];
            }


            $dadosCompra = [
                'items' => $itens,
                'sender' => [
                    'email' => $cliente->email,
                    'name' => $cliente->name,
                ]
            ];

            if ($cliente->cpf) {
                $dadosCompra['sender']['documents'] = [
                    [
                        'number' => $cliente->cpf,
                        'type' => 'cpf'
                    ]
                ];
            }


            $checkout = \PagSeguro::checkout()->createFromArray($dadosCompra);
            $models['info'] = $checkout->send(\PagSeguro::credentials()->get());

            try {

                $models['info'] = $checkout->send(\PagSeguro::credentials()->get());
            } catch (\Exception $e) {
                \Log::error($e);
                $models = null;
            }
        }else{
            $itens_pedido = DB::table('itens_vendas')->select('produtos.preco_venda','produtos.nome','itens_vendas.produto_id','itens_vendas.venda_id','itens_vendas.qtde')->join('produtos','produtos.id','=','itens_vendas.produto_id')->where(['itens_vendas.venda_id' => $id_pedido])->get();

            $itens = [];

            foreach ($itens_pedido as $item) {
                $itens[] = [
                    'id' => $item->produto_id,
                    'description' => $item->nome,
                    'quantity' => $item->qtde,
                    'amount' => $item->preco_venda,
                ];
            }


            $dadosCompra = [
                'items' => $itens,
                
            ];

        

            $checkout = \PagSeguro::checkout()->createFromArray($dadosCompra);
            $models['info'] = $checkout->send(\PagSeguro::credentials()->get());

            try {

                $models['info'] = $checkout->send(\PagSeguro::credentials()->get());
            } catch (\Exception $e) {
                \Log::error($e);
                $models = null;
            }
        }
        return $models;
    }

    
}
