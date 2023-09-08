<?php

namespace Tests\Feature\App\Features\Users\AdminUsers;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Users\Assertions;

class FindAllAdminUsersTest extends BaseTestCase
{
    private int $page;
    private int $perPage;
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = 1;
        $this->perPage = 1;

        $this->endpoint = self::ADMIN_USERS_ROUTE;

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public function test_should_return_admin_users_list()
    {
        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([Assertions::adminUserAssertion()]);
    }

    public function test_should_return_admin_users_list_with_pagination()
    {
        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonCount($this->perPage, 'data');
        $response->assertJsonFragment(['current_page' => $this->page]);

        $response->assertJsonStructure([
            'data' => [Assertions::adminUserAssertion()]
        ]);
    }

    public function test_should_return_admin_users_list_with_pagination_and_filters()
    {
        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'name'    => 'Giuseppe',
            'email'   => 'gfozza@hotmail.com',
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonCount($this->perPage, 'data');
        $response->assertJsonFragment(['current_page' => $this->page]);

        $response->assertJsonStructure([
            'data' => [Assertions::adminUserAssertion()]
        ]);
    }
}
