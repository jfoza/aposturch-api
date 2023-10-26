<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindBySubcategoryIdTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function getSubcategoryAssertion(): array
    {
        return [
            'id',
            'category_id',
            'name',
            'description',
            'active',
            'created_at',
            'category',
        ];
    }

    public function test_should_return_unique_subcategory()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Subcategory::factory()->create();

        $response = $this->getJson(
            "$this->endpoint/id/$subcategory->id",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure($this->getSubcategoryAssertion());
    }

    public function test_should_return_error_if_subcategory_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategoryId = Uuid::uuid4Generate();

        $response = $this->getJson(
            "$this->endpoint/id/$subcategoryId",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_subcategory_id_has_an_invalid_format()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategoryId = 'invalid-uuid';

        $response = $this->getJson(
            "$this->endpoint/id/$subcategoryId",
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $subcategory = Subcategory::factory()->create();

        $response = $this->getJson(
            "$this->endpoint/id/$subcategory->id",
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
