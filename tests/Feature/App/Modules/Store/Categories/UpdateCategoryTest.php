<?php

namespace Tests\Feature\App\Modules\Store\Categories;

use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Facades\DB;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateCategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_CATEGORIES_ROUTE;
    }

    public function test_should_update_unique_category()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $payload = [
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_update_unique_category_with_subcategories()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $subcategory1 = Subcategory::factory()->create();
        $subcategory2 = Subcategory::factory()->create();

        $subcategoriesIdPayload = [
            $subcategory1->id,
            $subcategory2->id,
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category->id",
            [
                'name'            => RandomStringHelper::alnumGenerate(),
                'description'     => RandomStringHelper::alnumGenerate(),
                'subcategoriesId' => $subcategoriesIdPayload
            ],
            $this->getAuthorizationBearer()
        );

        $subcategories = DB::table(Subcategory::tableName())
            ->whereIn(Subcategory::ID, $subcategoriesIdPayload)
            ->get();

        foreach ($subcategories as $subcategory)
        {
            $response->assertJsonFragment(['id' => $subcategory->category_id]);
        }

        $response->assertOk();
    }

    public function test_should_return_error_if_any_of_the_subcategories_are_not_found()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $subcategory1 = Subcategory::factory()->create();

        $subcategoriesIdPayload = [
            $subcategory1->id,
            Uuid::uuid4Generate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category->id",
            [
                'name'            => RandomStringHelper::alnumGenerate(),
                'description'     => RandomStringHelper::alnumGenerate(),
                'subcategoriesId' => $subcategoriesIdPayload
            ],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Uuid::uuid4Generate();

        $payload = [
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $payload = [
            'name'        => $category1->name,
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category2->id",
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
            'name'        => RandomStringHelper::alnumGenerate(),
            'description' => RandomStringHelper::alnumGenerate(),
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$category->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
