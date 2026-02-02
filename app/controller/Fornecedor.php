<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\builder\DeleteQuery;
use app\database\builder\UpdateQuery;

class Fornecedor extends Base
{

    public function lista($request, $response)
    {
        $dadosTemplate = [
            'titulo' => 'Lista de Fornecedores'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('listfornecedor'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function cadastro($request, $response)
    {
        try {
            $dadosTemplate = [
                'acao' => 'c',
                'titulo' => 'Cadastro'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('fornecedor'), $dadosTemplate)
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
            $IsSave = InsertQuery::table('supplier')->save($FieldAndValues);
            if (!$IsSave) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $IsSave, 'id' => 0], 403);
            }
            $supplier = SelectQuery::select('id')->from('supplier')->order('id', 'desc')->fetch();
            return $this->SendJson($response, ['status' => true, 'msg' => 'Salvo com sucesso', 'id' => $supplier['id']], 201);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function alterar($request, $response, $args)
    {
        try {
            $id = $args['id'];
            $supplier = SelectQuery::select()->from('supplier')->where('id', '=', $id)->fetch();
            $dadosTemplate = [
                'acao' => 'e',
                'id' => $id,
                'titulo' => 'Cadastro e edição',
                'supplier' => $supplier
            ];
            return $this->getTwig()
                ->render($response, $this->setView('fornecedor'), $dadosTemplate)
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
            $IsDelete = DeleteQuery::table('supplier')
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
    public function listfornecedor($request, $response)
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
        $query = SelectQuery::select('id,nome_fantasia,sobrenome_razao,cpf_cnpj,rg_ie,ativo')->from('supplier');
        if (!is_null($term) && ($term !== '')) {
            $query->where('nome_fantasia', 'ilike', "%{$term}%", 'or')
                ->where('sobrenome_razao', 'ilike', "%{$term}%", 'or')
                ->where('cpf_cnpj', 'ilike', "%{$term}%", 'or')
                ->where('rg_ie', 'ilike', "%{$term}%", 'or')
                ->where('ativo', 'ilike', "%{$term}%");
        }
        $suppliers = $query
            ->order($orderField, $orderType)
            ->limit($length, $start)
            ->fetchAll();
        $suppliersData = [];
        foreach ($suppliers as $key => $value) {
            $suppliersData[$key] = [
                $value['id'],
                $value['nome_fantasia'],
                $value['sobrenome_razao'],
                $value['cpf_cnpj'],
                $value['rg_ie'],
                $value['ativo'],
                "<a href=\"/fornecedor/alterar/" . $value['id'] . "\" class=\"btn btn-warning\"><i class=\"fa-solid fa-pen-to-square\"></i>Alterar</a>

                <button type='button'  onclick='Delete(" . $value['id'] . ");' class='btn btn-danger'>
                 <i class=\"bi bi-trash-fill\"></i>
                 Excluir
                 </button>"
            ];
        }
        $data = [
            'status' => true,
            'recordsTotal' => count($suppliers),
            'recordsFiltered' => count($suppliers),
            'data' => $suppliersData
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
                'ativo' => $form['ativo']
            ];
            $IsUpdate = UpdateQuery::table('supplier')->set($FieldAndValues)->where('id', '=', $id)->update();
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
            // 1. Busca os dados da tabela de fornecedores
            // Comum incluir o e-mail para fornecedores
            $fornecedores = SelectQuery::select('id, nome_fantasia, sobrenome_razao, cpf_cnpj, rg_ie')
                ->from('supplier')
                ->order('nome_fantasia', 'ASC')
                ->order('sobrenome_razao', 'ASC')
                ->order('cpf_cnpj', 'ASC')
                ->order('rg_ie', 'ASC')
                ->fetchAll();

            // 2. Prepara os dados para o Twig
            $dadosTemplate = [
                'titulo'       => 'Relatório de Fornecedores',
                'fornecedores' => $fornecedores,
                'total'        => count($fornecedores)
            ];

            // 3. Renderiza o template na pasta reports
            return $this->getTwig()
                ->render($response, $this->setView('reports/reportfornecedor'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write("Erro ao gerar relatório: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
