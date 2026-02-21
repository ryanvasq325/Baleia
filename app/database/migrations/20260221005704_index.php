<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Index extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
            create extension if not exists pg_trgm;

            create index idx_id_users ON users (id);
            create index idx_nome_users on users using gin (nome gin_trgm_ops);
            CREATE INDEX idx_sobrenome_users ON users USING gin (sobrenome gin_trgm_ops);
            create index idx_cpf_users ON users (cpf);
            create index idx_rg_users ON users (rg);

            create index idx_id_supplier ON supplier (id);
            create index idx_nome_fantasia_supplier on supplier using gin (nome_fantasia gin_trgm_ops);
            CREATE INDEX idx_sobrenome_razao_supplier ON supplier USING gin (sobrenome_razao gin_trgm_ops);
            create index idx_cpf_cnpj_supplier ON supplier (cpf_cnpj);
            create index idx_rg_ie_supplier ON supplier (rg_ie);


            create index idx_id_company ON company (id);
            create index idx_nome_fantasia_company on company using gin (nome_fantasia gin_trgm_ops);
            CREATE INDEX idx_sobrenome_razao_company ON company USING gin (sobrenome_razao gin_trgm_ops);
            create index idx_cpf_cnpj_company ON company (cpf_cnpj);
            create index idx_rg_ie_company ON company (rg_ie);


            create index idx_id_customer ON customer (id);
            create index idx_nome_fantasia_customer on customer using gin (nome_fantasia gin_trgm_ops);
            CREATE INDEX idx_sobrenome_razao_customer ON customer USING gin (sobrenome_razao gin_trgm_ops);
            create index idx_cpf_cnpj_customer ON customer (cpf_cnpj);
            create index idx_rg_ie_customer ON customer (rg_ie);
            
            CREATE UNIQUE INDEX idx_mvw_estoque_produto ON mvw_estoque (id_produto);
            ");
    }
}
