<?php

namespace App\Shared\Utils;

use Illuminate\Support\Facades\DB;

class Transaction
{
    public static function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public static function commit(): void
    {
        DB::commit();
    }

    public static function rollback(): void
    {
        DB::rollBack();
    }
}
