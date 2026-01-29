<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InstallmentSale extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('installment_sale', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_sale', 'biginteger', ['null' => false])
            ->addColumn('id_installment', 'biginteger', ['null' => false])
            ->addColumn('id_payment_terms', 'biginteger', ['null' => false])
            ->addColumn('total_parcelas', 'integer', ['null' => false])
            ->addColumn('valor_total', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => false])
            ->addColumn('valor_pago_total', 'decimal', ['precision' => 12, 'scale' => 2, 'null' => true, 'default' => 0])
            ->addColumn('status', 'string', ['values' => ['aberto', 'pago', 'parcial', 'cancelado'], 'default' => 'aberto', 'null' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_sale', 'sale', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('id_installment', 'installment', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('id_payment_terms', 'payment_terms', 'id', ['delete' => 'RESTRICT'])
            ->create();
    }
}
