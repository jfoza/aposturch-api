<?php

namespace Tests\Feature;

use App\Features\Users\Users\Models\User;
use App\Shared\Utils\Auth;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    const ADMIN_USERS_ROUTE = '/api/admin/admin-users';
    const USERS_ROUTE = '/api/admin/users';
    const PROFILES_ROUTE = '/api/admin/profiles';
    const CITIES_ROUTE = '/api/cities';
    const MODULES_ROUTE = '/api/admin/modules';
    const ZIP_CODE_ROUTE = '/api/zip-code';
    const UNIQUE_CODE_GENERATOR = '/api/unique-code/generator';
    const UNIQUE_CODE_PREFIXES = '/api/unique-code/prefixes';
    const CHURCHES_ROUTE = '/api/admin/modules/membership/churches';
    const MEMBERS_ROUTE = '/api/admin/modules/membership/members';
    const STORE_DEPARTMENTS_ROUTE = '/api/admin/modules/store/departments';
    const STORE_CATEGORIES_ROUTE = '/api/admin/modules/store/categories';
    const STORE_PRODUCTS_ROUTE = '/api/admin/modules/store/products';

    private string $email;
    private string $password;
    private array $authorizationBearer = [];

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAuthorizationBearer(): array
    {
        return $this->authorizationBearer;
    }

    public function setAuthorizationBearer(string $email): void
    {
        $this->setEmail($email);

        if(empty($this->authorizationBearer))
        {
            $user = User::where(User::EMAIL, $this->getEmail())->first();

            $token = Auth::generateAccessToken($user->id);

            $this->authorizationBearer = ['Authorization' => "Bearer $token"];
        }
    }
}
