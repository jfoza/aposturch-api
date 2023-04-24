CREATE SCHEMA IF NOT EXISTS users;

CREATE TABLE IF NOT EXISTS users.users
(
    id uuid default uuid_generate_v4() not null primary key,
    person_id uuid constraint "UsersPersonIdFk" references "person".persons,
    name varchar not null,
    email varchar not null constraint "UQ_97672ac88f789774dd47f7c8be3" unique,
    password varchar not null,
    avatar varchar,
    active boolean not null default true,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
