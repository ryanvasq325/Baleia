<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Product extends AbstractMigration
{
<<<<<<< HEAD
    
    public function change(): void
    {
        $table = $this->table('product' , ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('nome', 'string', ['limit' => 255])
              ->addColumn('descrição', 'text')
              ->addColumn('preço', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('estoque', 'integer')
              ->create();
=======

    public function change(): void
    {
        $table = $this->table('product', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_supplier', 'biginteger', ['null' => false])
            ->addColumn('id_company', 'biginteger', ['null' => false])
            ->addColumn('nome', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('descricao', 'text', ['null' => true])
            ->addColumn('preco_custo', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('preco_venda', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('estoque', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('ativo', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_supplier', 'supplier', 'id', ['delete' => 'RESTRICT'])
            ->addForeignKey('id_company', 'company', 'id', ['delete' => 'CASCADE'])
            ->create();
>>>>>>> 53dde170222d35095149a6dd36f7b6ad28de7b56
    }
}
