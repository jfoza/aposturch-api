<?php

namespace Tests\Feature\App\Modules\Store\Categories;

use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class InsertCategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_CATEGORIES_ROUTE;
    }

    public function test_should_create_new_category()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }

    public function test_should_create_new_category_with_products()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $product = Product::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
            'productsId'   => [$product->id]
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

        $department = Department::factory()->create();

        $product = Product::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
            'productsId'   => [$product->id, Uuid::uuid4Generate()]
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_department_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Uuid::uuid4Generate();

        $payload = [
            'departmentId' => $department,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department = Department::factory()->create();

        $category = Category::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => $category->name,
            'description'  => RandomStringHelper::alnumGenerate(),
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

        $department = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
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
     * @param mixed $departmentId
     * @param mixed $name
     * @param mixed $description
     * @param mixed $productsId
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $departmentId,
        mixed $name,
        mixed $description,
        mixed $productsId,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'departmentId' => $departmentId,
            'name'         => $name,
            'description'  => $description,
            'productsId'   => $productsId,
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
            'Invalid department id param' => [
                'departmentId' => 'invalid-uuid',
                'name'         => 'test',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Empty name param' => [
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => '',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Invalid name param' => [
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => false,
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Invalid description param' => [
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => RandomStringHelper::alnumGenerate(),
                'description'  => true,
                'productsId'   => [],
            ],

            'Invalid products id param' => [
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => 'test',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [Uuid::uuid4Generate(), 'invalid-uuid'],
            ]
        ];
    }
}
