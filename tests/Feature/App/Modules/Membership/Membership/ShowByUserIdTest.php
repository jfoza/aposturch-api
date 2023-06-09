<?php

namespace Tests\Feature\App\Modules\Membership\Membership;

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

    public function test_should_to_list_unique_member_user_by_admin_master_rule()
    {
        $this->setAuthorizationBearer();

        $userMember = User::where(User::EMAIL, $this->adminChurchUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$userMember->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(MembersAssertions::memberAssertion());
    }

    public function test_should_to_list_unique_member_user_by_member()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $userMember = User::where(User::EMAIL, $this->testUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$userMember->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(MembersAssertions::memberAssertion());
    }

    public function test_should_return_error_if_id_not_exists()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $userMember = Uuid::uuid4Generate();

        $response = $this->getJson(
            $this->endpoint."/user/{$userMember}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_payload_is_from_another_church()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $userMember = User::where(User::EMAIL, $this->adminModuleUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$userMember->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_is_from_a_higher_profile()
    {
        $this->setAuthorizationBearerByAssistant();

        $userMember = User::where(User::EMAIL, $this->adminChurchUserEmail)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$userMember->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
