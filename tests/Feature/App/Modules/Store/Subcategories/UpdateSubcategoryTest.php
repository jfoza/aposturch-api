<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateSubcategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function test_should_update_unique_subcategory()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Subcategory::factory()->create();
        $department  = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_update_unique_subcategory_with_products()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department    = Department::factory()->create();
        $subcategory = Subcategory::factory()->create();
        $product     = Product::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
            'productsId'   => [$product->id],
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_product_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $department  = Department::factory()->create();
        $subcategory = Subcategory::factory()->create();
        $product     = Product::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
            'productsId'   => [$product->id, Uuid::uuid4Generate()],
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_subcategory_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Uuid::uuid4Generate();
        $department  = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Subcategory::factory()->create();
        $department  = Uuid::uuid4Generate();

        $payload = [
            'departmentId' => $department,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_subcategory_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory1 = Subcategory::factory()->create();
        $subcategory2 = Subcategory::factory()->create();
        $department   = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => $subcategory1->name,
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory2->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $subcategory = Subcategory::factory()->create();
        $department  = Department::factory()->create();

        $payload = [
            'departmentId' => $department->id,
            'name'         => RandomStringHelper::alnumGenerate(),
            'description'  => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $id
     * @param mixed $categoryId
     * @param mixed $name
     * @param mixed $description
     * @param mixed $productsId
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $id,
        mixed $categoryId,
        mixed $name,
        mixed $description,
        mixed $productsId,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $payload = [
            'departmentId' => $categoryId,
            'name'         => $name,
            'description'  => $description,
            'productsId'   => $productsId,
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
        return [
            'Invalid id param' => [
                'id'           => 'invalid-uuid',
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => 'test',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Invalid department id param' => [
                'id'           => Uuid::uuid4Generate(),
                'departmentId' => 'invalid-uuid',
                'name'         => 'test',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Empty name param' => [
                'id'           => Uuid::uuid4Generate(),
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => '',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Invalid name param' => [
                'id'           => Uuid::uuid4Generate(),
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => false,
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [],
            ],

            'Invalid description param' => [
                'id'           => Uuid::uuid4Generate(),
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => RandomStringHelper::alnumGenerate(),
                'description'  => true,
                'productsId'   => [],
            ],

            'Invalid products id param' => [
                'id'           => Uuid::uuid4Generate(),
                'departmentId' => Uuid::uuid4Generate(),
                'name'         => 'test',
                'description'  => RandomStringHelper::alnumGenerate(),
                'productsId'   => [Uuid::uuid4Generate(), 'invalid-uuid'],
            ]
        ];
    }
}
