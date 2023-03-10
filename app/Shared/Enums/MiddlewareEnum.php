<?php

namespace App\Shared\Enums;

enum MiddlewareEnum: string
{
    case JWT_AUTH            = 'jwt.auth';
    case ACTIVE_USER         = 'active.user';
    case UUID                = 'uuid';
    case CODE                = 'forgot.password.code';
    case CNPJ                = 'cnpj';
    case EMAIL               = 'valid.email';
    case PRODUCT_UNIQUE_NAME = 'product.unique.name';
}
