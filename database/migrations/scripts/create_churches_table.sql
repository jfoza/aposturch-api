CREATE SCHEMA IF NOT EXISTS members;

create table "members".churches
(
    id uuid default uuid_generate_v4() not null primary key,
    name varchar not null,
    phone varchar,
    email varchar,
    youtube varchar,
    facebook varchar,
    instagram varchar,
    zip_code varchar(8),
    address varchar,
    number_address varchar,
    complement varchar,
    district varchar,
    uf varchar(2),
    city_id uuid constraint "ChurchesCityIdFk" references "city".cities,
    active boolean default true,
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
