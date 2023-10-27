<?php

namespace Tests\Feature\App\Modules\Store\Departments;

use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class RemoveDepartmentTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_DEPARTMENTS_ROUTE;
    }

    public function test_should_remove_unique_department()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$department->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_return_error_if_department_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $departmentId = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$departmentId",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_department_has_subcategories()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        Subcategory::factory()->create([
            Subcategory::DEPARTMENT_ID => $department->id,
            Subcategory::NAME          => RandomStringHelper::alnumGenerate(),
            Subcategory::DESCRIPTION   => RandomStringHelper::alnumGenerate(),
        ]);

        $response = $this->deleteJson(
            "$this->endpoint/id/$department->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $department = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$department",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $department = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$department",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
