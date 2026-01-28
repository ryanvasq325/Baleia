<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Supplier extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('supplier', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
            ->addColumn('id_do_produto', 'biginteger', ['null' => true])
            ->addColumn('nome', 'string', ['limit' => 255])
            ->addColumn('contato', 'string', ['limit' => 255])
            ->addColumn('endereço', 'string', ['limit' => 255])
            ->addForeignKey('id_do_produto', 'product', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
    }
}
