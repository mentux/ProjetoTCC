<?php

namespace Shoppvel\Http\Controllers;

use Illuminate\Http\Request;
use Shoppvel\Http\Requests;
use Shoppvel\Models\Produto;
use Shoppvel\Models\Marca;
use Shoppvel\Models\VendaItem;
use Shoppvel\Controllers\ImagemController;
use Shoppvel\Http\Requests\ProdutoFormRequest;
use Shoppvel\Http\Requests\ProdutoUpdateRequest;

class ProdutoController extends Controller {

    function getProdutoDetalhes($id) {
        $models['produto'] = Produto::find($id);
        return view('frente.produto-detalhes', $models);
    }

    function getBuscar(Request $request) {
        $this->validate($request, [
            'termo-pesquisa' => 'required|filled'
                ]
        );

        $termo = $request->get('termo-pesquisa');

        $produtos = Produto::where('nome','ILIKE',"%{$termo}%")->paginate(10);
        $models['produtos'] = $produtos;
        $models['termo'] = $termo;
        return view('frente.resultado-busca', $models);
    }
    function listar() {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['produtos'] = Produto::orderBy('nome')->paginate(10);
            return view('admin.produto.listar', $models);
        }
    }
    
    function criar() {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['marcas'] = Marca::all();
            return view('admin.produto.form', $models);

        }
    }
    
    function salvar(Request $request) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $this->validate($request,[
        'categoria_id'=>'required',
        'marca_id'=>'required',
        'nome'=>'required|min:3|unique:produtos',
        'descricao'=>'required|min:5',
        'qtde_estoque'=>'required|integer|min:1',
        'preco_venda'=>'required|min:0,00',
        ],[
                'categoria_id.required'=>'É Nescessário Selecionar uma Categoria',
                'marca_id.required'=>'É Nescessário Selecionar uma Marca',
                'nome.required'=>'Nome do Produto é Nescessário',
                'nome.min'=>'Minímo 3 Caracteres no Nome do Produto',
                'nome.unique:produtos'=>'O Nome do Produto Já Existe',
                'descricao.required'=>'É Nescessaŕio Uma Descrição',
                'descricao.min'=>'Número Mínimo de Caracteres é 5',
                'qtde_estoque.required'=>'É Nescessário Um Valor para Quantidade do Estoque',
                'qtde_estoque.integer'=>'O Valor do Estoque Deverá Ser Apenas Números Inteiros e Positivo"',
                'qtde_estoque.min'=>'O Valor Mínimo para a Quantidade do Estoque é de 1',
                'preco_venda.required'=>'O Preco do Produto é Nescessário ',
        ]);

        
        $produto = new Produto();
        $produto->categoria_id = $request->input('categoria_id');
        $produto->marca_id = $request->input('marca_id');
        $produto->nome = $request->input('nome');
        $produto->descricao = $request->input('descricao');
        $produto->qtde_estoque = $request->input('qtde_estoque');
        $produto->preco_venda = str_replace(',', '.',$request->input('preco_venda'));
        $produto->destacado = $request->input('destacado');
        if($request->file('imagem_nome') == null){
            $produto->imagem_nome= 'sem_imagem.jpg';
        }else{
            $path = 'uploads';
            $imagem_upload = $request->file('imagem_nome');
            $formato = $imagem_upload->getClientOriginalExtension();
            $arquivo = rand(902802,398432).'.'.$formato;
            $request->file('imagem_nome')->move($path,$arquivo);
            $produto->imagem_nome = $arquivo;
        }
        if($produto->preco_venda < 0){
            return redirect()->back()->with('mensagens-danger', 'Não é possível Deixar o Preço do Produto Negativo!!!');
        }
        $produto->save();
        return redirect()->action('ProdutoController@listar')->with('mensagens-sucesso', 'Cadastrado com Sucesso!!!');
        }
    }
    
    function editar($id) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['produto'] = Produto::find($id);
        $models['marcas'] = Marca::all();    
            return view('admin.produto.form', $models);
        }
    }

    public function atualizar(Request $request, $id) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $this->validate($request,[
            'categoria_id'=>'required',
            'marca_id'=>'required',
            'nome'=>'required|min:3',
            'descricao'=>'required|min:5',
            'qtde_estoque'=>'required|integer|min:1',
            'preco_venda'=>'required|min:0,00', 
            ],[
                    'categoria_id.required'=>'É Nescessário Selecionar uma Categoria',
                    'marca_id.required'=>'É Nescessário Selecionar uma Marca',
                    'nome.required'=>'Nome do Produto é Nescessário',
                    'nome.min'=>'Minímo 3 Caracteres no Nome do Produto',
                    'descricao.required'=>'É Nescessaŕio Uma Descrição',
                    'descricao.min'=>'Número Mínimo de Caracteres é 5',
                    'qtde_estoque.required'=>'É Nescessário Um Valor para Quantidade do Estoque',
                    'qtde_estoque.integer'=>'O Valor do Estoque Deverá Ser Apenas Números Inteiros e Positivo"',
                    'qtde_estoque.min'=>'O Valor Mínimo para a Quantidade do Estoque é de 1',
                    'preco_venda.required'=>'O Preco do Produto é Nescessário ',
            ]);
            $produto = Produto::find($id);
            $produto->categoria_id = $request->input('categoria_id');
            $produto->marca_id = $request->input('marca_id');
            $produto->nome = $request->input('nome');
            $produto->descricao = $request->input('descricao');
            $produto->qtde_estoque = $request->input('qtde_estoque');
            $produto->preco_venda = str_replace(',', '.',$request->input('preco_venda'));
            $produto->destacado = $request->input('destacado');
            if($produto->imagem_nome != 'sem_imagem.jpg'){
                $produto->imagem_nome = $produto->imagem_nome;
            }elseif($request->file('imagem_nome') == null){
                $produto->imagem_nome = 'sem_imagem.jpg';
            }
            else{
               $path = 'uploads';
                $imagem_upload = $request->file('imagem_nome');
                $formato = $imagem_upload->getClientOriginalExtension();
                $arquivo = rand(902802,398432).'.'.$formato;
                $request->file('imagem_nome')->move($path,$arquivo);
                $produto->imagem_nome = $arquivo; 
            }
            if($produto->preco_venda < 0){
                return redirect()->action('ProdutoController@atualizar');
            }
            if($produto->save()){
               return redirect()->action('ProdutoController@listar')->with('mensagens-sucesso', 'Atualizado com Sucesso!');
            } else {
               return redirect()->back()
               ->with('mensagens-danger', 'Erro ao Atualizar Produto!!!')
               ->withInput();
            }
        }
    }
    function excluir($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
        $models['produto'] = Produto::find($id);
            return view('admin.produto.excluir', $models);
        }
    }
    
    function delete($id) {
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $itens = VendaItem::where('produto_id',$id)->count();
            $produto = Produto::find($id);
            if($produto->imagem_nome == 'sem_imagem.jpg'){
                $produto->delete();
                \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
                return redirect()->action('ProdutoController@listar');
            }elseif($itens == 1){
                return redirect()->back()->with('mensagens-danger','Não é possivel excluir este produto,pois esta relacionado a algum pedido');
            }else{
                $path = 'uploads/'.$produto->imagem_nome;
                unlink($path);
                $produto->delete();
                \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
                return redirect()->action('ProdutoController@listar');
            }
        }
    }

    public function excluir_imagem($id){
        if(\Session::get('admin') == null){
            return redirect('admin/dashboard')->with('mensagens-danger','Acesso negado');
        }else{
            $imagem = Produto::find($id);
            if($imagem->imagem_nome != 'sem_imagem.jpg'){
            $path = 'uploads/'.$imagem->imagem_nome;
            $imagem->imagem_nome = 'sem_imagem.jpg';
            unlink($path);
            $imagem->save();
            return redirect()->back()->with('mensagens-sucesso','Imagem Excluida com sucesso');
            }else{
                return redirect()->back()->with('mensagens-danger','Imagem ja foi excluida');
            }   
        }
    }

}