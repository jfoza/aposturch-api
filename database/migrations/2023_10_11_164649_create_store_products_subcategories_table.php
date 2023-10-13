<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $path = 'database/migrations/scripts/2023_10_11_164649_create_store_products_subcategories_table.sql';
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropColumns('store.products_subcategories', ['product_id', 'subcategory_id']);
        Schema::dropIfExists('store.products_subcategories');
    }
};
