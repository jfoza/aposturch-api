CREATE SCHEMA IF NOT EXISTS membership;

create table "membership".member_types
(
    id uuid default uuid_generate_v4() not null primary key,
    unique_name varchar not null,
    description varchar not null,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
