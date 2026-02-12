<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Product extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('product', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('codigo_barra', 'text', ['null' => true])
            ->addColumn('nome', 'text', ['null' => true])
            ->addColumn('descricao_curta', 'text', ['null' => true])
            ->addColumn('valor', 'decimal', ['null' => true, 'precision' => 18, 'scale' => 4])
            ->addColumn('ativo', 'boolean', ['null' => true, 'default' => true])
            ->addColumn('excluido', 'boolean', ['null' => false, 'default' => false])
            ->create();
    }
}