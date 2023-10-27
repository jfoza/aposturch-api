<?php

namespace Tests\Feature\App\Modules\Store\Categories;

use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class RemoveCategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_CATEGORIES_ROUTE;
    }

    public function test_should_remove_unique_category()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$category->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_return_error_if_category_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$category",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_has_products()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $product = Product::factory()->create();

        Category::find($category->id)->product()->sync([$product->id]);

        $response = $this->deleteJson(
            "$this->endpoint/id/$category->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $category = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$category",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $category = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$category",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
