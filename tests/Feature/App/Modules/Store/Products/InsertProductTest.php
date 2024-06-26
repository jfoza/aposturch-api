<?php

namespace Tests\Feature\App\Modules\Store\Products;

use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class InsertProductTest extends BaseTestCase
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
            'highlightProduct'   => false,
            'categoriesId'       => [],
            'imageLinks'         => [
                'https://images-na.ssl-images-amazon.com/images/I/41XbfSiYscL._AC_SX184_.jpg',
                'https://images-na.ssl-images-amazon.com/images/I/41TsvI70n9L._AC_SX184_.jpg',
                'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg'
            ],
        ];
    }

    public function test_should_create_new_product()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $category = Category::factory()->create();

        $payload['categoriesId'] = [$category->id];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    public function test_should_return_error_if_product_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $product = Product::factory()->create();

        $payload['productName'] = $product->product_name;

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_product_code_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $product = Product::factory()->create();

        $payload['productCode'] = $product->product_code;

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = $this->getPayload();

        $category = Category::factory()->create();

        $payload['categoriesId'] = [$category->id, Uuid::uuid4Generate()];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $payload = $this->getPayload();

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
     * @param mixed $productName
     * @param mixed $productDescription
     * @param mixed $productCode
     * @param mixed $value
     * @param mixed $quantity
     * @param mixed $highlightProduct
     * @param mixed $categoriesId
     * @param mixed $imageLinks
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $productName,
        mixed $productDescription,
        mixed $productCode,
        mixed $value,
        mixed $quantity,
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
            'highlightProduct'   => $highlightProduct,
            'categoriesId'       => $categoriesId,
            'imageLinks'         => $imageLinks,
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
        $productCode = strtoupper(RandomStringHelper::alphaGenerate(2).RandomStringHelper::numericGenerate(5));

        return [
            'Empty product name param' => [
                'productName'        => '',
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid product description param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => ['test'],
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Empty product code param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => '',
                'value'              => 10,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Empty product value param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => '',
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid product value param case 1' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => -23,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid product value param case 2' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => 10.236,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Empty product quantity param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => null,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid product quantity param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => RandomStringHelper::stringsGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => -2,
                'highlightProduct'   => false,
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid highlight product param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => 'false',
                'categoriesId'       => [],
                'imageLinks'         => [],
            ],

            'Invalid category id param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate(), 'invalid-uuid'],
                'imageLinks'         => [],
            ],

            'Invalid image links param' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate()],
                'imageLinks'         => [
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                    'invalid-link'
                ],
            ],

            'More than three image links' => [
                'productName'        => RandomStringHelper::stringsGenerate(),
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate()],
                'imageLinks'         => [
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                    'https://images-na.ssl-images-amazon.com/images/I/51Hg0c-RYsL._AC_SX184_.jpg',
                ],
            ],
        ];
    }
}
