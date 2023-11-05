<?php

namespace Tests\Feature\App\Features\Users\Users;

use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Illuminate\Http\UploadedFile;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class RemoveUserAvatarTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::USERS_ROUTE;
    }

    public static function dataProviderCredentials(): array
    {
        return [
            'Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public function test_should_remove_image_user_avatar_by_admin_master()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $user = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $this->call(
            'POST',
            "$this->endpoint/upload/image",
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response = $this->deleteJson(
            $this->endpoint."/image/id/$user->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    /**
     * @dataProvider dataProviderCredentials
     *
     * @param string $credential
     * @return void
     */
    public function test_should_remove_image_user_avatar_by_members(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $this->call(
            'POST',
            "$this->endpoint/upload/image",
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response = $this->deleteJson(
            $this->endpoint."/image/id/$user->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_return_error_if_user_not_exists_by_admin_master()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $user = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            $this->endpoint."/image/id/$user",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    /**
     * @dataProvider dataProviderCredentials
     *
     * @param string $credential
     * @return void
     */
    public function test_should_return_error_if_user_not_exists_by_members(
        string $credential
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $user = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            $this->endpoint."/image/id/$user",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
