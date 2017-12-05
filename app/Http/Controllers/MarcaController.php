<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Marca;
use Shoppvel\Models\Produto;
use Shoppvel\Http\Requests\MarcaFormRequest;

class MarcaController extends Controller
{

    function listar() {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['marcas'] = Marca::orderBy('nome')->paginate(10);
      //$models['marcas'] = Marca::paginate(10);
            return view('admin.marca.listar', $models);
        }
    }
    
    function criar() {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            return view('admin.marca.form');
        }
    }
    
    function salvar(Request $request) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
             $this->validate($request,[
            'nome'=>'required|min:3|unique:marcas',
            ],[
                'nome.required'     =>'O campo Nome é nescessário ser preenchido',
                'nome.min'          =>'O minímo é 3 caracteres no campo Nome',
                'nome.unique:marcas'=>'O Nome para a Marca já está sendo utilizado',
            ]);
            $marca = new Marca();
            $marca->create($request->all());
            //\Session::flash('mensagens-sucesso', 'Cadastrado com Sucesso');
          return $this->listar();
        }
    }
    
    function editar($id) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['marca'] = Marca::find($id);
            return view('admin.marca.form', $models);
        }
    }

    public function atualizar(Request $request, $id) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $data = $request->all();
            $this->validate($request,[
            'nome'=>'required|min:3|unique:marcas',
            ],[
                'nome.required'     =>'O campo Nome é nescessário ser preenchido',
                'nome.min'          =>'O minímo é 3 caracteres no campo Nome',
                'nome.unique:marcas'=>'O Nome para a Marca já está sendo utilizado',
            ]);
            if(Marca::find($id)->update($data)){
               return redirect()->action('MarcaController@listar')->with('mensagens-sucesso', 'Atualizado com Sucesso!');
           } else {
               return redirect()->back()
               ->with('mensagens-erro', 'Erro!!!')
               ->withInput();
           }
       }
    }
    function excluir($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $data['marca'] = Marca::find($id);
            if(count(Produto::where('marca_id', $id)->get()) == 0){
                return view('admin.marca.excluir', $data); 
            }
            else{
               return \Redirect::back()
                ->withErrors('Não é possível excluir a Marca: '.$data['marca']->nome.', pois ela está associada à um(uns) produto(s).'); 
            }
        }
    }  
    function delete($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['marca'] = Marca::find($id)->delete();
        \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
            return redirect()->action('MarcaController@listar');
        }
    }
}