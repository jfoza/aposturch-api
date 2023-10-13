<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Categories\Models\Category;
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
        $category    = Category::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_subcategory_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Uuid::uuid4Generate();
        $category    = Category::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
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
        $category    = Uuid::uuid4Generate();

        $payload = [
            'categoryId'  => $category,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
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
        $category     = Category::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => $subcategory1->name,
            'description' => RandomStringHelper::alnumGenerate(),
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
        $category    = Category::factory()->create();

        $payload = [
            'categoryId'  => $category->id,
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$subcategory->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
