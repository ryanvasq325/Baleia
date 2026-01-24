<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Product extends AbstractMigration
{
    
    public function change(): void
    {
        $table = $this->table('product' , ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('nome', 'string', ['limit' => 255])
              ->addColumn('descrição', 'text')
              ->addColumn('preço', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('estoque', 'integer')
              ->create();
    }
}
