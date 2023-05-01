<?php

namespace Tests\Feature;

use Tests\Feature\Resources\AuthCredentials;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use AuthCredentials;

    const LOGIN_ROUTE = '/api/admin/auth/login';
    const LOGOUT_ROUTE = '/api/auth/logout';
}
