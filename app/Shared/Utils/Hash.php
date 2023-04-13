<?php
namespace App\Shared\Utils;

use Illuminate\Support\Facades\Hash as HashAlias;

class Hash
{
    public static function generateHash(string $payload): string
    {
        return HashAlias::make($payload);
    }

    public static function compareHash(string $payload, string $hashed): bool
    {
        return HashAlias::check($payload, $hashed);
    }
}

