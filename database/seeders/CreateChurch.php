<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateChurch extends Seeder
{
    public function run(): void
    {
        $sql = 'database/seeders/scripts/create_church.sql';
        DB::unprepared(file_get_contents($sql));
    }
}
