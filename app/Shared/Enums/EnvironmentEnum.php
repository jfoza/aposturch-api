<?php

namespace App\Shared\Enums;

enum EnvironmentEnum: string {
    case LOCAL = 'local';
    case PRODUCTION = 'production';
    case STAGING = 'staging';
    case TESTING = 'testing';
}
