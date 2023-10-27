CREATE SCHEMA IF NOT EXISTS store;

create table "store".products_categories
(
    id uuid default uuid_generate_v4() not null primary key,
    product_id uuid constraint "ProductsCategoriesProductIdFk" references "store".products on delete restrict,
    category_id uuid constraint "ProductsCategoriesCategoryIdFk" references "store".categories on delete restrict,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
