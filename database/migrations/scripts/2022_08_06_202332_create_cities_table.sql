CREATE SCHEMA IF NOT EXISTS city;

create table "city".cities
(
    id uuid default uuid_generate_v4() not null primary key,
    description varchar not null,
    uf varchar(2) not null,
    active boolean default true,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
