<?php

namespace Tests\Feature\App\Features\Users\AdminUsers;

use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Users\Assertions;

class FindAdminUserByIdTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::ADMIN_USERS_ROUTE;

        $this->setAuthorizationBearer();
    }

    public function test_should_return_unique_admin_user_by_id()
    {
        $user = User::factory()->create();

        $profile = Profile::where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)->first();

        AdminUser::factory()->create([
            AdminUser::USER_ID => $user->id
        ]);

        User::find($user->id)->profile()->sync([$profile->id]);

        $response = $this->getJson(
            $this->endpoint."/id/{$user->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(Assertions::adminUserAssertion());
    }

    public function test_should_return_error_admin_user_id_not_exists()
    {
        $id = Uuid::uuid4()->toString();

        $response = $this->getJson(
            $this->endpoint."/id/{$id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
