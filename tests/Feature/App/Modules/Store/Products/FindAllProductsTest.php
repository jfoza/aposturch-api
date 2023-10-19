<?php

namespace Tests\Feature\App\Modules\Store\Products;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindAllProductsTest extends BaseTestCase
{
    private int $page;
    private int $perPage;
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = 1;
        $this->perPage = 1;

        $this->endpoint = self::STORE_PRODUCTS_ROUTE;
    }

    public function getProductAssertion(): array
    {
        return [
            'id',
            'product_name',
            'product_description',
            'product_unique_name',
            'product_code',
            'value',
            'quantity',
            'balance',
            'highlight_product',
            'active',
            'created_at',
        ];
    }

    public function test_should_return_categories_list_with_pagination_and_order()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'columnName' => 'product_name',
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
            'data' => [$this->getProductAssertion()]
        ]);
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
