<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class InsertSubcategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function test_should_create_new_subcategory()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $payload = [
            'categoryId' => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    public function test_should_create_new_subcategory_with_products()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $product = Product::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
            'productsId'  => [$product->id]
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    public function test_should_return_error_if_product_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $product = Product::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
            'productsId'  => [$product->id, Uuid::uuid4Generate()]
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Uuid::uuid4Generate();

        $payload = [
            'categoryId' => $category,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_subcategory_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create();

        $payload = [
            'categoryId' => $category->id,
            'name'        => $subcategory->name,
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $category = Category::factory()->create();

        $payload = [
            'categoryId' => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

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
     * @param mixed $categoryId
     * @param mixed $name
     * @param mixed $description
     * @param mixed $productsId
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $categoryId,
        mixed $name,
        mixed $description,
        mixed $productsId,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'categoryId'  => $categoryId,
            'name'        => $name,
            'description' => $description,
            'productsId'  => $productsId,
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
        return [
            'Invalid category id param' => [
                'categoryId'  => 'invalid-uuid',
                'name'        => 'test',
                'description' => RandomStringHelper::alnumGenerate(),
                'productsId'  => [],
            ],

            'Empty name param' => [
                'categoryId'  => Uuid::uuid4Generate(),
                'name'        => '',
                'description' => RandomStringHelper::alnumGenerate(),
                'productsId'  => [],
            ],

            'Invalid name param' => [
                'categoryId'  => Uuid::uuid4Generate(),
                'name'        => false,
                'description' => RandomStringHelper::alnumGenerate(),
                'productsId'  => [],
            ],

            'Invalid description param' => [
                'categoryId'  => Uuid::uuid4Generate(),
                'name'        => RandomStringHelper::alnumGenerate(),
                'description' => true,
                'productsId'  => [],
            ],

            'Invalid products id param' => [
                'categoryId'  => Uuid::uuid4Generate(),
                'name'        => 'test',
                'description' => RandomStringHelper::alnumGenerate(),
                'productsId'  => [Uuid::uuid4Generate(), 'invalid-uuid'],
            ]
        ];
    }
}
