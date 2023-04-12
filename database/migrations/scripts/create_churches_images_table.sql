CREATE SCHEMA IF NOT EXISTS users;

create table "members".churches_images
(
    id uuid default uuid_generate_v4() not null primary key,
    church_id uuid constraint "ChurchesImagesChurchIdFk" references "members".churches on delete cascade,
    image_id uuid constraint "ChurchesImagesImageIdFk" references "general".images on delete cascade,
    created_at timestamp default now() not null,
    updated_at timestamp default now() not null
);
