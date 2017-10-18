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

        $produtos = Produto::where('nome', 'LIKE', '%' . $termo . '%')
                ->paginate(10);
        //$produtos->setPath('buscar/'.$termo);
        $models['produtos'] = $produtos;
        $models['termo'] = $termo;
        return view('frente.resultado-busca', $models);
    }
    function listar() {
        $models['produtos'] = Produto::orderBy('nome')->paginate(20);
            //dd($models);
            return view('admin.produto.listar', $models);
        }
    
    function criar() {
        $models['marcas'] = Marca::all();
            return view('admin.produto.form', $models);

        }
    
    function salvar(Request $request) {
        $this->validate($request,[
        'id'=>'required',
        'marca_id'=>'required',
        'nome'=>'required|min:3|unique:produtos',
        'descricao'=>'required|min:5',
        'qtde_estoque'=>'required|integer|min:1',
        'preco_venda'=>'required|min:0,00',
        'imagem_nome'=>'required|image',
        ],[
                'id.required'=>'É Nescessário Selecionar uma Categoria',
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
                'imagem_nome.required'=>'É Nescessário Selecionar Uma Imagem',
                'imagem_nome.image'=>'Não é Imagem Válida',
        ]);

        
        $produto = new Produto();
        $produto->categoria_id = $request->input('id');
        $produto->marca_id = $request->input('marca_id');
        $produto->nome = $request->input('nome');
        $produto->descricao = $request->input('descricao');
        $produto->qtde_estoque = $request->input('qtde_estoque');
        $produto->preco_venda = str_replace(',', '.',$request->input('preco_venda'));
        $produto->destacado = $request->input('destacado');
        $path = 'uploads';
        $imagem_upload = $request->file('imagem_nome');
        $formato = $imagem_upload->getClientOriginalExtension();
        $arquivo = rand(902802,398432).'.'.$formato;
        $request->file('imagem_nome')->move($path,$arquivo);
        $produto->imagem_nome = $arquivo;
        //str_replace(',', '.', $produto->preco_venda);
        if($produto->preco_venda < 0){
            return redirect()->back()->with('mensagens-danger', 'Não é possível Deixar o Preço do Produto Negativo!!!');
        }
        //dd($produto->preco_venda);
        $produto->save();
        return redirect()->action('ProdutoController@listar')->with('mensagens-sucesso', 'Cadastrado com Sucesso!!!');
        //dd($produto);
    }
    
    function editar($id) {
        $models['produto'] = Produto::find($id);
        $models['marcas'] = Marca::all();    
            return view('admin.produto.form', $models);
        }

    public function atualizar(Request $request, $id) {
        $this->validate($request,[
        'id'=>'required',
        'marca_id'=>'required',
        'nome'=>'required|min:3',
        'descricao'=>'required|min:5',
        'qtde_estoque'=>'required|integer|min:1',
        'preco_venda'=>'required|min:0,00',
        'imagem_nome'=>'required',
        ],[
                'id.required'=>'É Nescessário Selecionar uma Categoria',
                'marca_id.required'=>'É Nescessário Selecionar uma Marca',
                'nome.required'=>'Nome do Produto é Nescessário',
                'nome.min'=>'Minímo 3 Caracteres no Nome do Produto',
                'descricao.required'=>'É Nescessaŕio Uma Descrição',
                'descricao.min'=>'Número Mínimo de Caracteres é 5',
                'qtde_estoque.required'=>'É Nescessário Um Valor para Quantidade do Estoque',
                'qtde_estoque.integer'=>'O Valor do Estoque Deverá Ser Apenas Números Inteiros e Positivo"',
                'qtde_estoque.min'=>'O Valor Mínimo para a Quantidade do Estoque é de 1',
                'preco_venda.required'=>'O Preco do Produto é Nescessário ',
                'imagem_nome.required'=>'É Nescessário Selecionar Uma Imagem',
        ]);
        $data = $request->all();
        $data= Produto::find($id);
        $data->categoria_id = $request->input('id');
        $data->marca_id = $request->input('marca_id');
        $data->nome = $request->input('nome');
        $data->descricao = $request->input('descricao');
        $data->qtde_estoque = $request->input('qtde_estoque');
        $data->preco_venda = str_replace(',', '.',$request->input('preco_venda'));
        $data->destacado = $request->input('destacado');
        $path = 'public/uploads';
        $imagem_upload = $request->file('imagem_nome');
        $formato = $imagem_upload->getClientOriginalExtension();
        $arquivo = rand(902802,398432).'.'.$formato;
        $request->file('imagem_nome')->move($path,$arquivo);
        $data->imagem_nome = $arquivo;
        //str_replace(',', '.', $produto->preco_venda);
        if($data->preco_venda < 0){
            return redirect()->back()->with('mensagens-danger', 'Não é possível Deixar o Preço do Produto Negativo!!!');
        }
        if(Produto::find($id)->update($data)){
           return redirect()->action('ProdutoController@listar')->with('mensagens-sucesso', 'Atualizado com Sucesso!');
        } else {
           return redirect()->back()
           ->with('mensagens-danger', 'Erro ao Atualizar Produto!!!')
           ->withInput();
        }

    }
    function excluir($id) {
        $models['produto'] = Produto::find($id);
            return view('admin.produto.excluir', $models);
        }
    
    function delete($id) {
        $itens = VendaItem::where('produto_id',$id)->count();
        if($itens == 1){
            return redirect()->back()->with('mensagens-danger','Não é possivel excluir este produto,pois esta relacionado a algum pedido');
        }else{
            $produto = Produto::find($id);
            $path = 'uploads/'.$produto->imagem_nome;
            unlink($path);
            $produto->delete();
            \Session::flash('mensagens-sucesso', 'Excluido com Sucesso');
            return redirect()->action('ProdutoController@listar');
        }
        
    }

}
