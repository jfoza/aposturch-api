CREATE SCHEMA IF NOT EXISTS store;

create table "store".products_images
(
    id uuid default uuid_generate_v4() not null primary key,
    product_id uuid constraint "ProductsImagesProductIdFk" references "store".products on delete restrict,
    image_id uuid constraint "ProductsImagesImageIdFk" references "general".images on delete restrict,
    created_at  timestamp default now() not null,
    updated_at  timestamp default now() not null
);
