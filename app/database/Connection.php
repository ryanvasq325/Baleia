<?php

namespace app\database;

use PDO;

class Connection
{
    #variavel de conexão com o banco de dados.
    private static $pdo = null;
    #Método de conexão com o banco de dados.
    public static function connection(): PDO
    {
        #Tentativa de estabelecer uma conexão com o banco de dados com tratamentos de execuxão.
        try {
            #caso já exista a conexão com o banco de dados retornamos a conexão.
            if (static::$pdo) {
                return static::$pdo;
            }
            #Definindo as opções para a conexão do PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, # Lança exeções em caso de erros.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, #Define o modo de fetch padrão com array,associativo.
                PDO::ATTR_EMULATE_PREPARES => false, # Desativa a emulação de prepared statements.
                PDO::ATTR_PERSISTENT => true, #Conexão persistente para melhorar perfomace.
                PDO::ATTR_STRINGIFY_FETCHES => false # Desativa a conversão de valores numericos para strings.
            ];
            #criação da nova conexão PDO com os pârametro do banco de dados.
            static::$pdo = new PDO(
                'pgsql:host=postgres;port=5432;dbname=development_db',
                'senac', # Nome de usuario do banco de dados
                'senac', # Senha do banco de dados
                $options # Opções para conexão PDO.
            );
            static::$pdo->exec("SET NAMES 'utf8'");
            #Caso seja bem-sucedida a conexão retornamos a variavel $pdo;
            return static::$pdo;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro: " . $e->getMessage(), 1);
        }
    }
}