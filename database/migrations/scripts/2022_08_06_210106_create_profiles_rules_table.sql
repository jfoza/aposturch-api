CREATE SCHEMA IF NOT EXISTS users;

create table "users".profiles_rules
(
    id uuid default uuid_generate_v4() not null primary key,
    profile_id uuid constraint "ProfilesRulesProfileIdFk" references "users".profiles on delete cascade,
    rule_id uuid constraint "ProfilesRulesRuleIdFk" references "users".rules on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
