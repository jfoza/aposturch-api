CREATE SCHEMA IF NOT EXISTS store;

create table "store".products_subcategories
(
    id uuid default uuid_generate_v4() not null primary key,
    product_id uuid constraint "ProductsSubcategoriesProductIdFk" references "store".products on delete restrict,
    subcategory_id uuid constraint "ProductsSubcategoriesSubcategoryIdFk" references "store".subcategories on delete restrict,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
