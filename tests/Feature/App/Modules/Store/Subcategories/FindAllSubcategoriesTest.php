<?php

namespace Tests\Feature\App\Modules\Store\Subcategories;

use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindAllSubcategoriesTest extends BaseTestCase
{
    private int $page;
    private int $perPage;
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = 1;
        $this->perPage = 1;

        $this->endpoint = self::STORE_SUBCATEGORIES_ROUTE;
    }

    public function getSubcategoryAssertion(): array
    {
        return [
            'id',
            'department_id',
            'name',
            'description',
            'active',
            'created_at',
            'department',
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

    public function test_should_return_list_of_subcategories_without_pagination()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->getSubcategoryAssertion()]);
    }

    public function test_should_return_subcategories_list_with_pagination_and_order()
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
            'data' => [$this->getSubcategoryAssertion()]
        ]);
    }

    public function test_should_return_subcategories_list_with_pagination_order_and_filters()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'columnName' => 'name',
            'columnOrder' => 'asc',

            'name'         => 'test',
            'departmentId' => Uuid::uuid4Generate(),
            'active'       => true,
            'hasProducts'  => false,
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

    /**
     * @dataProvider dataProviderFormErrors
     *
     * @param mixed $name
     * @param mixed $departmentId
     * @param mixed $active
     * @param mixed $hasProducts
     * @return void
     */
    public function test_should_return_error_if_has_form_errors(
        mixed $name,
        mixed $departmentId,
        mixed $active,
        mixed $hasProducts,
    ): void
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'columnName' => 'name',
            'columnOrder' => 'asc',

            'name'         => $name,
            'departmentId' => $departmentId,
            'active'       => $active,
            'hasProducts'  => $hasProducts,
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
            'Invalid department id filter param' => [
                'name'         => 'test',
                'departmentId' => 'invalid-uuid',
                'active'       => true,
                'hasProducts'  => false,
            ],

            'Invalid active param' => [
                'name'         => 'test',
                'departmentId' => Uuid::uuid4Generate(),
                'active'       => 'invalid',
                'hasProducts'  => 0,
            ],

            'Invalid has products param' => [
                'name'         => 'test',
                'departmentId' => Uuid::uuid4Generate(),
                'active'       => 1,
                'hasProducts'  => 'invalid',
            ]
        ];
    }
}
