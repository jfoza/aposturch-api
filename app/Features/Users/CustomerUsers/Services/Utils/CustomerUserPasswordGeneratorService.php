<?php

namespace App\Features\Users\CustomerUsers\Services\Utils;

use App\Shared\Helpers\RandomStringHelper;
use App\Features\Users\Users\Services\Utils\HashService;

class CustomerUserPasswordGeneratorService
{
    public static function execute(): object
    {
        $password = strtolower(RandomStringHelper::alnumGenerate(6));
        $passwordEncrypt = HashService::generateHash($password);

        return (object) ([
            'password' => $password,
            'passwordEncrypt' => $passwordEncrypt
        ]);
    }
}
