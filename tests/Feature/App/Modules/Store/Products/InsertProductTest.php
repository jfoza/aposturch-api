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
            'productName'        => RandomStringHelper::alnumGenerate(),
            'productDescription' => RandomStringHelper::alnumGenerate(),
            'productCode'        => $productCode,
            'value'              => 100.25,
            'quantity'           => 10,
            'highlightProduct'   => false,
            'categoriesId'       => [],
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
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product description param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => ['test'],
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product code param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => '',
                'value'              => 10,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product value param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => '',
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product value param case 1' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => -23,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product value param case 2' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => 10.236,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Empty product quantity param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => null,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid product quantity param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => RandomStringHelper::alnumGenerate(),
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => -2,
                'highlightProduct'   => false,
                'categoriesId'       => [],
            ],

            'Invalid highlight product param' => [
                'productName'        => RandomStringHelper::alnumGenerate(),
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => 'false',
                'categoriesId'    => [],
            ],

            'Invalid category id param' => [
                'productName'        => '',
                'productDescription' => '',
                'productCode'        => $productCode,
                'value'              => 100.25,
                'quantity'           => 10,
                'highlightProduct'   => false,
                'categoriesId'       => [Uuid::uuid4Generate(), 'invalid-uuid'],
            ],
        ];
    }
}
