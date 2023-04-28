CREATE SCHEMA IF NOT EXISTS membership;

create table "membership".members
(
    id uuid default uuid_generate_v4() not null primary key,
    user_id uuid constraint "MembersUserIdFk" references "users".users on delete cascade,
    type_member_id uuid constraint "MembersTypeMemberIdFk" references "membership".member_types on delete cascade,
    code bigserial,
    active boolean default true,
    creator_id uuid,
    updater_id uuid,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
