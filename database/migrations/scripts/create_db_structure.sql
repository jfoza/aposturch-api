CREATE EXTENSION IF NOT EXISTS "uuid-ossp" SCHEMA public version '1.1';
comment on extension "uuid-ossp" is 'generate universally unique identifiers (UUIDs)';

CREATE EXTENSION IF NOT EXISTS pgcrypto;
CREATE EXTENSION IF NOT EXISTS unaccent;
CREATE EXTENSION IF NOT EXISTS hstore;

ALTER DATABASE postgres SET timezone TO 'Brazil/East';
