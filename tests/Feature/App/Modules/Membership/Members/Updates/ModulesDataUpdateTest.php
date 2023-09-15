<?php

namespace Tests\Feature\App\Modules\Membership\Members\Updates;

use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\ModulesUniqueNameEnum;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Members\DataProviders;

class ModulesDataUpdateTest extends BaseTestCase
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
    public function test_should_update_modules_data(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_3)->first();

        $module = Module::where(Module::MODULE_UNIQUE_NAME, ModulesUniqueNameEnum::MEMBERSHIP->value)->first();

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload->id,
            ['modulesId' => [$module->id]],
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderFormErrorsModulesUpdate
     *
     * @param array $modulesId
     * @return void
     */
    public function test_should_return_form_errors(
        mixed $modulesId,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload,
            ['modulesId' => $modulesId],
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
    public function test_should_return_error_if_user_not_exists(
        string $credential,
    ): void
    {
        $userPayload = Uuid::uuid4Generate();

        $module = Module::where(Module::MODULE_UNIQUE_NAME, ModulesUniqueNameEnum::MEMBERSHIP->value)->first();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload,
            ['modulesId' => [$module->id]],
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
    public function test_should_return_error_if_module_not_exists(
        string $credential,
    ): void
    {
        $this->setAuthorizationBearer($credential);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $module = Module::where(Module::MODULE_UNIQUE_NAME, ModulesUniqueNameEnum::MEMBERSHIP->value)->first();

        $moduleNotExists = Uuid::uuid4Generate();

        $this->setAuthorizationBearer($credential);

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload->id,
            ['modulesId' => [$module->id, $moduleNotExists]],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_CHURCH_1);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_2)->first();

        $module = Module::where(Module::MODULE_UNIQUE_NAME, ModulesUniqueNameEnum::MEMBERSHIP->value)->first();

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload->id,
            ['modulesId' => [$module->id]],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_the_user_does_not_have_modules()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $userPayload = User::where(User::EMAIL, Credentials::ASSISTANT_1)->first();

        $moduleId = Uuid::uuid4Generate();

        $response = $this->putJson(
            "$this->endpoint/modules-data/id/".$userPayload->id,
            ['modulesId' => [$moduleId]],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
