<?php

namespace app\controller;

use app\database\builder\DeleteQuery;
use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;

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
        $paymentTerms = SelectQuery::select() #Permite selecionar todas as colunas, ou colunas especificas.
            ->from('payment_terms') #Informa o nome da tabela.
            ->where('id', '=', $id) #Seleciona somente o registro com o ID informado.
            ->fetch(); #Obter o registro.
        #Caso não exista retornamos para a pagina de lista de condiçãoes de pagamento.
        if (!$paymentTerms) {
            return header('Location: /pagamento/lista');
            die;
        }
        #Passamos os dados para o template.
        $templaData = [
            'titulo' => 'Alteração de termos de pagamento',
            'acao' => 'e',
            'id' => $id,
            'paymentTerms' => $paymentTerms
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
    public function update($request, $response)
    {
        $form = $request->getParsedBody();
        $id = $form['id'];
        if (is_null($id) || $id == '' || empty($id)) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o código da condição de pagamento', 'id' => 0], 403);
        }
        $FieldAndValues = [
            'codigo' => $form['codigo'],
            'titulo' => $form['titulo']
        ];
        $isUpdated = UpdateQuery::table('payment_terms')
            ->set($FieldAndValues)
            ->where('id', '=', $id)
            ->update();
        if (!$isUpdated) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $isUpdated, 'id' => 0], 500);
        }
        return $this->SendJson($response, ['status' => true, 'msg' => 'Alteração realizada com sucesso!', 'id' => $id], 200);
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
    public function loaddatainstallments($request, $response)
    {
        $form = $request->getParsedBody();
        $idPaymentTerms = $form['id'];
        try {
            $installments = SelectQuery::select() #Permite selecionar todas as colunas, ou colunas especificas.
                ->from('installment') #Informa o nome da tabela.
                ->where('id_pagamento', '=', $idPaymentTerms) #Seleciona somente os registro de parcelas do termo de pagamento informado.
                ->fetchAll(); #Obter uma lista de todas as parcelas, da condição de pagamento informada.
            return $this->SendJson($response, ['status' => true, 'data' => $installments]);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage()], 500);
        }
    }
    public function deleteinstallment($request, $response)
    {
        $form = $request->getParsedBody();
        $idInstallment = $form['id_parcelamento'];
        if (empty($idInstallment) || is_null($idInstallment)  || $idInstallment === '') {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o código do parcelamento', 'id' => 0], 403);
        }
        try {
            $isDeleted = DeleteQuery::table('installment')->where('id', '=', $idInstallment)->delete();
            if (!$isDeleted) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $isDeleted, 'id' => 0], 500);
            }
            return $this->SendJson($response, ['status' => true, 'msg' => 'Removido com sucesso!', 'id' => $idInstallment], 200);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
}