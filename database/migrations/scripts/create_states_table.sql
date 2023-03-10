CREATE SCHEMA IF NOT EXISTS city;

create table "city".state
(
    id uuid default uuid_generate_v4() not null primary key,
    uf varchar(2) not null,
    description varchar not null,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
