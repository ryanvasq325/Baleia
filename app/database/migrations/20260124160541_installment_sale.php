<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InstallmentSale extends AbstractMigration
{
    
    public function change(): void
    {
        $table = $this->table('installment_sale' , ['identity' => true, 'null' => false]);
        $table->addColumn('id_da_venda', 'integer')
              ->addColumn('número_da_parcela', 'integer')
              ->addColumn('valor', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('data_de_vencimento', 'datetime')
              ->addColumn('status', 'string', ['limit' => 50])
              ->addForeignKey('id_da_venda', 'sale', 'id', ['delete' => 'CASCADE', 'update' => 'NO ACTION'])
              ->create();
    }
}
