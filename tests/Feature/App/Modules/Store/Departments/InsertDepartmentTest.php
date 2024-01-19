<?php

namespace Tests\Feature\App\Modules\Store\Departments;

use App\Modules\Store\Departments\Models\Department;
use App\Shared\Helpers\RandomStringHelper;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class InsertDepartmentTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_DEPARTMENTS_ROUTE;
    }

    public function test_should_create_new_department()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'name'        => RandomStringHelper::stringsGenerate(),
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    public function test_should_return_error_if_department_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $payload = [
            'name'        => $department->name,
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $payload = [
            'name'        => RandomStringHelper::stringsGenerate(),
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $name
     * @param mixed $description
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $name,
        mixed $description,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'name'        => $name,
            'description' => $description,
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public static function dataProviderFormErrors(): array
    {
        return [
            'Empty name param' => [
                'name'        => '',
                'description' => RandomStringHelper::stringsGenerate(),
            ],

            'Invalid description param' => [
                'name'        => RandomStringHelper::stringsGenerate(),
                'description' => true,
            ]
        ];
    }
}
