<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateStatusSubcategoriesTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function test_should_update_status_of_many_subcategories_id()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory1 = Subcategory::factory()->create();
        $subcategory2 = Subcategory::factory()->create();

        $payload = [
            'subcategoriesId' => [
                $subcategory1->id,
                $subcategory2->id,
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_any_of_the_subcategories_are_not_found()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory1 = Subcategory::factory()->create();

        $payload = [
            'subcategoriesId' => [
                $subcategory1->id,
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

        $subcategoriesId = [];

        for($i = 0; $i <= 101; $i++)
        {
            $subcategoriesId[] = Uuid::uuid4Generate();
        }

        $response = $this->putJson(
            "$this->endpoint/status",
            ['subcategoriesId' => $subcategoriesId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_uuid_is_invalid()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategoriesId = [
            Uuid::uuid4Generate(),
            'invalid-uuid'
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            ['subcategoriesId' => $subcategoriesId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $payload = [
            'subcategoriesId' => [
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
            'subcategoriesId' => [
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
