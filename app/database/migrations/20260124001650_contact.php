<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Contact extends AbstractMigration
{
    public function change(): void
    {
        $table - $this->table('contact', ['id' => false, 'primary_key' => ['id']]);
        $table->addColuam('id')
    }
}
