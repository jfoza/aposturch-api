CREATE SCHEMA IF NOT EXISTS users;

create table "membership".churches_members
(
    id uuid default uuid_generate_v4() not null primary key,
    member_id uuid constraint "ChurchesMembersMemberIdFk" references "membership".members on delete cascade,
    church_id uuid constraint "ChurchesMembersChurchIdFk" references "membership".churches on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
