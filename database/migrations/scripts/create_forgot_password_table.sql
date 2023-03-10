CREATE SCHEMA IF NOT EXISTS users;

create table "users".forgot_password
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "ForgotPasswordUserIdFk" references "users".users on delete cascade not null,
    code text not null,
    validate timestamp(0) with time zone not null,
    active boolean default true not null,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
