<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CustomTypes extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
                CREATE TYPE stock_movement_direction AS ENUM ('ENTRADA','SAIDA');
                CREATE TYPE stock_movement_origin AS ENUM (
                        'VENDA',
                        'CANCELAMENTO_VENDA',
                        'COMPRA',
                        'CANCELAMENTO_COMPRA',
                        'AJUSTE_MANUAL',
                        'INVENTARIO',
                        'TRANSFERENCIA'
                );
                CREATE TYPE stock_movement_venda AS ENUM (
                        'PRE_VENDA',
                        'ORCAMENTO',
                        'VENDA'
                );
                CREATE TYPE stock_movement_compra AS ENUM (
	                    'EM_ANDAMENTO',
	                    'RECEBIDO'
                );
        ");
    }
}
