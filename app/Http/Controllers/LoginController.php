<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

use Shoppvel\User;

use Crypt;

class LoginController extends Controller{


    public function login_form(request $request){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $this->validate($request, [
        'email' => 'required|max:255',
        'password' => 'required|max:255'
        ]
    );

        $model = User::where('email',$_REQUEST['email'])->count();//verifico se existe no banco o e-mail digitado
        if($model == 1){//se essa variavel for igual a 1,quer dizer que achou,se não,ele mostra o erro no else
            $dados = User::where('email',$_REQUEST['email'])->first();//pego o e-mail encontrado
            $senha_descriptografada = Crypt::decrypt($dados->password);//descriptografo a senha
            if($senha_descriptografada == $_REQUEST['password']){//verifico se a senha que ta vindo do form é igual a senha do banco descriptografada
                $dados->password = Crypt::encrypt($senha_descriptografada);//se sim,criptografo a senha denovo com o hash novo e salvo no banco
                $dados->save();
                if($dados->role == 'cozinha'){//verifico se o role(tipo de usuario) é igual a cozinha,seto as variaveis de sessao com as infos do banco e redireciono para o dashboard(as outras condicoes faz mesma coisa,só que mudaria o role do usuario e o lugar para qual ele ta mandando)
                    \Session::put('cozinha',$dados);
                    \Session::put('id',$dados->id);
                    \Session::put('nome',$dados->name);
                    \Session::put('role',$dados->role);
                    return redirect('cozinha_dashboard')->with("mensagens-sucesso",'Seja Bem vindo');
                    }elseif($dados->role == 'admin'){
                    \Session::put('admin',$dados);
                    \Session::put('id',$dados->id);
                    \Session::put('nome',$dados->name);
                    \Session::put('role',$dados->role);
                    return redirect('admin/dashboard')->with("mensagens-sucesso",'Seja Bem vindo ');  
                    }elseif($dados->role == 'recepcao'){
                    \Session::put('recepcao',$dados);
                    \Session::put('id_recepcao',$dados->id);
                    \Session::put('nome_recepcao',$dados->name);
                    \Session::put('role_recepcao',$dados->role);
                    return redirect('/')->with("mensagens-sucesso",'Seja Bem vindo');    
                    }
                }
            
            }else{
                  return redirect('login')->with("erro",'Login ou senha incorretos,tente novamente.');
            }
        
        }
        return view('frente.login_form');
    }


    public function logout(){//logout da cozinha,retira todas essas infos da sessao e volta pro login
        \Session::forget('cozinha');
        \Session::forget('id');
        \Session::forget('role');
        \Session::forget('nome');
        return redirect('login');
    }
		
}

