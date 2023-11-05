<?php

namespace App\Shared\Libraries;

use Ramsey\Uuid\Uuid as UuidAlias;

class Uuid extends UuidAlias
{
    public static function isValid(string|null $uuid): bool
    {
        if(is_null($uuid))
        {
            return false;
        }

        return self::getFactory()->getValidator()->validate($uuid);
    }

    public static function uuid4Generate(): string
    {
        return UuidAlias::uuid4()->toString();
    }
}
