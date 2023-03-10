<?php
namespace App\Features\Users\Users\Services\Utils;

use Illuminate\Support\Facades\Hash;

class HashService
{
    public static function generateHash(string $payload)
    {
        return Hash::make($payload);
    }

    public static function compareHash(string $payload, string $hashed)
    {
        return Hash::check($payload, $hashed);
    }
}

