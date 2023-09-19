<?php

namespace Tests\Feature\App\Modules\Membership\Members;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
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

    public static function dataProviderShowMember(): array
    {
        return [
            'By Admin Master' => [Credentials::ADMIN_MASTER],
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderShowMemberChurchValidation(): array
    {
        return [
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderShowMemberProfilesValidation(): array
    {
        return [
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    /**
     * @dataProvider dataProviderShowMember
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_unique_user_member_by_admin_master_rule(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(MembersAssertions::getMemberIdAssertion());
    }

    /**
     * @dataProvider dataProviderShowMember
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_not_exists(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = Uuid::uuid4Generate();

        $response = $this->getJson(
            $this->endpoint."/user/{$user}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderShowMemberProfilesValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_is_from_a_higher_profile(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = User::where(User::EMAIL, Credentials::ADMIN_CHURCH_1)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderShowMemberProfilesValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_is_from_a_different_module(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = User::where(User::EMAIL, Credentials::GROUPS_ADMIN_MODULE)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderShowMemberChurchValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_payload_is_from_another_church(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = User::where(User::EMAIL, Credentials::ASSISTANT_2)->first();

        $response = $this->getJson(
            $this->endpoint."/user/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
