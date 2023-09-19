<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\Users\Users\Models\User;
use App\Shared\Helpers\Helpers;
use App\Shared\Libraries\Uuid;
use Faker\Generator;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class GeneralDataUpdateTest extends BaseTestCase
{
    use DataProviders;

    private string $endpoint;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MEMBERS_ROUTE;

        $this->faker = app(Generator::class);
    }

    public function getPayload(): array
    {
        return [
            "name" => "Usuario auxiliar 1",
            "email" => Credentials::ASSISTANT_1,
            "phone" => Helpers::onlyNumbers($this->faker->phoneNumber),
        ];
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_general_data(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormErrorsGeneralDataUpdate
     *
     * @param string $name
     * @param string $email
     * @param string $phone
     * @return void
     */
    public function test_should_return_form_errors(
        string $name,
        string $email,
        string $phone,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            [
                "name"  => $name,
                "email" => $email,
                "phone" => $phone,
            ]
        ];

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload,
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
        $userPayload = Uuid::uuid4Generate();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_email_already_exists(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['email'] = Credentials::ASSISTANT_2;

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload->id,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_phone_already_exists(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['phone'] = Credentials::PHONE_ALREADY_EXISTS;

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload->id,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
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
            "$this->endpoint/general-data/id/".$userPayload->id,
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
    public function test_should_return_error_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ADMIN_CHURCH_1)->first();

        $response = $this->putJson(
            "$this->endpoint/general-data/id/".$userPayload->id,
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
            "$this->endpoint/general-data/id/".$userPayload->id,
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
            "$this->endpoint/general-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
