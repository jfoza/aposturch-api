CREATE SCHEMA IF NOT EXISTS users;

create table "users".users_churches
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "UsersChurchesUserIdFk" references "users".users on delete cascade,
    church_id uuid constraint "UsersChurchesChurchIdFk" references "members".churches on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
