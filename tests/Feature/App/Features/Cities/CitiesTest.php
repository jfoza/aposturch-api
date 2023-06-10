<?php

namespace Tests\Feature\App\Features\Cities;

use App\Features\City\Cities\Models\City;
use App\Shared\Libraries\Uuid;
use Tests\Feature\BaseTestCase;

class CitiesTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CITIES_ROUTE;

        $this->jsonStructure = ['id', 'description', 'uf'];
    }

    public function test_should_return_cities_list_by_uf()
    {
        $response = $this->getJson(
            $this->endpoint.'/uf/RS',
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }

    public function test_should_return_error_if_uf_is_invalid()
    {
        $response = $this->getJson(
            $this->endpoint.'/uf/ABC',
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_cities_list_by_id()
    {
        $city = City::where(City::DESCRIPTION, 'Novo Hamburgo')->first();

        $response = $this->getJson(
            $this->endpoint."/id/{$city->id}",
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }

    public function test_should_return_error_if_city_id_not_exists()
    {
        $city = Uuid::uuid4Generate();

        $response = $this->getJson(
            $this->endpoint."/id/{$city}",
        );

        $response->assertNotFound();
    }

    public function test_should_return_cities_list_in_persons()
    {
        $response = $this->getJson(
            $this->endpoint.'/in-persons',
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }

    public function test_should_return_cities_list_in_churches()
    {
        $response = $this->getJson(
            $this->endpoint.'/in-churches',
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }
}
