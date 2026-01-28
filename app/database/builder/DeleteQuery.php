<?php
#Define o namespace da classe, organizando o código por pastas virtuais.
namespace app\database\builder;
#Importa a classe de conexão com o banco de dados.
use app\database\Connection;
#Classe responsável por montar e executar queries SQL do tipo DELETE de forma fluente.
class DeleteQuery
{
    #Armazena o nome da tabela onde a exclusão será feita.
    private string $table;
    #Array para armazenar as condições da cláusula WHERE.
    private array $where = [];
    #Array de binds para associar os placeholders aos valores no prepared statement.
    private array $binds = [];

    # Método estático que inicia a construção da query DELETE com o nome da tabela.
    public static function table(string $table)  : ?  self
    {
        # Instancia a própria classe
        $self = new self;
        # Define a tabela a ser usada na exclusão
        $self->table = $table;
        # Retorna a instância para permitir encadeamento de métodos (interface fluente)
        return $self;
    }
    #field Campo (coluna) que será filtrado.
    #operator Operador lógico (=, >, <, etc.).
    #string|int $value Valor a ser comparado.
    #$logic Operador lógico adicional (AND, OR). Pode ser nulo.
    public function where(string $field, string $operator, string|int $value, ?string $logic = null): ?self
    {
        # Define um placeholder baseado no nome do campo
        $placeHolder = '';
        $placeHolder = $field;
        # Caso o campo venha com um alias (ex: "u.id"), extrai apenas o nome da coluna (ex: "id")
        if (str_contains($placeHolder, '.')) {
            $placeHolder = substr($field, strpos($field, '.') + 1);
        }
        # Monta a expressão da cláusula WHERE com o placeholder e operador lógico
        $this->where[] = "{$field} {$operator} :{$placeHolder} {$logic}";
        # Associa o valor ao placeholder no array de binds
        $this->binds[$placeHolder] = $value;
        # Retorna a própria instância para encadeamento
        return $this;
    }
    #Método privado que gera a query DELETE em forma de string.
    private function createQuery()
    {
        # Se a tabela não foi definida, lança uma exceção
        if (!$this->table) {
            throw new \Exception("A consulta precisa invocar o método table.");
        }
        # Inicia a construção da query
        $query = '';
        $query = "delete from {$this->table} ";
        # Se houver condições WHERE, adiciona-as à query
        $query .= (isset($this->where) and (count($this->where) > 0)) ? ' where ' . implode(' ', $this->where) : '';
        # Retorna a string da query montada
        return $query;
    }
    #$query A SQL gerada para execução.
    #Retorna true se a execução foi bem-sucedida.
    public function executeQuery($query)
    {
        # Obtém a conexão com o banco de dados via PDO
        $connection = Connection::connection();
        # Prepara a query para evitar SQL Injection
        $prepare = $connection->prepare($query);
        # Executa a query com os valores vinculados (binds)
        return $prepare->execute($this->binds ?? []);
    }
    #Método principal que monta e executa a query DELETE.
    #true em caso de sucesso, ou lança exceção se falhar.
    public function delete(): ?bool
    {
        # Cria a query completa
        $query = $this->createQuery();
        try {
            # Tenta executar a query
            return $this->executeQuery($query);
        } catch (\PDOException $e) {
            # Captura exceções do PDO e lança uma nova exceção personalizada
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }
}