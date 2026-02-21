<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class StockMovement extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('stock_movement', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_produto', 'biginteger', ['null' => true])
            ->addColumn('quantidade_entrada', 'integer', ['null' => true])
            ->addColumn('quantidade_saida', 'integer', ['null' => true])
            ->addColumn('estoque_atual', 'integer', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_produto', 'product', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
    }
}
