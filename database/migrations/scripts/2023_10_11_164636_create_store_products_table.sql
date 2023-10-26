CREATE SCHEMA IF NOT EXISTS store;

create table "store".products
(
    id uuid default uuid_generate_v4() not null primary key,
    product_name varchar not null,
    product_description text,
    product_unique_name varchar unique not null,
    product_code varchar not null,
    value decimal(6,2) not null default 0.00,
    quantity bigint not null default 0,
    balance bigint not null default 0,
    highlight_product boolean not null default false,
    product_image text,
    active boolean not null default true,
    creator_id uuid,
    updater_id uuid,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);

create function product_balance_validate() returns trigger
    language plpgsql
as
$$
BEGIN
    IF COALESCE(NEW.BALANCE,OLD.BALANCE) > COALESCE(NEW.QUANTITY,OLD.QUANTITY) THEN
        RAISE EXCEPTION 'The balance cannot be greater than the amount!';
    END IF;

    IF NEW.BALANCE < 0 THEN
        RAISE EXCEPTION 'Quantity unavailable!';
        RETURN NULL;
    END IF;

    RETURN NEW;
END
$$;

alter function product_balance_validate() owner to postgres;

create trigger product_balance_verify
    before insert or update
    on store.products
    for each row
execute procedure product_balance_validate();
