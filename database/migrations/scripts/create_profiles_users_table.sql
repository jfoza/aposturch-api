CREATE SCHEMA IF NOT EXISTS users;

create table "users".profiles_users
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "ProfilesUsersUserIdFk" references "users".users on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null,
    profile_id uuid constraint "ProfilesUsersProfileIdFk" references "users".profiles on delete cascade
);
