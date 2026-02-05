<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ViewPaymentTerms extends AbstractMigration
{

    public function up(): void
    {
        $this->execute("
            CREATE VIEW view_product AS
            SELECT 
                p.id,
                p.codigo,
                p.titulo,
                p.data_atualizacao AS data_alteracao,
                i.total_parcelas,
                i.valor_total,
                i.valor_pago_total,
                i.status,
                TRUE AS pagamento 
            FROM payment_terms p
            LEFT JOIN installment_sale i ON p.id = i.id_payment_terms
        ");
    }
}
