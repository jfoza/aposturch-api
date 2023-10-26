<?php

namespace Tests\Feature\App\Features\UniqueCodePrefixes;

use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class DeleteUniqueCodePrefixTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::UNIQUE_CODE_PREFIXES;
    }

    public function test_should_remove_unique_code_prefix()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = UniqueCodePrefix::factory()->create();

        $response = $this->deleteJson(
            "$this->endpoint/id/$uniqueCodePrefix->id",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_return_error_if_unique_code_prefix_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = Uuid::uuid4Generate();

        $response = $this->deleteJson(
            "$this->endpoint/id/$uniqueCodePrefix",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_id_has_an_invalid_format()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = 'invalid-uuid';

        $response = $this->deleteJson(
            "$this->endpoint/id/$uniqueCodePrefix",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }
}
