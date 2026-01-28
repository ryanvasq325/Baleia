<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ItemSale extends AbstractMigration
{
   
    public function change(): void
    {
        $table = $this->table('item_sale' , ['identity' => true, 'null' => false]);
        $table->addColumn('id_de_venda', 'integer')
              ->addColumn('id_do_produto', 'integer')
              ->addColumn('quantidade', 'integer')
              ->addColumn('preço_unitário', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addForeignKey('id_de_venda', 'sale', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->addForeignKey('id_do_produto', 'product', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->create();
    }
}
