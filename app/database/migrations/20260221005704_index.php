<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Index extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
            create extension if not exists pg_trgm;

            create index idx_id_customer ON customer (id);

            create index idx_nome_fantasia_customer on customer using gin (nome_fantasia gin_trgm_ops);

            CREATE INDEX idx_sobrenome_razao_customer ON customer USING gin (sobrenome_razao gin_trgm_ops);
        ");
    }
}
