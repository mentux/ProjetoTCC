<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

use Shoppvel\User;

use Crypt;

class LoginController extends Controller{


    public function login_form(request $request){
        if(\Session::get('role') == 'cozinha'){
            return redirect('cozinha_dashboard');
        }elseif(\Session::get('role')=='admin'){
            return redirect('admin/dashboard');
        }elseif(\Session::get('role_recepcao')=='recepcao'){
            return redirect('/');
        }elseif(\Session::get('role_cliente')=='cliente'){
            return redirect('getmesa/'.\Session::get('id_mesa'));
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                if($dados->role == 'cozinha'){
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


    public function logout(){
        \Session::forget('cozinha');
        \Session::forget('id');
        \Session::forget('role');
        \Session::forget('nome');
        return redirect('login');
    }
		
}

