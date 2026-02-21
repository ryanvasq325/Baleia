<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MaterializedView extends AbstractMigration
{

    public function change(): void
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
    }
}
