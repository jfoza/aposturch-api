<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class ChurchDataUpdateTest extends BaseTestCase
{
    use DataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MEMBERS_ROUTE;
    }

    /**
     * @dataProvider dataProviderUpdateChurchProfilesAndModules
     *
     * @param string $credential
     * @return void
     */
    public function test_should_update_address_data(
        string $credential,
    ): void
    {
        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $church = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_1)->first();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload->id,
            ['churchId' => $church->id],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormErrorsChurchUpdate
     *
     * @param string $churchId
     * @return void
     */
    public function test_should_return_form_errors(
        string $churchId,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload,
            ['churchId' => $churchId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    /**
     * @dataProvider dataProviderUpdateChurchProfilesAndModules
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_church_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $church = Uuid::uuid4Generate();

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload->id,
            ['churchId' => $church],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderUpdateChurchProfilesAndModules
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_not_exists(
        string $credential,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $church = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_1)->first();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload,
            ['churchId' => $church->id],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_the_authenticated_user_cannot_access_the_church_sent_in_the_payload()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $church = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_2)->first();

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload->id,
            ['churchId' => $church->id],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $church = Church::where(Church::UNIQUE_NAME, Credentials::CHURCH_1)->first();

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_2)->first();

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload->id,
            ['churchId' => $church->id],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $userPayload = User::where(User::EMAIL, Credentials::USER_WITHOUT_MODULES)->first();

        $response = $this->putJson(
            "$this->endpoint/church-data/id/".$userPayload->id,
            ['churchId' => Uuid::uuid4Generate()],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
