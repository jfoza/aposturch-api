<?php

namespace Tests\Feature\App\Features\Users\Users;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class ShowAuthenticatedUserTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::USERS_ROUTE;

        $this->jsonStructure = [
            'id',
            'email',
            'avatarId',
            'fullName',
            'role',
            'status',
            'ability',
        ];

        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);
    }

    public function test_should_return_authenticated_user()
    {
        $response = $this->getJson(
            $this->endpoint.'/me',
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }
}
