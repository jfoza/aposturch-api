<?php

namespace Tests\Feature\App\Modules\Store\Categories;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindAllCategoriesTest extends BaseTestCase
{
    private int $page;
    private int $perPage;
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = 1;
        $this->perPage = 1;

        $this->endpoint = self::STORE_CATEGORIES_ROUTE;
    }

    public function getCategoryAssertion(): array
    {
        return [
            'id',
            'name',
            'description',
            'active',
            'created_at',
        ];
    }

    public function test_should_return_empty()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_list_of_categories_without_pagination()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->getCategoryAssertion()]);
    }

    public function test_should_return_categories_list_with_pagination_and_order()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'columnName' => 'name',
            'columnOrder' => 'asc'
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonCount($this->perPage, 'data');
        $response->assertJsonFragment(['current_page' => $this->page]);

        $response->assertJsonStructure([
            'data' => [$this->getCategoryAssertion()]
        ]);
    }

    public function test_should_return_categories_list_with_pagination_order_and_filters()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'             => $this->page,
            'perPage'          => $this->perPage,
            'columnName'       => 'name',
            'columnOrder'      => 'asc',
            'name'             => 'test',
            'active'           => 1,
            'hasSubcategories' => 0,
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_user_does_not_have_access_to_module()
    {
        $this->setAuthorizationBearer(Credentials::USER_WITHOUT_MODULES);

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertForbidden();
    }
}
