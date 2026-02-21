<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PurchaseItem extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('purchase_item', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_buy', 'biginteger', ['null' => false])
            ->addColumn('id_product', 'biginteger', ['null' => false])
            ->addColumn('quantidade', 'integer', ['null' => false])
            ->addColumn('valor_unitario', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('valor_total', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_buy', 'buy', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('id_product', 'product', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
