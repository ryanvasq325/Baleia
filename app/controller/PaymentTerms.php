<?php

namespace app\controller;

use app\database\builder\InsertQuery;

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
    public function insert($request,$response){
        $form = $request->getParsedBody();
        #Retorna o teste
        $FieldsAndValues = [
            'codigo' => $form['codigo'],
            'titulo'=> $form['titulo']
        ];
        
        $IsSave = InsertQuery::table('payment_terms')->save($FieldsAndValues);
        if (!$IsSave) {
            $dataResponse = [
            'status' => true,
            'msg' => 'Cadastro realizado com sucesso!',
            'id' => 123
        ];
        return $this->SendJson($response, $dataResponse, 201);
    }
}
    public function insertInstallment($request,$response)
    {
        $form = $request->getParsedBody();
        $dataResponse = [
        'status' => true,
        'msg' => 'Cadastro realizado com sucesso!',
        'id' => 123
        ];
        return $this->SendJson($response, $dataResponse, 201);
    }
}