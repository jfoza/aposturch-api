<?php

namespace Tests\Feature\App\Features\Users\Profiles;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class ProfilesListTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::PROFILES_ROUTE;

        $this->jsonStructure = [
            'id',
            'profile_type_id',
            'description',
            'unique_name',
            'active',
        ];

        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);
    }

    public static function dataProviderProfilesList(): array
    {
        return [
            'By Admin Master' => [Credentials::ADMIN_MASTER],
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderProfileTypes(): array
    {
        return [
            'Administrative' => ['ADMINISTRATIVE'],
            'Membership'     => ['MEMBERSHIP'],
            'Common Users'   => ['COMMON_USER'],
        ];
    }

    /**
     * @dataProvider dataProviderProfilesList
     *
     * @return void
     */
    public function test_should_return_profiles_list(
        string $credential,
    )
    {
        $this->setAuthorizationBearer($credential);

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }

    /**
     * @dataProvider dataProviderProfilesList
     *
     * @return void
     */
    public function test_should_return_profiles_list_with_filters_setup_1(
        string $credential,
    )
    {
        $this->setAuthorizationBearer($credential);

        $response = $this->getJson(
            $this->endpoint.'?profileTypeUniqueName=MEMBERSHIP',
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }

    /**
     * @dataProvider dataProviderProfileTypes
     *
     * @param string $profileType
     * @return void
     */
    public function test_should_return_profiles_list_with_filters_setup_2(
        string $profileType,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $endpointAux = $this->endpoint."?profileTypeUniqueName=ADMINISTRATIVE";

        $response = $this->getJson(
            $endpointAux,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }
}
