<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Company extends AbstractMigration
{
    
    public function change(): void
    {
        $table = $this->table('company' , ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('nome', 'string', ['limit' => 255])
              ->addColumn('endereço', 'string', ['limit' => 255])
              ->addColumn('telefone', 'string', ['limit' => 20])
              ->create();
    }
}
