<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateUsers extends Seeder
{
    public function run(): void
    {
        $sql = 'database/seeders/scripts/create_users.sql';
        DB::unprepared(file_get_contents($sql));
    }
}
