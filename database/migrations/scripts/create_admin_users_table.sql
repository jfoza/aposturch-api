CREATE SCHEMA IF NOT EXISTS users;

create table "users".admin_users
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "AdminUsersUserIdFk" references "users".users on delete cascade,
    church_id uuid constraint "AdminUsersChurchIdFk" references "members".churches,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
