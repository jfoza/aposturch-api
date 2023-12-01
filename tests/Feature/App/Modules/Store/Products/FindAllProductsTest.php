<?php

namespace Tests\Feature\App\Modules\Store\Products;

use App\Shared\Libraries\Uuid;
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
            'product_value',
            'product_quantity',
            'product_balance',
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

    public function test_should_return_categories_list_with_pagination_order_and_filters()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page' => $this->page,
            'perPage' => $this->perPage,
            'columnName' => 'product_name',
            'columnOrder' => 'asc',

            'name' => 'test',
            'categoriesId' => [Uuid::uuid4Generate()],
            'code' => 10,
            'highlight' => 0,
            'active' => 1,
        ]);

        $response = $this->getJson(
            $this->endpoint . "?{$params}",
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

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $page
     * @param mixed $perPage
     * @param mixed $name
     * @param mixed $categoriesId
     * @param mixed $code
     * @param mixed $highlight
     * @param mixed $active
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $page,
        mixed $perPage,
        mixed $name,
        mixed $categoriesId,
        mixed $code,
        mixed $highlight,
        mixed $active,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'        => $page,
            'perPage'     => $perPage,
            'columnName'  => 'name',
            'columnOrder' => 'asc',

            'name'         => $name,
            'categoriesId' => $categoriesId,
            'code'         => $code,
            'highlight'    => $highlight,
            'active'       => $active,
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public static function dataProviderFormErrors(): array
    {
        return [
            'Empty page param' => [
                'page'         => null,
                'perPage'      => 10,
                'name'         => 'test',
                'categoriesId' => [Uuid::uuid4Generate()],
                'code'         => 1,
                'highlight'    => 0,
                'active'       => 1,
            ],

            'Empty per page param' => [
                'page'         => 1,
                'perPage'      => '',
                'name'         => 'test',
                'categoriesId' => [Uuid::uuid4Generate()],
                'code'         => 1,
                'highlight'    => 0,
                'active'       => 1,
            ],

            'Invalid categories id param' => [
                'page'         => 1,
                'perPage'      => 10,
                'name'         => 'test',
                'categoriesId' => [Uuid::uuid4Generate(), 'invalid-uuid'],
                'code'         => 1,
                'highlight'    => 0,
                'active'       => 1,
            ],

            'Invalid highlight param' => [
                'page'         => 1,
                'perPage'      => 10,
                'name'         => 'test',
                'categoriesId' => [Uuid::uuid4Generate()],
                'code'         => 1,
                'highlight'    => 'false',
                'active'       => 1,
            ],

            'Invalid active param' => [
                'page'         => 1,
                'perPage'      => 10,
                'name'         => 'test',
                'categoriesId' => [Uuid::uuid4Generate()],
                'code'         => 1,
                'highlight'    => 0,
                'active'       => 'true',
            ],
        ];
    }
}
