CREATE SCHEMA IF NOT EXISTS general;

create table "general".unique_code_prefixes
(
    id uuid default uuid_generate_v4() not null primary key,
    prefix varchar not null,
    active boolean not null default true,
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
