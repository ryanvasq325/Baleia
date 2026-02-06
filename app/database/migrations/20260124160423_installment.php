<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Installment extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('installment', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_pagamento', 'biginteger', ['null' => true])
            ->addColumn('parcela', 'integer', ['null' => true])
            ->addColumn('intervalor', 'integer', ['null' => true])
            ->addColumn('alterar_vencimento_conta', 'integer', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_pagamento', 'payment_terms', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
            ->create();
    }
}
