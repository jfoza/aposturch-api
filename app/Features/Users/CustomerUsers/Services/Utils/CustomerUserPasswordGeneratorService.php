<?php

namespace App\Features\Users\CustomerUsers\Services\Utils;

use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;

class CustomerUserPasswordGeneratorService
{
    public static function execute(): object
    {
        $password = strtolower(RandomStringHelper::alnumGenerate(6));
        $passwordEncrypt = Hash::generateHash($password);

        return (object) ([
            'password' => $password,
            'passwordEncrypt' => $passwordEncrypt
        ]);
    }
}
