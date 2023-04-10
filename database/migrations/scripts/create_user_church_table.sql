CREATE SCHEMA IF NOT EXISTS users;

create table "users".user_church
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "UserChurchUserIdFk" references "users".users on delete cascade,
    church_id uuid constraint "UserChurchChurchIdFk" references "members".churches on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
