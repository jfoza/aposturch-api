CREATE SCHEMA IF NOT EXISTS users;

create table "users".modules_rules
(
    id uuid default uuid_generate_v4() not null primary key,
    module_id uuid constraint "ModuleRuleModuleIdFk" references "module".modules on delete cascade,
    rule_id uuid constraint "ModuleRuleRuleIdFk" references "users".rules on delete cascade,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
