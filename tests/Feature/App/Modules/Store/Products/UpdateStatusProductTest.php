<?php

namespace Tests\Feature\App\Modules\Store\Products;

use App\Modules\Store\Products\Models\Product;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateStatusProductTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_PRODUCTS_ROUTE;
    }

    public function test_should_update_status_of_many_products_id()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $payload = [
            'productsId' => [
                $product1->id,
                $product2->id,
            ],
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_any_of_the_products_are_not_found()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $product1 = Product::factory()->create();

        $payload = [
            'productsId' => [
                $product1->id,
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

        $productsId = [];

        for($i = 0; $i <= 101; $i++)
        {
            $productsId[] = Uuid::uuid4Generate();
        }

        $response = $this->putJson(
            "$this->endpoint/status",
            ['productsId' => $productsId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_uuid_is_invalid()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $productsId = [
            Uuid::uuid4Generate(),
            'invalid-uuid'
        ];

        $response = $this->putJson(
            "$this->endpoint/status",
            ['categoriesId' => $productsId],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $payload = [
            'productsId' => [
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
            'productsId' => [
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
