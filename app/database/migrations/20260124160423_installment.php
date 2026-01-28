<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Installment extends AbstractMigration
{

    public function change(): void
    {
<<<<<<< HEAD
        $table = $this->table('installment' , ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['identity' => true, 'null' => false])
              ->addColumn('id_da_venda', 'integer')
              ->addColumn('número_da_parcela', 'integer')
              ->addColumn('valor', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('data_de_vencimento', 'datetime')
              ->addColumn('status', 'string', ['limit' => 50])
              ->addForeignKey('id_da_venda', 'sale', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->create();
=======
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
>>>>>>> 53dde170222d35095149a6dd36f7b6ad28de7b56
    }
}
