<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Trigger extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
                CREATE TRIGGER trg_refresh_estoque
                AFTER INSERT OR UPDATE OR DELETE ON stock_movement
                FOR EACH STATEMENT
                EXECUTE FUNCTION refresh_mvw_estoque();

                CREATE OR REPLACE TRIGGER trg_purchase_to_stock_movement
				AFTER INSERT OR UPDATE OF estado_compra
				ON purchase
				FOR EACH ROW
				EXECUTE FUNCTION fn_trigger_purchase_to_stock_movement();

                CREATE OR REPLACE TRIGGER trg_sale_to_stock_movement
                AFTER INSERT OR UPDATE OF estado_venda
                ON sale
                FOR EACH ROW
                EXECUTE FUNCTION fn_trigger_sale_to_stock_movement();
        
        ");
    }
}
