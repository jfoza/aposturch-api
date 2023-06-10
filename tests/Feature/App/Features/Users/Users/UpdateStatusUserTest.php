<?php

namespace Tests\Feature\App\Features\Users\Users;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
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

    public function test_should_update_status_user_by_admin_master_rule()
    {
        $this->setAuthorizationBearer();

        $user = User::where(User::EMAIL, $this->testUserEmail)->first();

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
        $this->setAuthorizationBearer();

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
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, $this->testUserEmail)->first();

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
        $this->setAuthorizationBearerByAdminChurch();

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
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, $this->adminMasterUserEmail)->first();

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_payload_is_from_another_church()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, $this->adminModuleUserEmail)->first();

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
