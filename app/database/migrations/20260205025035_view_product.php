<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ViewProduct extends AbstractMigration
{
       public function up(): void
{
    $this->execute("
        CREATE VIEW view_product AS
        SELECT 
            p.id,
            p.nome,
            p.codigo_barras,
            p.descricao_curta,
            p.preco_custo,
            p.preco_venda,
            TRUE AS produto_venda
        FROM product p
        WHERE p.excluido IS NOT TRUE
    ");
}
    }
