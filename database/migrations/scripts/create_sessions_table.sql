CREATE SCHEMA IF NOT EXISTS users;

create table "users".sessions
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "SessionsUserIdFk" references "users".users on delete cascade not null,
    initial_date timestamp not null,
    final_date timestamp,
    token text not null,
    ip_address inet not null,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
