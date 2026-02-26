<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MvwStockMovement extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
           
        CREATE MATERIALIZED VIEW mvw_estoque AS
                SELECT 
                    id_produto,
                    SUM(COALESCE(quantidade_entrada, 0)) AS total_entradas,
                    SUM(COALESCE(quantidade_saida, 0)) AS total_saidas,
                    (SUM(COALESCE(quantidade_entrada, 0)) - SUM(COALESCE(quantidade_saida, 0))) AS estoque_atual,
                        MAX(data_cadastro) AS ultima_movimentacao
                            FROM 
                                stock_movement
                            GROUP BY 
                                id_produto;
        ");

        $this->execute("
            CREATE INDEX product_id_hash ON product USING HASH (id);
            CREATE INDEX product_nome_hash ON product USING HASH (nome);
            CREATE INDEX stock_movement_idprd_hash ON stock_movement USING HASH (id_produto);
        ");
    }

    public function down(): void
    {
        $this->execute("DROP MATERIALIZED VIEW IF EXISTS mvw_stock_movement CASCADE;");
    }
}
