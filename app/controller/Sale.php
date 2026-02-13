<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;

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
                'titulo' => 'Cadastro e edição',
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
    public function insertItemSale($request, $response)
    {
        $form = $request->getParsedBody();
        $id = $form['id'] ?? null;
        $id_produto = $form['pesquisa'];
        if (empty($id) or is_null($id)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID da venda é obrigatório!',
                'id' => 0
            ], 403);
        }

    if (empty($id_produto) or is_null($id_produto)) {
            return $this->SendJson($response, [
                'status' => false,
                'msg' => 'Restrição: O ID do produto é obrigatório!',
                'id' => 0
            ], 403);
        }
        try {
            $produto = SelectQuery::select()->from('product')->where('id', '=', $id_produto)->fetch();
            if (!$produto) {
                return $this->SendJson($response, [
                    'status'=> false,
                    'msg'=> 'Restrição: Nenhum produto localizado!',
                    'id'=> 0
                    ], 403);
            }
                $FieldAndValue = [
                    'id_venda' => $id,
                    'id_produto' => $id_produto,
                    'quantidade' => '1',
                    'total_bruto' => $produto['valor'],
                    'total_liquido' => $produto['valor'],
                    'desconto' => 0,
                    'acrescimo' => 0,
                    'nome' => $produto['nome'],
                ];
            } catch (\Exception $e) {
                return $this->SendJson($response, [
                
                    'status'=> false,
                    'msg'=> 'Restrição: ' .  $e->getMessage(),
                    'id'=> 0
                    ],500);
            }    
        }
    
    }
