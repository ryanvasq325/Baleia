<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Sale extends AbstractMigration
{
    
    public function change(): void
    {
         $table = $this->table('sale', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
              ->addColumn('id_customer', 'biginteger')
              ->addColumn('id_product', 'biginteger')
              ->addColumn('id_users', 'biginteger', ['null' => false])
              ->addColumn('valor_total', 'decimal', ['precision' => 18, 'scale' => 4, 'default' => 0])
              ->addColumn('status', 'string', ['default' => 'ABERTA'])
              ->addColumn('data_venda', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('data_alteracao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('desconto', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
              ->addColumn('observacoes', 'text', ['null' => true])
              ->addColumn('numero_venda', 'string', ['limit' => 50, 'null' => false])
            ->addForeignKey('id_customer', 'customer', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->addForeignKey('id_product', 'product', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'RESTRICT'])
            ->create();
    }
}
