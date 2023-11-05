<?php

namespace Tests\Feature\App\Modules\Store\Departments;

use App\Modules\Store\Departments\Models\Department;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindByDepartmentIdTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_DEPARTMENTS_ROUTE;
    }

    public function getDepartmentAssertion(): array
    {
        return [
            'id',
            'name',
            'description',
            'active',
            'created_at',
        ];
    }

    public function test_should_return_unique_department()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $response = $this->getJson(
            "$this->endpoint/id/$department->id",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->getDepartmentAssertion());
    }

    public function test_should_return_error_if_department_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Uuid::uuid4Generate();

        $response = $this->getJson(
            "$this->endpoint/id/$department",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_department_id_has_an_invalid_format()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = 'invalid-uuid';

        $response = $this->getJson(
            "$this->endpoint/id/$department",
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $department = Department::factory()->create();

        $response = $this->getJson(
            "$this->endpoint/id/$department->id",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
