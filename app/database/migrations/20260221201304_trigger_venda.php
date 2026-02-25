<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Trigger extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("
CREATE OR REPLACE FUNCTION fn_trigger_perchuse_to_stock_movement()
                RETURNS TRIGGER
                LANGUAGE plpgsql
                AS $$
                	BEGIN   
                		IF (NEW.estado_compra = 'RECEBIDO') AND (OLD.estado_compra IS DISTINCT FROM 'RECEBIDO') THEN
                			insert into stock_movement (id_produto, quantidade_entrada,tipo,origem_movimento)
                				select id_produto, coalesce(sum(quantidade), 0),'ENTRADA', 'COMPRA' from item_purchase where id_compra = NEW.id group by id_produto;
							IF NOT FOUND THEN 
								RAISE WARNING 'Trigger fn_trigger_perchuse_to_stock_movement: Nenhum item encontrado para a venda ID = %', NEW.id;
							END IF;
						END IF;
						RETURN NEW;
					END;
				$$;
				
CREATE OR REPLACE TRIGGER trg_purchase_to_stock_movement
	AFTER INSERT OR UPDATE OF estado_compra
	ON purchase
	FOR EACH ROW
	EXECUTE FUNCTION fn_trigger_perchuse_to_stock_movement();

        ");
    }
}
