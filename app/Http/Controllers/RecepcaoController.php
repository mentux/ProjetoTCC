<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

use Shoppvel\User;

class RecepcaoController extends Controller{
	
	public function logout_recepcao($id){
		 if($id != \Session::get('id_recepcao')){
            return redirect()->back()->with('mensagens-danger','Erro');
        }elseif($id == \Session::get('id_recepcao') ){
        $user = User::find($id);
        $user->status = 1;
        $user->save();
		\Session::forget('recepcao');
        \Session::forget('id_recepcao');
        \Session::forget('role_recepcao');
        \Session::forget('nome_recepcao');
        return redirect('login');
    	}
	}
    
}
