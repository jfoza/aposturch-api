<?php

namespace Tests\Feature;

use App\Features\Users\Users\Models\User;
use App\Shared\Utils\Auth;
use Tests\Feature\Resources\Auth\AuthCredentials;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use AuthCredentials;

    const LOGIN_ROUTE = '/api/admin/auth/login';
    const LOGOUT_ROUTE = '/api/auth/logout';
    const ADMIN_USERS_ROUTE = '/api/admin/admin-users';
    const CHURCHES_ROUTE = '/api/admin/modules/membership/churches';

    private array $authorizationBearer = [];

    public function getAuthorizationBearer(): array
    {
        return $this->authorizationBearer;
    }

    public function setAuthorizationBearer(): void
    {
        $this->defineAdminUserTypeCredentials();

        if(empty($this->authorizationBearer))
        {
            $user = User::where(User::EMAIL, $this->getEmail())->first();

            $token = Auth::generateAccessToken($user->id);

            $this->authorizationBearer = ['Authorization' => "Bearer {$token}"];
        }
    }
}
