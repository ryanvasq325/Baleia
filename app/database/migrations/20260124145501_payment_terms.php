<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PaymentTerms extends AbstractMigration
{
   
    public function change(): void
    {
        $table = $this->table('payment_terms', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('codigo', 'text', ['null' => true])
            ->addColumn('titulo', 'text', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
