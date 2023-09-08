<?php

namespace Tests\Feature\App\Features\Users\Users;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateStatusUserTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::USERS_ROUTE;

        $this->jsonStructure = ['status'];
    }

    public function getUser(string $email)
    {
        return User::select(User::ID)
            ->where(User::EMAIL, $email)
            ->first();
    }

    public function test_should_update_status_user_by_admin_master_rule()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $user = $this->getUser(Credentials::ASSISTANT_1);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }

    public function test_should_return_error_if_user_id_not_exists_by_admin_master_rule()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $user = Uuid::uuid4Generate();

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_update_status_user_by_admin_church_rule()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $user = $this->getUser(Credentials::ASSISTANT_1);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }

    public function test_should_return_error_if_user_id_not_exists_by_admin_church_rule()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $user = Uuid::uuid4Generate();

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_is_from_a_higher_profile()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $user = $this->getUser(Credentials::ADMIN_MASTER);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_throw_exception_if_user_is_from_another_church()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $user = $this->getUser(Credentials::ASSISTANT_2);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
