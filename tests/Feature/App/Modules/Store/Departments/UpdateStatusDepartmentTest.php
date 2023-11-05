<?php

namespace Tests\Feature\App\Modules\Store\Departments;

use App\Modules\Store\Departments\Models\Department;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateStatusDepartmentTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_DEPARTMENTS_ROUTE;
    }

    public function test_should_update_status_of_many_departments_id()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department1 = Department::factory()->create();
        $department2 = Department::factory()->create();

        $payload = [
            'departmentsId' => [
                $department1->id,
                $department2->id,
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_any_of_the_departments_are_not_found()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department1 = Department::factory()->create();

        $payload = [
            'departmentsId' => [
                $department1->id,
                Uuid::uuid4Generate(),
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_more_than_100_records_are_sent()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $departmentsId = [];

        for($i = 0; $i <= 101; $i++)
        {
            $departmentsId[] = Uuid::uuid4Generate();
        }

        $response = $this->putJson(
            "$this->endpoint/status",
            ['departmentsId' => $departmentsId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_uuid_is_invalid()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $departmentsId = [
            Uuid::uuid4Generate(),
            'invalid-uuid'
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            ['departmentsId' => $departmentsId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $payload = [
            'departmentsId' => [
                Uuid::uuid4Generate(),
                Uuid::uuid4Generate(),
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $payload = [
            'departmentsId' => [
                Uuid::uuid4Generate(),
                Uuid::uuid4Generate(),
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
