<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class RemoveSubcategoryTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function test_should_remove_unique_subcategory()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategory = Subcategory::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$subcategory->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_return_error_if_subcategory_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $subcategoryId = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$subcategoryId",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_user_does_not_have_access()
    {
        $this->setAuthorizationBearer(Credentials::ASSISTANT_STORE_MODULE);

        $subcategory = Subcategory::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$subcategory->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $subcategory = Subcategory::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$subcategory->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
