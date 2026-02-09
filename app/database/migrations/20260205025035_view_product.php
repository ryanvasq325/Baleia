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
    p.codigo_barras,
    p.descricao_curta,
    p.preco_custo::TEXT,
    p.preco_venda::TEXT,
    p.ativo,
    p.data_cadastro,
    p.data_atualizacao AS data_alteracao,
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