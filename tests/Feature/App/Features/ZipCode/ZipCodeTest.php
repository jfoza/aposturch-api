<?php

namespace Tests\Feature\App\Features\ZipCode;

use Tests\Feature\BaseTestCase;

class ZipCodeTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::ZIP_CODE_ROUTE;

        $this->jsonStructure = [
            'zipCode',
            'address',
            'district',
            'city',
            'citiesByUF',
        ];
    }

    public function test_should_return_address_data_by_zip_code()
    {
        $response = $this->getJson(
            $this->endpoint.'?zipCode=93052170'
        );

        $response->assertOk();
        $response->assertJsonStructure($this->jsonStructure);
    }

    public function test_should_return_error_if_zip_code_is_invalid_or_not_exists()
    {
        $response = $this->getJson(
            $this->endpoint.'?zipCode=00000000'
        );

        $response->assertNotFound();
    }
}
