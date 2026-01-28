<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Company extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('company', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('nome_fantasia', 'text', ['null' => true])
            ->addColumn('sobrenome_razao', 'text', ['null' => true])
            ->addColumn('cpf_cnpj', 'text', ['null' => true])
            ->addColumn('rg_ie', 'text', ['null' => true])
            ->addColumn('data_nascimento_abertura', 'text', ['null' => true])
            ->addColumn('ativo', 'boolean', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
