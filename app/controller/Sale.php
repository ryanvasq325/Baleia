<?php

namespace app\controller;

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
    public function listsale($request, $response)
    {
        #Captura todas a variaveis de forma mais segura VARIAVEIS POST.
        $form = $request->getParsedBody();
        #Qual a coluna da tabela deve ser ordenada.
        $order = $form['order'][0]['column'];
        #Tipo de ordenação
        $orderType = $form['order'][0]['dir'];
        #Em qual registro se inicia o retorno dos registros, OFFSET
        $start = $form['start'];
        #Limite de registro a serem retornados do banco de dados LIMIT
        $length = $form['length'];
        $fields = [
            0 => 'id',
            1 => 'nome',
            2 => 'preco_venda'
        ];
        #Capturamos o nome do campo a ser odernado.
        $orderField = $fields[$order];
        #O termo pesquisado
        $term = $form['search']['value'];
        $query = SelectQuery::select()->from('view_product');
        if (!is_null($term) && ($term !== '')) {
            $query
                ->where('id', 'ilike', "%{$term}%")
                ->where('nome', 'ilike', "%{$term}%", 'or')
                ->where('preco_venda', 'ilike', "%{$term}%", 'or');
        }
        $product = $query
            ->order($orderField, $orderType)
            ->limit($length, $start)
            ->fetchAll();
        $produtoData = [];
        foreach ($product as $key => $value) {
            $produtoData[$key] = [
                $value['id'],
                $value['nome'],
                $value['preco_venda'],
                "<div class='d-flex gap-2'>
    <a href='/produto/adicionar/{$value['id']}' class='btn btn-warning btn-sm px-2 shadow-sm' style='white-space: nowrap; font-weight: 500;'>
        <i class='bi bi-pencil-square'></i> Adicionar"
            ];
        }
        $data = [
            'status' => true,
            'recordsTotal' => count($product),
            'recordsFiltered' => count($product),
            'data' => $produtoData
        ];
        $payload = json_encode($data);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}