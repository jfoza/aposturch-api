<?php

namespace Tests\Feature\App\Modules\Store\Products;

use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateProductTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_PRODUCTS_ROUTE;
    }

    public function getPayload(): array
    {
        $productCode = strtoupper(RandomStringHelper::alphaGenerate(2).RandomStringHelper::numericGenerate(5));

        return [
            'productName'        => RandomStringHelper::alnumGenerate(),
            'productDescription' => RandomStringHelper::alnumGenerate(),
            'productCode'        => $productCode,
            'value'              => 100.25,
            'quantity'           => 10,
            'balance'            => 10,
            'highlightProduct'   => false,
            'categoriesId'       => [],
        ];
    }

    public function test_should_update_unique_product()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $category = Category::factory()->create();
        $product     = Product::factory()->create();

        $payload['categoriesId'] = [$category->id];

        $response = $this->putJson(
            "$this->endpoint/id/$product->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_product_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $product = Uuid::uuid4Generate();

        $payload['categoriesId'] = [Uuid::uuid4Generate()];

        $response = $this->putJson(
            "$this->endpoint/id/$product",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_product_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $category = Category::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $payload['categoriesId'] = [$category->id];
        $payload['productName']  = $product2->product_name;

        $response = $this->putJson(
            "$this->endpoint/id/$product1->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_product_code_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $category = Category::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $payload['categoriesId'] = [$category->id];
        $payload['productCode']  = $product2->product_code;

        $response = $this->putJson(
            "$this->endpoint/id/$product1->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $product = Product::factory()->create();

        $payload['categoriesId'] = [Uuid::uuid4Generate()];

        $response = $this->putJson(
            "$this->endpoint/id/$product->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $payload = $this->getPayload();

        $product = Uuid::uuid4Generate();

        $payload['categoriesId'] = [Uuid::uuid4Generate()];

        $response = $this->putJson(
            "$this->endpoint/id/$product",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $id
     * @param mixed $productName
     * @param mixed $productDescription
     * @param mixed $productCode
     * @param mixed $value
     * @param mixed $quantity
     * @param mixed $balance
     * @param mixed $highlightProduct
     * @param mixed $categoriesId
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $id,
        mixed $productName,
        mixed $productDescription,
        mixed $productCode,
        mixed $value,
        mixed $quantity,
        mixed $balance,
        mixed $highlightProduct,
        mixed $categoriesId,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'productName'        => $productName,
            'productDescription' => $productDescription,
            'productCode'        => $productCode,
            'value'              => $value,
            'quantity'           => $quantity,
            'balance'            => $balance,
            'highlightProduct'   => $highlightProduct,
            'categoriesId'       => $categoriesId,
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
        $productCode = strtoupper(RandomStringHelper::alphaGenerate(2).RandomStringHelper::numericGenerate(5));

        return [
            'Invalid id param' => [
                'id'                 => 'invalid-uuid',
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product name param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => '',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product description param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => ['test'],
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product code param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => '',
                'value'              => 50,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product value param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => '',
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product value param case 1' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => -23,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product value param case 2' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 10.236,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product quantity param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => null,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product quantity param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => -11,
                'balance'            => -13,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty balance param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 2,
                'balance'            => null,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Balance greater than the amount' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 2,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid highlight product param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => 'false',
                'categoriesId'       => [],
            ],

            'Invalid category id param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate(), 'invalid-uuid'],
            ],
        ];
    }
}
