<?php

namespace Shoppvel\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model {


	protected $fillable = [
		'nome',
        'descricao',
        'avaliacao_qtde',
        'avaliacao_total',
        'qtde_estoque',
        'preco_venda',
        'destacado',
    	'imagem_nome',
    	'categoria_id',
      	'marca_id',
	];

    public function marca() {
        return $this->belongsTo(Marca::class);
    }
    
    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }
    
    public function scopeDestacado($query) {
        return $query->where('destacado', TRUE);
    }
}