<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\City\Cities\Models\City;
use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class AddressDataUpdateTest extends BaseTestCase
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
        $city = City::where(City::DESCRIPTION, 'Canoas')->first();

        return [
            'zipCode'       => '92440504',
            'address'       => 'Quadra L Cinco',
            'numberAddress' => '30',
            'complement'    => 'casa',
            'district'      => 'Guajuviras',
            'cityId'        => $city->id,
            'uf'            => 'RS',
        ];
    }

    /**
     * @dataProvider dataProviderCreateUpdateMembers
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_address_data(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
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
            "$this->endpoint/address-data/id/".$userPayload,
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
    public function test_should_return_error_if_city_not_exists(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $payload = $this->getPayload();

        $payload['cityId'] = Uuid::uuid4Generate();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload->id,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderFormErrorsAddressUpdate
     *
     * @param string $zipCode
     * @param string $address
     * @param string $numberAddress
     * @param string $complement
     * @param string $district
     * @param string $cityId
     * @param string $uf
     * @return void
     */
    public function test_should_return_form_errors(
        string $zipCode,
        string $address,
        string $numberAddress,
        string $complement,
        string $district,
        string $cityId,
        string $uf,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $userPayload = Uuid::uuid4Generate();

        $payload = [
            'zipCode'       => $zipCode,
            'address'       => $address,
            'numberAddress' => $numberAddress,
            'complement'    => $complement,
            'district'      => $district,
            'cityId'        => $cityId,
            'uf'            => $uf,
        ];

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
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

        $payload = $this->getPayload();

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload->id,
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

        $userPayload = User::where(User::EMAIL, Credentials::ADMIN_CHURCH_1)->first();

        $payload = $this->getPayload();

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload->id,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $userPayload = User::where(User::EMAIL, Credentials::USER_WITHOUT_MODULES)->first();

        $response = $this->putJson(
            "$this->endpoint/address-data/id/".$userPayload->id,
            $this->getPayload(),
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
