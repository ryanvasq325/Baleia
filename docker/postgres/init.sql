-- Extensão para UUID
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Role de aplicação
DO
$$
BEGIN
   IF NOT EXISTS (
      SELECT FROM pg_roles WHERE rolname = 'senac'
   ) THEN
      CREATE ROLE senac LOGIN PASSWORD 'senac';
   END IF;
END
$$;

-- Bancos adicionais
CREATE DATABASE testing_db OWNER senac;
CREATE DATABASE production_db OWNER senac;