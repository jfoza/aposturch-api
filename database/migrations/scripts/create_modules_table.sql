CREATE SCHEMA IF NOT EXISTS module;

create table "module".modules
(
    id uuid default uuid_generate_v4() not null primary key,
    module_description varchar not null,
    module_unique_name varchar not null constraint "UQ_1451h85f00b63e00fcb99297799g" unique,
    active boolean default true,
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
