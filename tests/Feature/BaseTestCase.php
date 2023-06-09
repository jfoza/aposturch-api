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
    const USERS_ROUTE = '/api/admin/users';
    const MODULES_ROUTE = '/api/admin/modules';
    const CITIES_ROUTE = '/api/cities';
    const ZIP_CODE_ROUTE = '/api/zip-code';
    const CHURCHES_ROUTE = '/api/admin/modules/membership/churches';
    const MEMBERS_ROUTE = '/api/admin/modules/membership/members';

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

    public function setAuthorizationBearerByAdminChurch(): void
    {
        $this->setEmail('felipe-dutra@hotmail.com');
        $this->setPassword('Teste123');

        if(empty($this->authorizationBearer))
        {
            $user = User::where(User::EMAIL, $this->getEmail())->first();

            $token = Auth::generateAccessToken($user->id);

            $this->authorizationBearer = ['Authorization' => "Bearer {$token}"];
        }
    }

    public function setAuthorizationBearerByAdminModule(): void
    {
        $this->setEmail('fabio-dutra@hotmail.com');
        $this->setPassword('Teste123');

        if(empty($this->authorizationBearer))
        {
            $user = User::where(User::EMAIL, $this->getEmail())->first();

            $token = Auth::generateAccessToken($user->id);

            $this->authorizationBearer = ['Authorization' => "Bearer {$token}"];
        }
    }

    public function setAuthorizationBearerByAssistant(): void
    {
        $this->setEmail('usuario-auxiliar-caxias@hotmail.com');
        $this->setPassword('Teste123');

        if(empty($this->authorizationBearer))
        {
            $user = User::where(User::EMAIL, $this->getEmail())->first();

            $token = Auth::generateAccessToken($user->id);

            $this->authorizationBearer = ['Authorization' => "Bearer {$token}"];
        }
    }
}
