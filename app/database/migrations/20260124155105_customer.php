<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Customer extends AbstractMigration
{
   
    public function change(): void
    {
        $table = $this->table('customer' , ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('nome', 'string', ['limit' => 255])
              ->addColumn('email', 'string', ['limit' => 255])
              ->addColumn('telefone', 'string', ['limit' => 20])
              ->create();
    }
}
