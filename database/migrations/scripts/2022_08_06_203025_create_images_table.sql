CREATE SCHEMA IF NOT EXISTS general;

create table "general".images
(
    id uuid default uuid_generate_v4() not null primary key,
    path text not null,
    type varchar(20) not null
        constraint ck_type
        check ((type)::text = ANY
               ((ARRAY [
                   'product'::character varying,
                   'user-avatar'::character varying,
                   'church'::character varying
                ])::text[])),
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
