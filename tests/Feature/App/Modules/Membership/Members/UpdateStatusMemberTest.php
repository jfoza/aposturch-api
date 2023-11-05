<?php

namespace Tests\Feature\App\Modules\Membership\Members;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateStatusMemberTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MEMBERS_ROUTE;

        $this->jsonStructure = ['status'];
    }

    public function getUser(string $email)
    {
        return User::select(User::ID)
            ->where(User::EMAIL, $email)
            ->first();
    }

    public static function dataProviderUpdateStatus(): array
    {
        return [
            'By Admin Master' => [Credentials::ADMIN_MASTER],
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
        ];
    }

    public static function dataProviderUpdateStatusValidation(): array
    {
        return [
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateStatus
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_status_member(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = $this->getUser(Credentials::ASSISTANT_3);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @dataProvider dataProviderUpdateStatus
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_id_not_exists(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = Uuid::uuid4Generate();

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderUpdateStatusValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_payload_is_from_another_church(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = $this->getUser(Credentials::ASSISTANT_2);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderUpdateStatusValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_is_from_a_different_module(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::MEMBERSHIP_ADMIN_MODULE);

        $user = $this->getUser(Credentials::GROUPS_ADMIN_MODULE);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderUpdateStatusValidation
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_is_from_a_higher_profile(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = $this->getUser(Credentials::ADMIN_CHURCH_1);

        $response = $this->putJson(
            $this->endpoint."/status/id/{$user->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
