<?php

namespace App\Shared\Enums;

enum MiddlewareEnum
{
    const JWT_AUTH            = 'jwt.auth';
    const ACTIVE_USER         = 'active.user';
    const USER_CHECK          = 'user.check';
    const UUID                = 'uuid';
    const CODE                = 'forgot.password.code';
    const CNPJ                = 'cnpj';
    const EMAIL               = 'valid.email';
    const CHURCH_UNIQUE_NAME = 'church.unique.name';
    const MODULE_ACCESS = 'module.access';
    const PROFILE_UNIQUE_NAME = 'profile.unique.name';
}
