<?php

namespace App\Shared\Enums;

enum CacheEnum: string {
    case POLICY = 'POLICY';
    case ADMIN_USERS = 'ADMIN_USERS';
    case CITIES_IN_COMPANIES = 'CITIES_IN_COMPANIES';
    case STATES = 'STATES';
    case SYSTEM_DATA = 'SYSTEM_DATA';
}
