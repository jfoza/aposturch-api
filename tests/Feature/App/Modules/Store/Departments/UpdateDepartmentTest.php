<?php

namespace Tests\Feature\App\Modules\Store\Departments;

use App\Modules\Store\Departments\Models\Department;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateDepartmentTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_DEPARTMENTS_ROUTE;
    }

    public function test_should_update_unique_department()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $payload = [
            'name'        => RandomStringHelper::stringsGenerate(),
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$department->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_department_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Uuid::uuid4Generate();

        $payload = [
            'name'        => RandomStringHelper::stringsGenerate(),
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$department",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_department_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department1 = Department::factory()->create();
        $department2 = Department::factory()->create();

        $payload = [
            'name'        => $department1->name,
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$department2->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $department = Department::factory()->create();

        $payload = [
            'name'        => RandomStringHelper::stringsGenerate(),
            'description' => RandomStringHelper::stringsGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$department->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $id
     * @param mixed $name
     * @param mixed $description
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $id,
        mixed $name,
        mixed $description,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'name'        => $name,
            'description' => $description,
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public static function dataProviderFormErrors(): array
    {
        return [
            'Invalid uuid' => [
                'id'          => 'invalid-uuid',
                'name'        => 'test',
                'description' => 'test',
            ],

            'Empty name param' => [
                'id'          => Uuid::uuid4Generate(),
                'name'        => '',
                'description' => 'test',
            ],

            'Invalid description param' => [
                'id'          => Uuid::uuid4Generate(),
                'name'        => 'test',
                'description' => true,
            ]
        ];
    }
}
