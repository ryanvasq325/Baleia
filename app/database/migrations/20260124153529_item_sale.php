<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ItemSale extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('item_sale', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_venda', 'biginteger', ['null' => true])
            ->addColumn('id_produto', 'biginteger', ['null' => true])
            ->addColumn('total_bruto', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('quantidade', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('total_liquido', 'decimal', [
                'precision' => 18,
                'scale' => 4,
                'null' => true,
                'comment' => 'Valor a ser pago produto.'
            ])
            ->addColumn('desconto', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('acrescimo', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('nome', 'text', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_venda', 'sale', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->addForeignKey('id_produto', 'product', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
    }
}