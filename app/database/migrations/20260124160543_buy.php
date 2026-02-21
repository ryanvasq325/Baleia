<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Buy extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('buy', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_supplier', 'biginteger', ['null' => false])
            ->addColumn('data_compra', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('valor_total', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('status', 'string', ['values' => ['aberto', 'finalizado', 'cancelado'], 'default' => 'aberto', 'null' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_supplier', 'supplier', 'id', ['delete' => 'CASCADE'])
            ->create();

    }
}
