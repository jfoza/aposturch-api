CREATE SCHEMA IF NOT EXISTS person;

CREATE TABLE IF NOT EXISTS person.persons
(
    id uuid default uuid_generate_v4() not null primary key,
    city_id uuid constraint "PersonsCityIdFk" references "city".cities,
    phone varchar(15) unique,
    zip_code varchar(8),
    address varchar,
    number_address varchar,
    complement varchar,
    district varchar,
    uf varchar(2),
    active boolean not null default true,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
