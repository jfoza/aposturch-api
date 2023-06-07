<?php

namespace Tests\Feature\App\Features\Users\Users;

use App\Features\Users\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesDataProviders;

class UploadUserAvatarImageTest extends BaseTestCase
{
    use ChurchesDataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::USERS_ROUTE.'/upload/image';
    }

    public function test_must_insert_a_new_user_avatar_image_by_admin_master()
    {
        $this->setAuthorizationBearer();

        $user = User::where(User::EMAIL, 'usuario-auxiliar@hotmail.com')->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'type',
            'path'
        ]);
    }

    public function test_must_insert_a_new_user_avatar_image_by_members()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, 'felipe-dutra@hotmail.com')->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'type',
            'path'
        ]);
    }

    public function test_should_return_error_if_user_not_exists_by_admin_master()
    {
        $this->setAuthorizationBearer();

        $id = Uuid::uuid4()->toString();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_not_exists_by_members()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $id = Uuid::uuid4()->toString();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_payload_is_from_another_church()
    {
        $this->setAuthorizationBearerByAdminChurch();

        $user = User::where(User::EMAIL, 'fabio-dutra@hotmail.com')->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_is_from_a_higher_profile()
    {
        $this->setAuthorizationBearerByAssistant();

        $user = User::where(User::EMAIL, 'felipe-dutra@hotmail.com')->first();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'userId' => $user->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertForbidden();
    }
}
