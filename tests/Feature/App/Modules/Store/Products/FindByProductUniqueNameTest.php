<?php

namespace Tests\Feature\App\Modules\Store\Products;

use App\Modules\Store\Products\Models\Product;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindByProductUniqueNameTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_PRODUCTS_ROUTE;
    }

    public function getProductAssertion(): array
    {
        return [
            'id',
            'product_name',
            'product_description',
            'product_unique_name',
            'product_code',
            'product_value',
            'product_quantity',
            'product_balance',
            'highlight_product',
            'active',
            'created_at',
            'category',
        ];
    }

    public function test_should_return_unique_product()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $product = Product::factory()->create();

        $response = $this->getJson(
            "$this->endpoint/unique-name/$product->product_unique_name",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->getProductAssertion());
    }

    public function test_should_return_error_if_product_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $product = Uuid::uuid4Generate();

        $response = $this->getJson(
            "$this->endpoint/unique-name/$product",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $product = Uuid::uuid4Generate();

        $response = $this->getJson(
            "$this->endpoint/unique-name/$product",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
