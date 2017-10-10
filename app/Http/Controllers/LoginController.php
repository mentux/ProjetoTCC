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
                    return redirect('cozinha_dashboard')->with("sucesso",'Seja Bem vindo ');  
                    }elseif($dados->role == 'admin'){
                    \Session::put('admin',$dados);
                    \Session::put('id',$dados->id);
                    \Session::put('nome',$dados->name);
                    \Session::put('role',$dados->role);
                    return redirect('admin/dashboard')->with("sucesso",'Seja Bem vindo ');  
                    }else{
                        return redirect('login')->with("sucesso",'Login ou senha incorretos,tente novamente.');
                    }
                }else{
                  return redirect('login')->with("sucesso",'Login ou senha incorretos,tente novamente.');
                }
            
            }
        
        }
        return view('frente.login_form');
    }


    public function logout(){
        \Session::forget('cozinha');
        return redirect('login');
    }
		
}

