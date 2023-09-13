CREATE SCHEMA IF NOT EXISTS users;

create table "users".profile_types
(
    id uuid default uuid_generate_v4() not null primary key,
    description varchar not null,
    unique_name varchar not null,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
