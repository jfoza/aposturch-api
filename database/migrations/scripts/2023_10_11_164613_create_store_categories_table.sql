CREATE SCHEMA IF NOT EXISTS store;

create table "store".categories
(
    id uuid default uuid_generate_v4() not null primary key,
    department_id uuid constraint "CategoriesDepartmentIdFk" references "store".departments on delete restrict,
    name varchar not null,
    description varchar,
    active boolean not null default true,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
