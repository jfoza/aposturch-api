<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class ProfileDataUpdateTest extends BaseTestCase
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
    public function test_should_update_profile_data(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT->value)->first();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload->id,
            ['profileId' => $profile->id],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormErrorsProfileUpdate
     *
     * @param string $profileId
     * @return void
     */
    public function test_should_return_form_errors(
        string $profileId,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload,
            ['churchId' => $profileId],
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
    public function test_should_return_error_if_profile_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $profile = Uuid::uuid4Generate();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload->id,
            ['profileId' => $profile],
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
        $this->setAuthorizationBearer($credential);

        $userPayload = Uuid::uuid4Generate();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT->value)->first();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload,
            ['profileId' => $profile->id],
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
    public function test_should_return_error_if_profile_is_invalid(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER->value)->first();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload->id,
            ['profileId' => $profile->id],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_2)->first();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT->value)->first();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload->id,
            ['profileId' => $profile->id],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $userPayload = User::where(User::EMAIL, Credentials::USER_WITHOUT_MODULES)->first();

        $response = $this->putJson(
            "$this->endpoint/profile-data/id/".$userPayload->id,
            ['profileId' => Uuid::uuid4Generate()],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
