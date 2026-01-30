<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\DeleteQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;

class Cliente extends Base
{
    public function lista($request, $response)
    {
        try {
            $dadosTemplate = [
                'titulo' => 'Página inicial'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('listcliente'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
        }
    }
    public function cadastro($request, $response)
    {
        try {
            $dadosTemplate = [
                'acao' => 'c',
                'titulo' => 'Cadastro'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('cliente'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
    public function insert($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $FieldAndValues = [
                'nome_fantasia' => $form['nome_fantasia'],
                'sobrenome_razao' => $form['sobrenome_razao'],
                'cpf_cnpj' => $form['cpf_cnpj'],
                'rg_ie' => $form['rg_ie'],
                'ativo' => $form['ativo']
            ];
            $IsSave = InsertQuery::table('customer')->save($FieldAndValues);
            if (!$IsSave) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $IsSave, 'id' => 0], 403);
            }
            $customer = SelectQuery::select('id')->from('customer')->order('id', 'desc')->fetch();
            return $this->SendJson($response, ['status' => true, 'msg' => 'Salvo com sucesso', 'id' => $customer['id']], 201);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function alterar($request, $response, $args)
    {
        try {
            $id = $args['id'];
            $customer = SelectQuery::select()->from('customer')->where('id', '=', $id)->fetch();
            $dadosTemplate = [
                'acao' => 'e',
                'id' => $id,
                'titulo' => 'Cadastro e edição',
                'customer' => $customer
            ];
            return $this->getTwig()
                ->render($response, $this->setView('cliente'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
    public function delete($request, $response)
    {
        try {
            $id = $_POST['id'];
            $IsDelete = DeleteQuery::table('customer')
                ->where('id', '=', $id)
                ->delete();

            if (!$IsDelete) {
                echo 'Erro ao deletar';
                die;
            }
            echo "Deletado com sucesso!";
            die;
        } catch (\Throwable $th) {
            echo "Erro: " . $th->getMessage();
            die;
        }
    }
    public function listcliente($request, $response)
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
            1 => 'nome_fantasia',
            2 => 'sobrenome_razao',
            3 => 'cpf_cnpj',
            4 => 'rg_ie',
            5 => 'ativo',
        ];
        #Capturamos o nome do campo a ser odernado.
        $orderField = $fields[$order];
        #O termo pesquisado
        $term = $form['search']['value'];
        $query = SelectQuery::select('id,nome_fantasia,sobrenome_razao,cpf_cnpj,rg_ie,ativo')->from('customer');
        if (!is_null($term) && ($term !== '')) {
            $query->where('nome_fantasia', 'ilike', "%{$term}%", 'or')
                ->where('sobrenome_razao', 'ilike', "%{$term}%", 'or')
                ->where('cpf_cnpj', 'ilike', "%{$term}%", 'or')
                ->where('rg_ie', 'ilike', "%{$term}%", 'or')
                ->where('ativo', 'ilike', "%{$term}%");
        }
        $customers = $query
            ->order($orderField, $orderType)
            ->limit($length, $start)
            ->fetchAll();
        $customersData = [];
        foreach ($customers as $key => $value) {
            $customersData[$key] = [
                $value['id'],
                $value['nome_fantasia'],
                $value['sobrenome_razao'],
                $value['cpf_cnpj'],
                $value['rg_ie'],
                $value['ativo'] ? 'Sim' : 'Não',
                "<a href=\"/cliente/alterar/" . $value['id'] . "\" class=\"btn btn-warning\">Alterar</a>

                <button type='button'  onclick='Delete(" . $value['id'] . ");' class='btn btn-danger'>
                 <i class=\"bi bi-trash-fill\"></i>
                 Excluir
                 </button>"
            ];
        }
        $data = [
            'status' => true,
            'recordsTotal' => count($customers),
            'recordsFiltered' => count($customers),
            'data' => $customersData
        ];
        $payload = json_encode($data);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
    public function update($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $id = $form['id'];
            if (is_null($id) || empty($id)) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o ID', 'id' => 0], 500);
            }
            $FieldAndValues = [
                'nome_fantasia' => $form['nome_fantasia'],
                'sobrenome_razao' => $form['sobrenome_razao'],
                'cpf_cnpj' => $form['cpf_cnpj'],
                'rg_ie' => $form['rg_ie'],
                'ativo' => $form['ativo'],
            ];
            $IsUpdate = UpdateQuery::table('customer')->set($FieldAndValues)->where('id', '=', $id)->update();
            if (!$IsUpdate) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $IsUpdate, 'id' => 0], 403);
            }
            return $this->SendJson($response, ['status' => true, 'msg' => 'Atualizado com sucesso!', 'id' => $id]);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function print($request, $response)
{
    try {
        // Busca todos os clientes usando sua SelectQuery
        // Selecionei as colunas baseadas no que o seu HTML original pedia
        $customers = SelectQuery::select('id, nome_fantasia, cpf_cnpj')
            ->from('customer')
            ->order('nome_fantasia', 'ASC')
            ->fetchAll();

        $dadosTemplate = [
            'titulo'   => 'Relatório de Clientes',
            'clientes' => $customers,
            'total'    => count($customers)
        ];

        // Renderiza o template passando os dados
        // Nota: Certifique-se que o método render do seu Base aceita esses parâmetros
        return $this->getTwig()
    ->render($response, $this->setView('reports/reportcliente'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);

    } catch (\Exception $e) {
        $response->getBody()->write("Erro ao gerar relatório: " . $e->getMessage());
        return $response->withStatus(500);
    }
}
}
