<?php

namespace Tests\Feature\App\Features\Users\AdminUsers;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Shared\Helpers\RandomStringHelper;
use Ramsey\Uuid\Nonstandard\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class CreateAdminUserTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::ADMIN_USERS_ROUTE;

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public function dataProviderFormError(): array
    {
        return [
            'Name empty' => [
                '',
                'email@email.com',
                'pass',
                'pass',
                true,
                Uuid::uuid4()->toString()
            ],
            'Email empty' => [
                'Test',
                '',
                'pass',
                'pass',
                true,
                Uuid::uuid4()->toString()
            ],
            'Invalid email' => [
                'Test',
                'invalid-email',
                'pass',
                'pass',
                true,
                Uuid::uuid4()->toString()
            ],
            'Password empty' => [
                'Test',
                'email@email.com',
                '',
                'pass',
                true,
                Uuid::uuid4()->toString()
            ],
            'Confirm Password empty' => [
                'Test',
                'email@email.com',
                'pass',
                '',
                true,
                Uuid::uuid4()->toString()
            ],
            'Passwords not match' => [
                'Test',
                'email@email.com',
                'pass',
                'pass2',
                true,
                Uuid::uuid4()->toString()
            ],
            'Profile id empty' => [
                'Test',
                'email@email.com',
                'pass',
                'pass',
                true,
                ''
            ],
            'Invalid profile uuid' => [
                'Test',
                'email@email.com',
                'pass',
                'pass',
                true,
                'abc'
            ],
        ];
    }

    public function test_should_create_a_new_admin_user()
    {
        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            'name'                 => $name,
            'email'                => $name.'@email.com',
            'password'             => 'pass',
            'passwordConfirmation' => 'pass',
            'profileId'            => $profile->id
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormError
     *
     * @param mixed $name
     * @param mixed $email
     * @param mixed $password
     * @param mixed $passwordConfirmation
     * @param mixed $profileId
     * @return void
     */
    public function test_should_return_error(
        mixed $name,
        mixed $email,
        mixed $password,
        mixed $passwordConfirmation,
        mixed $profileId,
    ): void
    {
        $payload = [
            'name'                 => $name,
            'email'                => $email,
            'password'             => $password,
            'passwordConfirmation' => $passwordConfirmation,
            'profileId'            => $profileId,
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_profile_not_exists()
    {
        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            'name'                 => $name,
            'email'                => $name.'@email.com',
            'password'             => "pass",
            'passwordConfirmation' => "pass",
            'profileId'            => Uuid::uuid4()->toString()
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
