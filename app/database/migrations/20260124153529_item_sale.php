<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ItemSale extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('item_sale', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_sale', 'biginteger')
            ->addColumn('id_product', 'biginteger')
            ->addColumn('quantidade', 'integer')
            ->addColumn('preco_unitario', 'decimal', ['precision' => 18, 'scale' => 2])
            ->addColumn('desconto_item', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
            ->addColumn('preco_total', 'decimal', ['precision' => 18, 'scale' => 2])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_sale', 'sale', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addForeignKey('id_product', 'product', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();

    }
}
