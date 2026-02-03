<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;

class PaymentTerms extends Base
{
    public function lista($request, $response)
    {
        $templaData = [
            'titulo' => 'Lista de termos de pagamento'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('listpaymentterms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function cadastro($request, $response)
    {
        $templaData = [
            'titulo' => 'Cadastro de termos de pagamento',
            'acao' => 'c',
            'id' => '',
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentterms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function alterar($request, $response, $args)
    {
        $id = $args['id'];
        $templaData = [
            'titulo' => 'Alteração de termos de pagamento',
            'acao' => 'e',
            'id' => $id,
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentterms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function insert($request, $response)
    {
        #Captura os dados do front-end.
        $form = $request->getParsedBody();
        $FieldAndValues = [
            'codigo' => $form['codigo'],
            'titulo' => $form['titulo']
        ];
        try {
            $IsSave = InsertQuery::table('payment_terms')->save($FieldAndValues);
            if (!$IsSave) {
                $dataResponse = [
                    'status' => false,
                    'msg' => 'Restrição: ' . $IsSave,
                    'id' => 0
                ];
                return $this->SendJson($response, $dataResponse, 500);
            }
            #Seleciona o ID do ultimo registro da tabela payment_terms.
            $Id = (array) SelectQuery::select('id')->from('payment_terms')->order('id', 'desc')->fetch();
            $dataResponse = [
                'status' => true,
                'msg' => 'Cadastro realizado com sucesso!',
                'id' => $Id['id']
            ];
            #Retorno de teste.
            return $this->SendJson($response, $dataResponse, 201);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function insertInstallment($request, $response)
    {
        #Captura os dados do front-end.
        $form = $request->getParsedBody();
        $FieldAndValues = [
            'id_pagamento' => $form['id'],
            'parcela' => $form['parcela'],
            'intervalor' => $form['intervalo'],
            'alterar_vencimento_conta' => $form['vencimento_incial_parcela']
        ];
        $IsSave = InsertQuery::table('installment')->save($FieldAndValues);
        if (!$IsSave) {
            $dataResponse = [
                'status' => false,
                'msg' => 'Restrição: ' . $IsSave,
                'id' => 0
            ];
            return $this->SendJson($response, $dataResponse, 500);
        }
        #Seleciona o ID do ultimo registro da tabela payment_terms.
        $Id = (array) SelectQuery::select('id')->from('payment_terms')->order('id', 'desc')->fetch();
        $dataResponse = [
            'status' => true,
            'msg' => 'Cadastro realizado com sucesso!',
            'id' => $Id['id']
        ];
        #Retorno de teste.
        return $this->SendJson($response, $dataResponse, 201);
    }
}