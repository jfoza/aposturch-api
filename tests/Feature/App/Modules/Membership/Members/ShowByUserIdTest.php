<?php

namespace Tests\Feature\App\Modules\Membership\Members;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\MembersAssertions;

class ShowByUserIdTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MEMBERS_ROUTE;
    }

    public function test_should_return_unique_user_member_by_admin_master_rule()
    {
        $this->setAuthorizationBearer();

        $user = User::where(User::EMAIL, $this->adminChurchUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(MembersAssertions::getMemberAssertion());
    }

    public function test_should_return_error_if_user_not_exists_by_admin_master_rule()
    {
        $this->setAuthorizationBearer();

        $user = Uuid::uuid4Generate();

        $response = $this->getJson(
            $this->endpoint."/user/{$user}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_unique_user_member_by_admin_church_rule()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, $this->adminChurchUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(MembersAssertions::getMemberAssertion());
    }

    public function test_should_return_error_if_user_is_from_a_higher_profile()
    {
        $this->setAuthorizationBearerByAssistant();

        $user = User::where(User::EMAIL, $this->adminChurchUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_payload_is_from_another_church()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, $this->adminModuleUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
