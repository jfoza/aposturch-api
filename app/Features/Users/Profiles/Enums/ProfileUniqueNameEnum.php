<?php

namespace App\Features\Users\Profiles\Enums;

enum ProfileUniqueNameEnum: string
{
    case TECHNICAL_SUPPORT = 'TECHNICAL_SUPPORT';
    case ADMIN_MASTER = 'ADMIN_MASTER';
    case ADMIN_CHURCH = 'ADMIN_CHURCH';
    case ADMIN_MODULE = 'ADMIN_MODULE';
    case ASSISTANT = 'ASSISTANT';
    case MEMBER = 'MEMBER';
}
