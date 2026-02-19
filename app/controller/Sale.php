<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;

class Sale extends Base
{
    public function cadastro($request, $response)
    {
        $dadosTemplate = [
            'titulo' => 'Página inicial',
            'acao' => 'c'
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
        #captura os dados do formulário
        $form = $request->getParsedBody();
        #Captura o id do produto
        $id_produto = $form['pesquisa'];
        #Verificar se o id do produto esta vasio ou nulo
        if (empty($id_produto) or is_null($id_produto)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID do produto é obrigatório!',
                'id' => 0
            ], 403);
        }
        #seleciona o id do cliente CONSUMIDOR FINAL para inserir a venda
        $customer = SelectQuery::select('id')
            ->from('customer')
            ->order('id', 'asc')
            ->limit(1)
            ->fetch();
        #Verificar se o cliente não foi encontrado
        if (!$customer) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: Nenhum cliente encontrado!',
                'id' => 0
            ], 403);
        }
        #seleciona o id do cliente CONSUMIDOR FINAL para inserir a venda
        $id_customer = $customer['id'];
        $FieldAndValue = [
            'id_cliente' => $id_customer,
            'total_bruto' => 0,
            'total_liquido' => 0,
            'desconto' => 0,
            'acrescimo' => 0,
            'observacao' => ''
        ];
        try {
            #Tenta inserir a venda no banco de dados e captura o resultado da inserção
            $IsInserted = InsertQuery::table('sale')->save($FieldAndValue);
            #Verificar se a inserção falhou
            if (!$IsInserted) {
                return $this->SendJson(
                    $response,
                    [
                        'status' => false,
                        'msg' => 'Restrição: Falha ao inserir a venda!',
                        'id' => 0
                    ],
                    403
                );
            }
            #Seleciona o id da venda inserida mais recente para retornar na resposta
            $sale = SelectQuery::select('id')
                ->from('sale')
                ->order('id', 'desc')
                ->limit(1)
                ->fetch();
            #Verificar se a venda não foi encontrada
            if (!$sale) {
                return $this->SendJson($response, [
                    'status' => false,
                    'msg' => 'Restrição: Nenhuma venda encontrada!',
                    'id' => 0
                ], 403);
            }
            $id_sale = $sale["id"];
            return $this->SendJson($response, [
                'status' => true,
                'msg' => 'Venda inserida com sucesso!',
                'id' => $id_sale
            ], 201);
        } catch (\Exception $e) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: ' . $e->getMessage(),
                'id' => 0
            ], 500);
        }
    }
    public function update($request, $response)
    {
        $form = $request->getParsedBody();
        $id = $form['id'] ?? null;
        $id_cliente = $form['id_cliente'] ?? null;
        $observacao = $form['observacao'] ?? null;
        if (is_null($id)) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Para alterar a venda informa o código!'], 403);
        }

        try {
            $total_venda = SelectQuery::select("sum(total_liquido) as total_liquido,sum(total_bruto) as total_bruto")
                ->from('item_sale')
                ->where('id_venda', '=', $id)
                ->fetch();

            $FieldAndValues = [
                'total_bruto' => $total_venda['total_bruto'],
                'total_liquido' => $total_venda['total_liquido']
            ];
            #Alteramos o código do cliente
            if (!is_null($id_cliente)) {
                $FieldAndValues['id_cliente'] = $id_cliente;
            }
            #Alteramos a observação
            if (!is_null($observacao)) {
                $FieldAndValues['observacao'] = $observacao;
            }
            $isUpdated = UpdateQuery::table('sale')->set($FieldAndValues)->update();
            if (!$isUpdated) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $isUpdated, 'id' => 0], 500);
            }
            return $this->SendJson($response, [
                'status' => true,
                'msg' => 'Atualização realizada com sucesso!',
                'id' => $id,
                'data' => $total_venda
            ]);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function alterar($request, $response, $args)
    {
        $id = $args['id'];
        try {
            $sale = SelectQuery::select()
                ->from('sale')
                ->where('id', '=', $id)
                ->fetch();
            if (!$sale) {
                return header('Location: /venda/lista');
                die;
            }
            $dadosTemplate = [
                'titulo' => 'Página inicial',
                'acao' => 'e',
                'id' => $id,
                'sale' => $sale
            ];
            return $this->getTwig()
                ->render($response, $this->setView('sale'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
    public function insertitem($request, $response)
    {
        #captura os dados do formulário
        $form = $request->getParsedBody();
        $id = $form['id'] ?? null;
        $id_produto = $form['pesquisa'];
        #Verificar se o id da venda esta vasio ou nulo
        if (empty($id) or is_null($id)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID da venda é obrigatório!',
                'id' => 0
            ], 403);
        }
        #Verificar se o id do produto esta vasio ou nulo
        if (empty($id_produto) or is_null($id_produto)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID do produto é obrigatório!',
                'id' => 0
            ], 403);
        }
        try {
            #Selecionamos o produto que esta sendo vendido
            $produto = SelectQuery::select()->from('product')->where('id', '=', $id_produto)->fetch();
            if (!$produto) {
                return $this->SendJson($response, [
                    'status' => false,
                    'msg' => 'Restrição: Nenhum produto localizado!',
                    'id' => 0
                ], 403);
            }
            $FieldAndValue = [
                'id_venda' => $id,
                'id_produto' => $id_produto,
                'quantidade' => 1,
                'total_bruto' => $produto['valor'],
                'total_liquido' => $produto['valor'],
                'desconto' => 0,
                'acrescimo' => 0,
                'nome' => $produto['nome'],
            ];
            $isInserted = InsertQuery::table('item_sale')->save($FieldAndValue);
            if (!$isInserted) {
                return $this->SendJson($response, [
                    'status' => false,
                    'msg' => 'Restrição: ' . $isInserted,
                    'id' => 0
                ], 500);
            }
            return $this->SendJson($response, [
                'status' => true,
                'msg' => 'Item inserido com sucesso!',
                'id' => 0
            ]);
        } catch (\Exception $e) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: ' . $e->getMessage(),
                'id' => 0
            ], 500);
        }
    }
    public function listitemsale($request, $response)
    {
        $form = $request->getParsedBody();
        $id = $form['id'] ?? null;
        #Verificar se o id da venda esta vasio ou nulo
        if (empty($id) or is_null($id)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID da venda é obrigatório!',
                'id' => 0
            ], 403);
        }

        $total_venda = SelectQuery::select("sum(total_liquido) as total_liquido,sum(total_bruto) as total_bruto")
            ->from('item_sale')
            ->where('id_venda', '=', $id)
            ->fetch();

        $items = SelectQuery::select('id,nome,total_liquido')
            ->from('item_sale')
            ->where('id_venda', '=', $id)
            ->fetchAll();

        $data = [
            'status' => true,
            'id' => $id,
            'msg' => 'Dados listados com sucesso!',
            'sale' => $total_venda,
            'data' => $items
        ];
        return $this->SendJson($response, $data);
    }
}