<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;

class Login extends Base
{
    public function login($request, $response)
    {
        try {
            $dadosTemplate = [
                'titulo' => 'Autenticação'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('login'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }
    }
    public function precadastro($request, $response)
    {
        try {
            #Captura os dados do form
            $form = $request->getParsedBody();
            #Capturar os dados do usuário.
            $dadosUsuario = [
                'nome' => $form['nome'],
                'sobrenome' => $form['sobrenome'],
                'cpf' => $form['cpf'],
                'rg' => $form['rg'],
                'senha' => password_hash($form['senhaCadastro'], PASSWORD_DEFAULT)
            ];
            $IsInseted = InsertQuery::table('users')->save($dadosUsuario);
            if (!$IsInseted) {
                return $this->SendJson(
                    $response,
                    ['status' => false, 'msg' => 'Restrição: ' . $IsInseted, 'id' => 0],
                    403
                );
            }
            #Captura o código do ultimo usuário cadastrado na tabela de usuário
            $id = SelectQuery::select('id')->from('users')->order('id', 'desc')->fetch();
            #Colocamos o ID do ultimo usuário cadastrado na varaivel $id_usuario.
            $id_users = $id['id'];
            #Inserimos o e-mail
            $dadosContato = [
                'id_users' => $id_users,
                'tipo' => 'email',
                'email' => $form['email']
            ];
            InsertQuery::table('contact')->save($dadosContato);
            $dadosContato = [];
            #Inserimos o WhastaApp
            $dadosContato = [
                'id_users' => $id_users,
                'tipo' => 'telefone',
                'telefone' => $form['telefone']
            ];
            InsertQuery::table('contact')->save($dadosContato);
            return $this->SendJson($response, ['status' => true, 'msg' => 'Cadastro realizado com sucesso!', 'id' => $id_users], 201);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => true, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
    public function autenticar($request, $response)
    {
        try {
            #Captura os dados do form
            $form = $request->getParsedBody();
            #Caso a posição login não exista, informa a ocorrencia de erro.
            if (!isset($form['login']) || empty($form['login'])) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o login', 'id' => 0], 403);
            }
            #Caso a posição login não exista, informa a ocorrencia de erro.
            if (!isset($form['senha']) || empty($form['senha'])) {
                return $this->SendJson($response, ['status' => false, 'msg' => 'Por favor informe o senha', 'id' => 0], 403);
            }
            $user = SelectQuery::select()
                ->from('view_usuarios_contatos')
                ->where('cpf', '=', $form['login'], 'or')
                ->where('email', '=', $form['login'], 'or')
                ->where('telefone', '=', $form['login'])
                ->fetch();
            if (!isset($user) || empty($user) || count($user) <= 0) {
                return $this->SendJson(
                    $response,
                    ['status' => false, 'msg' => 'Usuário ou senha inválidos!', 'id' => 0],
                    403
                );
            }
            if (!$user['ativo']) {
                return $this->SendJson(
                    $response,
                    ['status' => false, 'msg' => 'Por enquanto você ainda não tem permissão de acessar o sistema!', 'id' => 0],
                    403
                );
            }
            if (!password_verify($form['senha'], $user['senha'])) {
                return $this->SendJson(
                    $response,
                    ['status' => false, 'msg' => 'Usuário ou senha inválidos!', 'id' => 0],
                    403
                );
            }

            if (password_needs_rehash($user['senha'], PASSWORD_DEFAULT)) {
                UpdateQuery::table('users')->set(['senha' => password_hash($form['senha'], PASSWORD_DEFAULT)])->where('id', '=', $user['id'])->update();
            }

            $_SESSION['users'] = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'sobrenome' => $user['sobrenome'],
                'cpf' => $user['cpf'],
                'rg' => $user['rg'],
                'ativo' => $user['ativo'],
                'logado' => true,
                'administrador' => $user['administrador'],
                'email' => $user['email'],
                'telefone' => $user['telefone'],
                'data_cadastro' => $user['data_cadastro'],
                'data_alteracao' => $user['data_alteracao'],
            ];

            return $this->SendJson(
                $response,
                ['status' => true, 'msg' => 'Seja bem-vindo de volta!', 'id' => $user['id']],
                200
            );
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
        }
    }
} 