<?php

/*
  |--------------------------------------------------------------------------
  | Routes File
  |--------------------------------------------------------------------------
  |
  | Here is where you will register all of the routes in an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */


/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | This route group applies the "web" middleware group to every route
  | it contains. The "web" middleware group is defined in your HTTP
  | kernel and includes session state, CSRF protection, and more.
  |
 */




//middleware da recepção
Route::group(['middleware'=>'Shoppvel\Http\Middleware\recepcao'], function(){
Route::get('reservar_mesa/{id}','MesaController@ReservarMesa');
Route::get('/','FrenteLojaController@getIndex');
Route::get('logout_recepcao/{id}', 'RecepcaoController@logout_recepcao');
});
//lista os produtos relacionados a categoria selecionada
Route::get('categoria/{id?}', [
    'as' => 'categoria.listar',
    'uses' => 'CategoriaController@getCategoria'
]);
/*
 * ATENÇÃO para esta rota, ela deve estar antes de produto/{id}
 * para funcionar
 */
//busca de produto na mesa
Route::any('produto/buscar', [
    'as' => 'produto.buscar',
    'uses' => 'ProdutoController@getBuscar'
]);
Route::get('imagem/arquivo/{nome}', [
    'as' => 'imagem.file',
    'uses' => 'ImagemController@getImagemFile'
]);

Route::any('imagem/arquivo/{nome}', [
    'as' => 'imagem.file',
    'uses' => 'ImagemController@getImagemFile'
]);

Route::any('carrinho/adicionar/{id}', [
    'as' => 'carrinho.adicionar',
    'uses' => 'CarrinhoController@anyAdicionar'
]);

Route::any('carrinho/remover_item/{id}', [
    'as' => 'carrinho.remover-item',
    'uses' => 'CarrinhoController@remover_item'
]);

Route::get('carrinho', [
    'as' => 'carrinho.listar',
    'uses' => 'CarrinhoController@getListar'
]);

Route::get('carrinho/esvaziar', [
    'as' => 'carrinho.esvaziar',
    'uses' => 'CarrinhoController@getEsvaziar'
]);
Route::post('carrinho/avaliar', [
    'as' => 'carrinho.avaliar',
    'uses' => 'CarrinhoController@Avaliar'

]);

Route::post('carrinho/calcFrete', [
    'as' => 'carrinho.calcFrete',
    'uses' => 'CarrinhoController@calcFrete'
]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('carrinho/finalizar-compra', [
        'as' => 'carrinho.finalizar-compra',
        'uses' => 'CarrinhoController@getFinalizarCompra'
    ]);

});
//form de login geral
Route::any('/login','LoginController@login_form');
////////Rotas do usuario de cozinha
Route::group(['middleware'=>'Shoppvel\Http\Middleware\cozinha'], function(){
    Route::get('logout_cozinha/{id}','CozinhaController@logout');

    Route::get('cozinha_pedido_detalhes/{id}', [
    'as' => 'cozinha.detalhes',
    'uses' => 'CozinhaController@getCozinhaDetalhes'
    ]);
    Route::get('pagina', [
    'as' => 'pedidos.pagina',
    'uses' => 'CozinhaController@PaginacaoPedidos'
    ]);
    
    Route::get('cozinha_dashboard','CozinhaController@dashboard');
    Route::get('pedidos_pendentes','CozinhaController@getPendentes');
    Route::get('pedidos_pendentes_hoje','CozinhaController@getPendentesHoje');
    Route::get('pedidos_andamento','CozinhaController@getAndamentos');
    Route::get('pedidos_andamento_hoje','CozinhaController@getAndamentosHoje');
    Route::get('pedidos_pronto','CozinhaController@getProntos');
    Route::get('pedidos_pronto_hoje','CozinhaController@getProntosHoje');
    Route::any('pedido_pendente_status/{id?}', [
                'as' => 'status_pendente',
                'uses' => 'CozinhaController@putAndamento'
    ]);
    Route::any('muda_pendente/{id?}', [
                'as' => 'status_muda_pendente',
                'uses' => 'CozinhaController@putMudaPendente'
    ]);
    Route::any('muda_pronto/{id?}', [
                'as' => 'status_muda_pronto',
                'uses' => 'CozinhaController@putMudaPronto'
    ]);
    //////Route in Test (Atualizar Pedidos Pendente(Novos), na listagem da cozinha do dia atual)
    Route::get('novos_pedidos', [
                'as' => 'novos_pedidos_pendente',
                'uses' => 'CozinhaController@getNovosPedidosPendente'
    ]);
    //////*****************************////////////

    Route::put('pedido_pronto_status/{id}', [
                'as' => 'status_pronto',
                'uses' => 'CozinhaController@putPronto'
    ]);
    Route::any('pendentes/{id?}', [
                'as' => 'pedido.pendentes',
                'uses' => 'CozinhaController@getItensPedidoPendentes'
    ]);

});

//Rota onde mostra o status de andamento do pedido após a emissão do pedido
Route::get('mesa_pedido/{id_pedido}','MesaController@MesaPedido');
//Rota para abrir o cardapio da mesa reservada
Route::get('getmesa/{id}','MesaController@getMesaId');

///////////Increment/////////
Route::get('increment_teste/{id}','MesaController@IncrementDelete');
Route::get('decrement_teste/{id}','MesaController@Decrement');
////////////////////////////

///cadastro cliente form 
Route::any('cadastrar_cliente','ClienteController@NovoCliente');
//login cliente form
Route::any('login_cliente','ClienteController@login_cliente');

///////////Rotas usuário do tipo cliente
Route::group(['middleware'=>'Shoppvel\Http\Middleware\cliente'], function(){

    Route::get('logout_cliente','ClienteController@logout_cliente');

    Route::get('cliente/dashboard', [
        'as' => 'cliente.dashboard',
        'uses' => 'ClienteController@getDashboard'
    ]);

    Route::get('cliente/pedidos/{id?}', [
        'as' => 'cliente.pedidos',
        'uses' => 'ClienteController@getPedidos'
    ]);
    Route::any('cliente/avaliar/{id}', [
        'as' => 'cliente.avaliar',
        'uses' => 'ClienteController@postAvaliar'
    ]);
});

//Quando é clicado em sair da mesa,a mesa fica esperando ser reservada novamente para voltar para o cardapio
Route::get('volte_sempre', [
    'as' => 'volte.sempre',
    'uses' => 'MesaController@MesaVolteSempre'
]);
//Libera a mesa quando é clicado no botão sair da mesa
Route::get('volte_sempre_liberar/{id}', [
    'as' => 'volte.sempre.liberar',
    'uses' => 'MesaController@VolteSempreLiberar'
]);
//Ao clicar no botao mais detalhes na lista de produtos no cardapio,categorias e busca,abre o modal com mais infos do produto
Route::GET('mesa/produto/{id}', [
    'as' => 'mesa.produto',
    'uses' => 'MesaController@getProdutoModal'

]);
//Adiciona produto no carrinho ao clicar no botao adicionar ao carrinho no produto modal
Route::any('adicionar', [
    'as' => 'adicionar',
    'uses' => 'MesaController@Adicionar'
]);
//Remove o produto individualmente no modal carrinho ao clicar no botao excluir item
Route::any('remover/{id}', [
    'as' => 'remover',
    'uses' => 'MesaController@Remover'
]);
//Fecha o pedido quando é clicado no botao confirmar pedido no modal carrinho
Route::any('finalizar_cardapio', [
    'as' => 'finalizar.cardapio',
    'uses' => 'MesaController@FecharPedido'
]);

Route::group(['middleware'=>'Shoppvel\Http\Middleware\admin'], function(){

            ////Parametro Atualizado Venda
            Route::get('logout_admin_caixa/{id}', [
                'as'   => 'logout.admin.caixa',
                'uses' => 'AdminController@logout_admin_caixa'
            ]);
            
            Route::any('troco/{id_pedido?}/{troco?}/{entrada?}/{desconto?}/{total_n?}/{troco_n?}', [
                'as' => 'troco.salvar',
                'uses' => 'AdminController@salvar_Troco'
            ]);

            Route::get('logout_admin/{id}', [
                'as'   => 'logout.admin',
                'uses' => 'AdminController@logout_admin'
            ]);

            Route::get('excluir_imagem/{id}', [
                'as'   => 'excluir.imagem',
                'uses' => 'ProdutoController@excluir_imagem'
            ]);

            Route::get('admin', [
                'as' => 'admin',
                'uses' => 'AdminController@getDashboard'
            ]);
            
            Route::get('admin/dashboard', [
                'as' => 'admin.dashboard',
                'uses' => 'AdminController@getDashboard'
            ]);
            Route::put('admin/pedido/pago/{id}', [
                'as' => 'admin.pedido.pago',
                'uses' => 'AdminController@putPedidoPago'
            ]);
            Route::put('admin/pedido/finalizado/{id}', [
                'as' => 'admin.pedido.finalizado',
                'uses' => 'AdminController@putPedidoFinalizado'
            ]);
            Route::get('admin/pedidos/{id?}', [
                'as' => 'admin.pedidos',
                'uses' => 'AdminController@getPedidos'
            ]);
            //lista todos os pedidos pendentes,pagos,enviados(finalizados), de hoje
            Route::get('todosHoje', [
                'as' => 'admin.pedidos.hoje',
                'uses' => 'AdminController@getTodosHoje'
            ]);
            Route::get('pendentesHoje', [
                'as' => 'admin.pedidos.pendentes.hoje',
                'uses' => 'AdminController@getPendentesHoje'
            ]);
            Route::get('pagosHoje', [
                'as' => 'admin.pedidos.pagos.hoje',
                'uses' => 'AdminController@getPagosHoje'
            ]);

            Route::get('enviadosHoje', [
                'as' => 'admin.pedidos.enviados.hoje',
                'uses' => 'AdminController@getFinalizadosHoje'
            ]);

            /////-----Categorias-----/////
            Route::get('admin/categoria/listar', [
                'as' => 'admin.categoria.listar',
                'uses' => 'CategoriaController@listar'
            ]);
            Route::get('admin/categoria/{id?}/editar', [
                'as' => 'admin.categoria.editar',
                'uses' => 'CategoriaController@editar'
            ]);
            Route::get('admin/categoria/form', [
                'as' => 'admin.categoria.criar',    
                'uses' => 'CategoriaController@criar'
            ]);
            Route::get('admin/categoria/excluir', [
                'as' => 'admin.categoria.excluir',
                'uses' => 'CategoriaController@excluir'
            ]);
            Route::post('admin/categoria/listar', [
                'as' => 'admin.categoria.salvar',
                'uses' => 'CategoriaController@salvar'
            ]);
            Route::patch('admin/categoria/atualizar/{id}', [
                'as' => 'admin.categoria.atualizar',
                'uses' => 'CategoriaController@atualizar'
            ]);
            Route::get('admin/categoria/{id?}/excluir', [
                'as' => 'admin.categoria.excluir',
                'uses' => 'CategoriaController@excluir'
            ]);
            Route::delete('admin/categoria/{id?}/delete', [
                'as' => 'admin.categoria.delete',
                'uses' => 'CategoriaController@delete'
            ]);
            //////-----Marcas-----//////
            Route::get('admin/marca/listar', [
                'as' => 'admin.marca.listar',
                'uses' => 'MarcaController@listar'
            ]);
            Route::get('admin/marca/{id?}/editar', [
                'as' => 'admin.marca.editar',
                'uses' => 'MarcaController@editar'
            ]);
            Route::get('admin/marca/form', [
                'as' => 'admin.marca.criar',    
                'uses' => 'MarcaController@criar'
            ]);
            Route::get('admin/marca/excluir', [
                'as' => 'admin.marca.excluir',
                'uses' => 'MarcaController@excluir'
            ]);
            Route::post('admin/marca/listar', [
                'as' => 'admin.marca.salvar',
                'uses' => 'MarcaController@salvar'
            ]);
            Route::patch('admin/marca/atualizar/{id}', [
                'as' => 'admin.marca.atualizar',
                'uses' => 'MarcaController@atualizar'
            ]);
            Route::get('admin/marca/{id?}/excluir', [
                'as' => 'admin.marca.excluir',
                'uses' => 'MarcaController@excluir'
            ]);
            Route::delete('admin/marca/{id?}/delete', [
                'as' => 'admin.marca.delete',
                'uses' => 'MarcaController@delete'
            ]);
            /////-----Produtos-----//////
            Route::get('admin/produto/listar', [
                'as' => 'admin.produto.listar',
                'uses' => 'ProdutoController@listar'
            ]);
            Route::get('admin/produto/{id?}/editar', [
                'as' => 'admin.produto.editar',
                'uses' => 'ProdutoController@editar'
            ]);
            Route::get('admin/produto/form', [
                'as' => 'admin.produto.criar',    
                'uses' => 'ProdutoController@criar'
            ]);
            Route::get('admin/produto/excluir', [
                'as' => 'admin.produto.excluir',
                'uses' => 'ProdutoController@excluir'
            ]);
            Route::post('admin/produto/listar', [
                'as' => 'admin.produto.salvar',
                'uses' => 'ProdutoController@salvar'
            ]);
            Route::patch('admin/produto/atualizar/{id}', [
                'as' => 'admin.produto.atualizar',
                'uses' => 'ProdutoController@atualizar'
            ]);
            Route::get('admin/produto/{id?}/excluir', [
                'as' => 'admin.produto.excluir',
                'uses' => 'ProdutoController@excluir'
            ]);
            Route::delete('admin/produto/{id?}/delete', [
                'as' => 'admin.produto.delete',
                'uses' => 'ProdutoController@delete'
            ]);
            //////-----Mesas-----//////
            Route::any('mesa', [
                'as' => 'admin.mesa',
                'uses' => 'MesaController@mesa_form'
            ]);
            Route::get('admin/mesa/listar', [
                'as' => 'admin.mesa.listar',
                'uses' => 'MesaController@listar'
            ]);
            Route::get('admin/mesa/{id?}/editar', [
                'as' => 'admin.mesa.editar',
                'uses' => 'MesaController@editar'
            ]);
            Route::get('admin/mesa/form', [
                'as' => 'admin.mesa.criar',    
                'uses' => 'MesaController@mesa_form'
            ]);
            Route::get('admin/mesa/excluir', [
                'as' => 'admin.mesa.excluir',
                'uses' => 'MesaController@excluir'
            ]);
            Route::get('excluir_mesa_selecionar/{id}','MesaController@excluir_mesa_selecionar');

            Route::post('admin/mesa/listar', [
                'as' => 'admin.mesa.salvar',
                'uses' => 'MesaController@salvar'
            ]);
            Route::patch('admin/mesa/atualizar/{id}', [
                'as' => 'admin.mesa.atualizar',
                'uses' => 'MesaController@atualizar'
            ]);
            Route::get('admin/mesa/{id?}/excluir', [
                'as' => 'admin.mesa.excluir',
                'uses' => 'MesaController@excluir'
            ]);
            Route::get('admin/mesa/{id?}/delete', [
                'as' => 'admin.mesa.delete',
                'uses' => 'MesaController@delete'
            ]);
            ///////----Cliente----////////
            Route::get('admin/cliente/listar', [
                'as' => 'admin.cliente.listar',
                'uses' => 'AdminController@listarClientes'
            ]);
            Route::any('admin/cliente/atualizar/{id}', [
                'as' => 'admin.cliente.atualizar',
                'uses' => 'AdminController@atualizarCliente'
            ]);
            Route::get('admin/cliente/excluir/{id}', [
                'as' => 'admin.cliente.excluir',
                'uses' => 'AdminController@excluirCliente'
            ]);
            Route::delete('admin/cliente/{id}/deletar', [
                'as' => 'admin.cliente.deletar',
                'uses' => 'AdminController@deletarCliente'
            ]);
            ///////Desconto Venda
            Route::get('desconto_venda/{id?}', [
                'as' => 'desconto',
                'uses' => 'AdminController@DescontoVenda'
            ]);
});