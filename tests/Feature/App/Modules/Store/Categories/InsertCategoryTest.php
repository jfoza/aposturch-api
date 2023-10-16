<?php

namespace Tests\Feature\App\Modules\Store\Categories;

use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Facades\DB;
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

        $payload = [
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

    public function test_should_create_new_category_with_subcategories()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory1 = Subcategory::factory()->create();
        $subcategory2 = Subcategory::factory()->create();

        $subcategoriesIdPayload = [
            $subcategory1->id,
            $subcategory2->id,
        ];

        $response = $this->postJson(
            $this->endpoint,
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

        $response->assertCreated();
    }

    public function test_should_return_error_if_any_of_the_subcategories_are_not_found()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory1 = Subcategory::factory()->create();

        $subcategoriesIdPayload = [
            $subcategory1->id,
            Uuid::uuid4Generate(),
        ];

        $response = $this->postJson(
            $this->endpoint,
            [
                'name'            => RandomStringHelper::alnumGenerate(),
                'description'     => RandomStringHelper::alnumGenerate(),
                'subcategoriesId' => $subcategoriesIdPayload
            ],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_category_name_already_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $category = Category::factory()->create();

        $payload = [
            'name'        => $category->name,
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

        $payload = [
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
}
