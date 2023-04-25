<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = 'database/migrations/scripts/2023_04_24_194308_create_responsible_church_table.sql';
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('members.responsible_church', ['user_id', 'church_id']);
        Schema::dropIfExists('members.responsible_church');
    }
};
