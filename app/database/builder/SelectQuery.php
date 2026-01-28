<?php

// Define o namespace da classe.
namespace app\database\builder;

// Importa a classe de conexão PDO.
use app\database\Connection;

// Classe para construir e executar consultas SELECT.
class SelectQuery
{
    // Campos SELECT (ex: 'id, nome').
    private string $fields;
    // Nome da tabela (FROM).
    private string $table;
    // Cláusulas WHERE.
    private array $where = [];
    // Valores para PDO binds (placeholders).
    private array $binds = [];
    // Cláusula ORDER BY.
    private string $order;
    // Valor do LIMIT.
    private int $limit;
    // Valor do OFFSET.
    private int $offset;
    // Cláusula LIMIT e OFFSET completa.
    private string $limits;

    // Inicia o construtor (Método de Fábrica).
    public static function select(string $fields = '*'): self
    {
        // Cria nova instância.
        $self = new self;
        // Define os campos (padrão: '*').
        $self->fields = $fields;
        // Retorna a instância.
        return $self;
    }

    // Define a tabela (FROM).
    public function from(string $table): self
    {
        // Armazena o nome da tabela.
        $this->table = $table;
        // Permite encadeamento (Fluent Interface).
        return $this;
    }

    // Adiciona uma cláusula WHERE.
    public function where(string $field, string $operator, string | int $value, ?string $logic = null): self
    {
        // Variável para nome do placeholder.
        $placeholder = '';
        // Inicializa o placeholder.
        $placeholder = $field;

        // Verifica se é um campo de join (contém '.').
        if (str_contains($placeholder, '.')) {
            // Extrai apenas o nome do campo como placeholder (PDO não aceita '.').
            $placeholder = substr($field, strpos($field, '.') + 1);
        }

        // Adiciona a cláusula WHERE ao array.
        // Formato: campo operador :placeholder lógica.
        $this->where[] = "{$field}  {$operator} :{$placeholder} {$logic}";

        // Armazena o valor no array de binds.
        $this->binds[$placeholder] = $value;

        // Retorna para encadeamento.
        return $this;
    }

    // Adiciona a cláusula ORDER BY.
    public function order(string $field, string $typeOrder = 'asc'): self
    {
        // Constrói e armazena o ORDER BY.
        $this->order = " order by {$field}  {$typeOrder}";
        // Retorna para encadeamento.
        return $this;
    }

    // Adiciona LIMIT e OFFSET (para paginação).
    public function limit(int $limit, int $offset = 0): self
    {
        // Armazena LIMIT.
        $this->limit = $limit;
        // Armazena OFFSET.
        $this->offset = $offset;
        // Constrói a string de LIMIT e OFFSET.
        $this->limits = " limit {$this->limit} offset {$this->offset} ";
        // Retorna para encadeamento.
        return $this;
    }

    // Monta a string final da consulta SQL.
    private function createQuery(): string
    {
        // --- Validações ---

        // Verifica se os campos SELECT foram definidos.
        if (!$this->fields) {
            // Lança exceção se faltar campos.
            throw new \Exception("Para realizar uma consulta SQL é necessário informa os campos da consulta");
        }
        // Verifica se a tabela FROM foi definida.
        if (!$this->table) {
            // Lança exceção se faltar tabela.
            throw new \Exception("Para realizar a consulta SQL é necessário informa a nome da tabela.");
        }

        // --- Construção da Query ---

        // Inicializa a query.
        $query = '';
        // Inicia com 'SELECT '.
        $query = 'select ';
        // Adiciona campos e 'FROM '.
        $query .= $this->fields . ' from ';
        // Adiciona a tabela.
        $query .= $this->table;

        // Verifica e adiciona a cláusula WHERE.
        $query .= (isset($this->where) and (count($this->where) > 0))
            // Se houver WHERE, une as cláusulas.
            ? ' where ' . implode(' ', $this->where)
            // Caso contrário, vazio.
            : '';

        // Adiciona ORDER BY, se definido.
        $query .= $this->order ?? '';

        // Adiciona LIMIT e OFFSET, se definido.
        $query .= $this->limits ?? '';

        // Retorna a string SQL completa.
        return $query;
    }

    // Executa e retorna uma única linha (PDO::fetch).
    public function fetch()
    {
        // Variável da query.
        $query = '';
        // Monta a string SQL.
        $query = $this->createQuery();

        // Bloco try-catch para execução.
        try {
            // Obtém a conexão PDO.
            $connection = Connection::connection();
            // Prepara a consulta.
            $prepare = $connection->prepare($query);
            // Executa com os binds.
            $prepare->execute($this->binds ?? []);
            // Retorna o resultado (FETCH_ASSOC).
            return $prepare->fetch(\PDO::FETCH_ASSOC);
            // Captura exceções.
        } catch (\Exception $e) {
            // Lança uma exceção de "Restrição" com o erro original.
            throw new \Exception("Restrição: " . $e->getMessage());
        }
    }

    // Executa e retorna todas as linhas (PDO::fetchAll).
    public function fetchAll()
    {
        // Variável da query.
        $query = '';
        // Monta a string SQL.
        $query = $this->createQuery();

        // Bloco try-catch.
        try {
            // Obtém a conexão PDO.
            $connection = Connection::connection();
            // Prepara a consulta.
            $prepare = $connection->prepare($query);
            // Executa com os binds.
            $prepare->execute($this->binds ?? []);
            // Retorna todos os resultados (FETCH_ASSOC).
            return $prepare->fetchAll(\PDO::FETCH_ASSOC);
            // Captura exceções.
        } catch (\Exception $e) {
            // Lança uma exceção de "Restrição".
            throw new \Exception("Restrição: " . $e->getMessage());
        }
    }
}