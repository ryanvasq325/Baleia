<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateViewUsuariosContatos extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE VIEW view_usuarios_contatos AS
            SELECT 
                u.id,
                u.nome,
                u.senha,
                u.sobrenome,
                u.cpf,
                u.rg,
                u.ativo,
                u.administrador,
                u.data_cadastro,
                u.data_atualizacao AS data_alteracao,
                c.email,
                c.telefone,
                TRUE AS logado -- Campo virtual solicitado
            FROM users u
            LEFT JOIN contact c ON u.id = c.id_users
        ");
    }
}
