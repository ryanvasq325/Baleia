-- É uma extensão oficial que fornece funções para gerar UUIDs, como uuid_generate_v4()
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Exemplo de uso: SELECT uuid_generate_v4();
-- Retorno: 550edb11-5ff5-489a-a26f-cec315fd4d5b

CREATE ROLE senac LOGIN PASSWORD 'senac';

CREATE DATABASE development_db OWNER senac;
