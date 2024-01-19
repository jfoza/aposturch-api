<?php

namespace App\Shared\Helpers;

use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class RandomStringHelper
{
    public static function basicGenerate(): int
    {
        return mt_rand();
    }

    public static function alphaGenerate(int $len = 8): string
    {
        $pool = '#abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }

    public static function alnumGenerate(int $len = 8): string
    {
        $pool = '0123456789#abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }

    public static function stringsGenerate(int $len = 8): string
    {
        $pool = 'abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }

    public static function numericGenerate(int $len = 8): string
    {
        $pool = '0123456789';

        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }

    public static function noZeroGenerate(int $len = 8): string
    {
        $pool = '123456789';

        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }

    public static function md5Generate(): string
    {
        return md5(uniqid(mt_rand()));
    }

    public static function bcryptGenerate(): string
    {
        return Hash::make(uniqid(mt_rand()));
    }

    public static function sha1Generate(): string
    {
        return sha1(uniqid(mt_rand(), true));
    }

    public static function uuidv4Generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
