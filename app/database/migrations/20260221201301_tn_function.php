<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TnFunction extends AbstractMigration
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

				CREATE OR REPLACE FUNCTION fn_trigger_purchase_to_stock_movement()
                RETURNS TRIGGER
                LANGUAGE plpgsql
                AS $$
                	BEGIN   
                		IF (NEW.estado_compra = 'RECEBIDO') AND (OLD.estado_compra IS DISTINCT FROM 'RECEBIDO') THEN
                			insert into stock_movement (id_produto, quantidade_entrada,tipo,origem_movimento)
                				select id_produto, coalesce(sum(quantidade), 0),'ENTRADA', 'COMPRA' from item_purchase where id_compra = NEW.id group by id_produto;
							IF NOT FOUND THEN 
								RAISE WARNING 'Trigger fn_trigger_purchase_to_stock_movement: Nenhum item encontrado para a compra ID = %', NEW.id;
							END IF;
						END IF;
						RETURN NEW;
					END;
				$$;

				CREATE OR REPLACE FUNCTION fn_trigger_sale_to_stock_movement()
        		RETURNS TRIGGER
            	LANGUAGE plpgsql AS $$
            		BEGIN
                		IF (NEW.estado_venda = 'VENDA') AND (OLD.estado_venda IS DISTINCT FROM 'VENDA') THEN
                    		INSERT INTO stock_movement(id_produto, quantidade_saida, tipo, origem_movimento)
                    			SELECT id_produto,COALESCE(SUM(quantidade), 0) AS quantidade, 'SAIDA', 'VENDA' FROM item_sale WHERE id_venda = NEW.id GROUP BY id_produto;
                		IF NOT FOUND THEN
                        	RAISE WARNING 'Trigger fn_trigger_sale_to_stock_movement: Nenhum item encontrado para a venda ID = %', NEW.id;
                    	END IF;
					END IF;
                	RETURN NEW;
            		END;
            	$$;
        ");
	}
}
