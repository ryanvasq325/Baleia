<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PaymentTerms extends AbstractMigration
{
   
    public function change(): void
    {
        $table = $this->table('payment_terms', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
        ->addColumn('descricao', 'string', ['limit' => 255, 'null' => false])
        ->addColumn('dias_vencimento', 'integer', ['null' => false])
        ->addColumn('percentual_desconto', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
        ->addColumn('percentual_juros', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
        ->addColumn('multa_atraso', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
        ->addColumn('ativo', 'boolean', ['default' => true, 'null' => false])
        ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
        ->create();
    }
}
