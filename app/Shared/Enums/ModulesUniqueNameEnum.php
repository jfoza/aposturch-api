<?php

namespace App\Shared\Enums;

enum ModulesUniqueNameEnum: string {
    case USERS = 'USERS';
    case FINANCE = 'FINANCE';
    case MEMBERSHIP = 'MEMBERSHIP';
    case STORE = 'STORE';
    case GROUPS = 'GROUPS';
    case SCHEDULE = 'SCHEDULE';
    case PATRIMONY = 'PATRIMONY';
}
