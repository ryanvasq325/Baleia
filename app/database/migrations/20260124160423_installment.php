<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Installment extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('installment', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('numero_parcela', 'integer', ['null' => false])
            ->addColumn('valor_parcela', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('data_vencimento', 'date', ['null' => false])
            ->addColumn('data_pagamento', 'date', ['null' => true])
            ->addColumn('valor_pago', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true])
            ->addColumn('juros_cobrado', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
            ->addColumn('multa_cobrada', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
            ->addColumn('desconto_concedido', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
            ->addColumn('status', 'string', ['values' => ['pendente', 'pago', 'atrasado', 'cancelado'], 'default' => 'pendente', 'null' => false])
            ->addColumn('observacoes', 'text', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
