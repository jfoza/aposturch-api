<?php

namespace Tests\Feature\App\Features\Users\AdminUsers;

use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Helpers\RandomStringHelper;
use Ramsey\Uuid\Nonstandard\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateAdminUserTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::ADMIN_USERS_ROUTE;

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public static function dataProviderFormError(): array
    {
        return [
            'Name empty' => [
                '',
                'email@email.com',
                '',
                '',
                true,
            ],
            'Email empty' => [
                'Test',
                '',
                '',
                '',
                true,
            ],
            'Invalid email' => [
                'Test',
                'invalid-email',
                '',
                '',
                true,
            ],
            'Passwords not match' => [
                'Test',
                'invalid-email',
                'new-pass',
                'new-pass2',
                true,
            ],
        ];
    }

    public function test_should_update_a_unique_admin_user()
    {
        $name = RandomStringHelper::alnumGenerate();

        $user = User::factory()->create();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)->first();

        AdminUser::factory()->create([
            AdminUser::USER_ID => $user->id
        ]);

        User::find($user->id)->profile()->sync([$profile->id]);

        $payload = [
            'name'    => $name,
            'email'   => $name.'@email.com',
        ];

        $response = $this->putJson(
            $this->endpoint."/{$user->id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_update_a_unique_admin_user_with_new_password()
    {
        $name = RandomStringHelper::alnumGenerate();

        $user = User::factory()->create();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)->first();

        AdminUser::factory()->create([
            AdminUser::USER_ID => $user->id
        ]);

        User::find($user->id)->profile()->sync([$profile->id]);

        $payload = [
            'name'                 => $name,
            'email'                => $name.'@email.com',
            'password'             => 'new-pass',
            'passwordConfirmation' => 'new-pass',
        ];

        $response = $this->putJson(
            $this->endpoint."/{$user->id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_admin_user_email_already_exists()
    {
        $user1 = User::first();

        $name = RandomStringHelper::alnumGenerate();

        $user2 = User::factory()->create();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)->first();

        AdminUser::factory()->create([
            AdminUser::USER_ID => $user2->id
        ]);

        User::find($user2->id)->profile()->sync([$profile->id]);

        $payload = [
            'name'    => $name,
            'email'   => $user2->email,
        ];

        $response = $this->putJson(
            $this->endpoint."/{$user1->id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    /**
     * @dataProvider dataProviderFormError
     *
     * @param mixed $name
     * @param mixed $email
     * @param mixed $password
     * @param mixed $passwordConfirmation
     * @return void
     */
    public function test_should_return_error(
        mixed $name,
        mixed $email,
        mixed $password,
        mixed $passwordConfirmation,
    ): void
    {
        $payload = [
            'name'                 => $name,
            'email'                => $email,
            'password'             => $password,
            'passwordConfirmation' => $passwordConfirmation,
        ];

        $id = Uuid::uuid4()->toString();

        $response = $this->putJson(
            $this->endpoint."/{$id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_admin_user_id_not_exists()
    {
        $payload = [
            'name'    => 'test',
            'email'   => 'test986754@email.com',
        ];

        $id = Uuid::uuid4()->toString();

        $response = $this->putJson(
            $this->endpoint."/{$id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
