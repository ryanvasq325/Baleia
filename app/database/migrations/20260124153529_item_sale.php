<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ItemSale extends AbstractMigration
{

    public function change(): void
    {
<<<<<<< HEAD
        $table = $this->table('item_sale' , ['identity' => true, 'null' => false]);
        $table->addColumn('id_de_venda', 'integer')
              ->addColumn('id_do_produto', 'integer')
              ->addColumn('quantidade', 'integer')
              ->addColumn('preço_unitário', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addForeignKey('id_de_venda', 'sale', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->addForeignKey('id_do_produto', 'product', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->create();
=======
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
>>>>>>> 53dde170222d35095149a6dd36f7b6ad28de7b56
    }
}
