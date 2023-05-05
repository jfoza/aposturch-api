<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesAssertions;

class FindAllChurchesTest extends BaseTestCase
{
    private int $page;
    private int $perPage;
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = 1;
        $this->perPage = 1;

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer();
    }

    public function test_should_return_churches_list()
    {
        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([ChurchesAssertions::churchAssertion()]);
    }

    public function test_should_return_churches_list_with_pagination_and_order()
    {
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
            'data' => [ChurchesAssertions::churchAssertion()]
        ]);
    }

    public function test_should_return_churches_list_with_pagination_and_filters()
    {
        $params = http_build_query([
            'page'    => $this->page,
            'perPage' => $this->perPage,
            'name'    => 'Igreja Bíblica Viver NH',
        ]);

        $response = $this->getJson(
            $this->endpoint."?{$params}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonCount($this->perPage, 'data');
        $response->assertJsonFragment(['current_page' => $this->page]);

        $response->assertJsonStructure([
            'data' => [ChurchesAssertions::churchAssertion()]
        ]);
    }
}
