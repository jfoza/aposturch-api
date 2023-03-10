CREATE SCHEMA IF NOT EXISTS module;

create table "module".modules
(
    id uuid default uuid_generate_v4() not null primary key,
    description varchar not null constraint "UQ_0340g74e99a53d99eba88186688f" unique,
    active boolean default true,
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
