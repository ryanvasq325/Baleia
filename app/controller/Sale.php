<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;

class Sale extends Base
{
    public function cadastro($request, $response)
    {
        $dadosTemplate = [
            'titulo' => 'Página inicial'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('sale'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function lista($request, $response)
    {
        $dadosTemplate = [
            'titulo' => 'Página inicial'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('listsale'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function insert($request, $response)
    {
    $form = $request->getParsedBody();
      $id_produto = $form['pesquisa'];
      if (empty($id_produto) or is_null($id_produto)) {
        return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o código do produto', 'id'=> 0],403);
      }
    $customer = SelectQuery::select('id') 
      ->from('customer')
      ->order('id', 'asc')
      ->limit(1)
      ->fetch();
    if (!$customer){
    return $this->SendJson($response, ['status'=> false,'msg'=> 'Restrição: Nenhuma cliente encontrado!', 'id'=> 0],403);
    }
    $id_customer = $customer['id'];
    $FieldAndValue = [
        'id_cliente'=> $id_customer,
        'total_bruto'=> 0,
        'total_liquido'=> 0,
        'desconto'=> 0,
        'acrescimo'=> 0,
        'observacao'=> ''
    ];
    
    try {
        $IsInserted = InsertQuery::table('sale')->save($FieldAndValue);
        if (!$IsInserted){
            return $this->SendJson($response, ['status'=> false,'msg'=> 'Restrição: Falha ao inserir a venda!','id'=> 0],403);
    }
    $sale - SelectQuery::select('id')
    ->from('sale')
    ->order('id', 'desc')
    ->limit(1)
    ->fetch();
    if (!$sale){
        return $this->SendJson($response, ['status'=> false,'msg'=> 'Restrição: Falha ao inserir a venda!','id'=> 0],403);
    }
    $id_sale = $sale["id"];
    }

    

    }
}