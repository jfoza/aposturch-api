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
            'productName'        => RandomStringHelper::stringsGenerate(),
            'productDescription' => RandomStringHelper::stringsGenerate(),
            'productCode'        => $productCode,
            'value'              => 100.25,
            'quantity'           => 10,
            'balance'            => 10,
            'highlightProduct'   => false,
            'categoriesId'       => [],
            'imageLinks'         => [
                'https://images-na.ssl-images-amazon.com/images/I/41XbfSiYscL._AC_SX184_.jpg',
                'https://images-na.ssl-images-amazon.com/images/I/41TsvI70n9L._AC_SX184_.jpg',
                'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg'
            ],
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
     * @param mixed $imageLinks
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
        mixed $imageLinks,
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
            'imageLinks'         => $imageLinks,
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
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
                'imageLinks'         => [],
            ],

            'Invalid image links param' => [
                'id'                 => Uuid::uuid4Generate(),
                'productName'        => 'test',
                'productDescription' => 'test',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'balance'            => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate()],
                'imageLinks'         => [
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                    'invalid-link'
                ],
            ],
        ];
    }
}
