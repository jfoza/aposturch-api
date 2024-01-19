<?php

namespace Tests\Feature\App\Modules\Membership\Members;

use App\Features\City\Cities\Models\City;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Helpers\Helpers;
use App\Shared\Libraries\Uuid;
use Faker\Generator;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class CreateMemberTest extends BaseTestCase
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
        $name  = $this->faker->regexify('[A-Za-z0-9 ]{10}');
        $email  = $this->faker->email;
        $phone = Helpers::onlyNumbers($this->faker->phoneNumber);

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT->value)->first();
        $module  = Module::where(Module::MODULE_UNIQUE_NAME, 'MEMBERSHIP')->first();
        $church  = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_1)->first();
        $city    = City::first();

        return [
            "name"                 => $name,
            "email"                => $email,
            "password"             => "Teste123",
            "passwordConfirmation" => "Teste123",
            "profileId"            => $profile->id,
            "modulesId"            => [$module->id],
            "churchId"             => $church->id,
            "cityId"               => $city->id,
            "phone"                => $phone,
            "zipCode"              => "99999999",
            "address"              => "teste",
            "numberAddress"        => "23",
            "complement"           => "",
            "district"             => "teste",
            "uf"                   => $city->uf
        ];
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_create_new_member(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $response = $this->postJson(
            $this->endpoint,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $passwordConfirmation
     * @param string $profileId
     * @param array $modulesId
     * @param string $churchId
     * @param string $cityId
     * @param string $phone
     * @param string $zipCode
     * @param string $address
     * @param string $numberAddress
     * @param string $complement
     * @param string $district
     * @param string $uf
     * @return void
     */
    public function test_should_return_form_errors(
        string $name,
        string $email,
        string $password,
        string $passwordConfirmation,
        string $profileId,
        array  $modulesId,
        string $churchId,
        string $cityId,
        string $phone,
        string $zipCode,
        string $address,
        string $numberAddress,
        string $complement,
        string $district,
        string $uf,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            "name"                 => $name,
            "email"                => $email,
            "password"             => $password,
            "passwordConfirmation" => $passwordConfirmation,
            "profileId"            => $profileId,
            "modulesId"            => $modulesId,
            "churchId"             => $churchId,
            "cityId"               => $cityId,
            "phone"                => $phone,
            "zipCode"              => $zipCode,
            "address"              => $address,
            "numberAddress"        => $numberAddress,
            "complement"           => $complement,
            "district"             => $district,
            "uf"                   => $uf,
        ];

        $response = $this->postJson(
            $this->endpoint,
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
    public function test_should_return_error_if_profile_id_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['profileId'] = Uuid::uuid4Generate();

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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
    public function test_should_return_error_if_module_id_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['modulesId'] = [Uuid::uuid4Generate()];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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
    public function test_should_return_error_if_church_id_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['churchId'] = Uuid::uuid4Generate();

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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
    public function test_should_return_error_if_city_id_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['cityId'] = Uuid::uuid4Generate();

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['email'] = Credentials::ASSISTANT_3;

        $response = $this->postJson(
            $this->endpoint,
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
        $this->setAuthorizationBearer($credential);

        $payload = $this->getPayload();

        $payload['phone'] = Credentials::PHONE_ALREADY_EXISTS;

        $response = $this->postJson(
            $this->endpoint,
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

        $payload = $this->getPayload();

        $church  = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_2)->first();

        $payload['churchId'] = $church->id;

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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

        $payload = $this->getPayload();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_CHURCH->value)->first();

        $payload['profileId'] = $profile->id;

        $response = $this->postJson(
            $this->endpoint,
            $payload,
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

        $payload = $this->getPayload();

        $module = Module::where(Module::MODULE_UNIQUE_NAME, 'GROUPS')->first();

        $payload['modulesId'] = [$module->id];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $response = $this->postJson(
            $this->endpoint,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
