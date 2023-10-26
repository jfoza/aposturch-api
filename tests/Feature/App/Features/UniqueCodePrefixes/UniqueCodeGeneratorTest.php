<?php

namespace Tests\Feature\App\Features\UniqueCodePrefixes;

use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UniqueCodeGeneratorTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::UNIQUE_CODE_GENERATOR;
    }

    public function test_should_generate_new_unique_product_code()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            "$this->endpoint?uniqueCodeType=products",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(['code']);
    }

    public function test_should_return_error_if_unique_code_type_is_not_submitted()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            "$this->endpoint?uniqueCodeType=",
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_unique_code_type_is_invalid()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $response = $this->getJson(
            "$this->endpoint?uniqueCodeType=abcd",
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }
}
