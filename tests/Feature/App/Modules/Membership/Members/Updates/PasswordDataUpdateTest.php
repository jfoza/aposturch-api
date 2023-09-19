<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class PasswordDataUpdateTest extends BaseTestCase
{
    use DataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MEMBERS_ROUTE;
    }

    public function getPayload(): array
    {
        return [
            "password"             => "Teste123",
	        "passwordConfirmation" => "Teste123"
        ];
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_password_data_case_1(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderUpdateFromAdminChurch
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_password_data_case_2(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, $credential)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormErrorsPasswordUpdate
     *
     * @param string $password
     * @param string $passwordConfirmation
     * @return void
     */
    public function test_should_return_form_errors(
        string $password,
        string $passwordConfirmation,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_1);

        $userPayload = Uuid::uuid4Generate();

        $payload = [
            "password"             => $password,
            "passwordConfirmation" => $passwordConfirmation,
        ];

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = Uuid::uuid4Generate();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembersProfileValidations
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ADMIN_CHURCH_1)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembersProfileValidations
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_is_from_a_different_module(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::GROUPS_ADMIN_MODULE)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembersChurchValidations
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_the_authenticated_user_is_not_linked_to_the_churches(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_2)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $response = $this->putJson(
            "$this->endpoint/password-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
