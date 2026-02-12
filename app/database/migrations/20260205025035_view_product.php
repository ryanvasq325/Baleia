<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ViewProduct extends AbstractMigration
{

    public function up(): void
    {
        $this->execute("
            DROP VIEW IF EXISTS view_product;

CREATE OR REPLACE VIEW view_product AS
SELECT 
    p.id::TEXT,
    p.nome,
    p.codigo_barra,
    p.descricao_curta,
    p.valor,
    p.ativo,
    TRUE AS produto
FROM public.product p
WHERE p.excluido = FALSE ;
        ");
    }
    public function down(): void
    {
        $this->execute("DROP VIEW IF EXISTS view_product");
    }
}