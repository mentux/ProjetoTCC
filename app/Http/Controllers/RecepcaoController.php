<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;

use Shoppvel\Http\Requests;

class RecepcaoController extends Controller{
	
	public function logout_recepcao(){
		\Session::forget('recepcao');
        \Session::forget('id_recepcao');
        \Session::forget('role_recepcao');
        \Session::forget('nome_recepcao');
        return redirect('login');
	}
    
}
