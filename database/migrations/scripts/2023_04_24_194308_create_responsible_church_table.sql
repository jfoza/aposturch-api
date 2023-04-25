CREATE SCHEMA IF NOT EXISTS members;

create table "members".responsible_church
(
    id uuid default uuid_generate_v4() not null primary key,
    admin_user_id uuid constraint "ResponsibleChurchAdminUserIdFk" references "users".admin_users on delete cascade,
    church_id uuid constraint "ResponsibleChurchChurchIdFk" references "members".churches on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
