<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $path = 'database/migrations/scripts/2023_12_01_213744_create_store_products_images_table.sql';
        DB::unprepared(file_get_contents($path));
    }

    public function down(): void
    {
        Schema::dropColumns('store.products_images', ['product_id', 'image_id']);
        Schema::dropIfExists('store.products_images');
    }
};
