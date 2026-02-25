<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Purchase extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('purchase', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_fornecedor', 'biginteger', ['null' => true])
            ->addColumn('id_usuario', 'biginteger', ['null' => true])
            ->addColumn('total_bruto', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('total_liquido', 'decimal', [
                'precision' => 18,
                'scale' => 4,
                'null' => true,
                'comment' => 'Valor a ser pago pelo cliente.'
            ])
            ->addColumn('desconto', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('acrescimo', 'decimal', ['precision' => 18, 'scale' => 4, 'null' => true])
            ->addColumn('observacao', 'text', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_fornecedor', 'supplier', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->addForeignKey('id_usuario', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
            $this->execute('alter table purchase add column estado_compra stock_movement_compra');
    }
}
