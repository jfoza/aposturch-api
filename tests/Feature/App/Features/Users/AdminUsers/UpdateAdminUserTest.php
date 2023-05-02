<?php

namespace Tests\Feature\App\Features\Users\AdminUsers;

use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Helpers\RandomStringHelper;
use Ramsey\Uuid\Nonstandard\Uuid;
use Tests\Feature\BaseTestCase;

class UpdateAdminUserTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::ADMIN_USERS;

        $this->setAuthorizationBearer();
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
            ],
            'Email empty' => [
                'Test',
                '',
                'pass',
                'pass',
                true,
            ],
            'Invalid email' => [
                'Test',
                'invalid-email',
                'pass',
                'pass',
                true,
            ],
            'Active empty' => [
                'Test',
                'email@email.com',
                'pass',
                'pass',
                null,
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
            'active'  => true,
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
            'active'  => true,
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
     * @param mixed $active
     * @return void
     */
    public function test_should_return_error(
        mixed $name,
        mixed $email,
        mixed $active,
    ): void
    {
        $payload = [
            'name'   => $name,
            'email'  => $email,
            'active' => $active,
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
            'active'  => true,
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
