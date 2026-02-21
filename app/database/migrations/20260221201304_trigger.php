<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Trigger extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
                CREATE OR REPLACE FUNCTION refresh_mvw_estoque()
                RETURNS TRIGGER AS $$
                BEGIN
                REFRESH MATERIALIZED VIEW CONCURRENTLY mvw_estoque;
                RETURN NULL;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER trg_refresh_estoque
                AFTER INSERT OR UPDATE OR DELETE ON stock_movement
                FOR EACH STATEMENT
                EXECUTE FUNCTION refresh_mvw_estoque();
        ");
    }
}
