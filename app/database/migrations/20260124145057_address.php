<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Address extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('address', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_users', 'biginteger', ['null' => true])
            ->addColumn('titulo', 'text', ['null' => true])
            ->addColumn('cep', 'text', ['null' => true])
            ->addColumn('numero', 'text', ['null' => true])
            ->addColumn('bairro', 'text', ['null' => true])
            ->addColumn('cidade', 'text', ['null' => true])
            ->addColumn('uf', 'text', ['null' => true])
            ->addColumn('ibge', 'text', ['null' => true])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
    }
}
