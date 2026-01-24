<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Sale extends AbstractMigration
{
    
    public function change(): void
    {
        $table = $this->table('sale');
        $table->addColumn('id_do_produto', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('quantidade', 'integer')
              ->addColumn('preço_total', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('data_de_venda', 'datetime')
              ->create();
    }
}
