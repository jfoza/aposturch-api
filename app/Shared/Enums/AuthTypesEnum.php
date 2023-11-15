<?php

namespace App\Shared\Enums;

enum AuthTypesEnum: string {
    case EMAIL_PASSWORD = 'EMAIL_PASSWORD';
    case GOOGLE = 'GOOGLE';
    case MICROSOFT = 'MICROSOFT';
}
