<?php

namespace Tests\Feature\App\Features\UniqueCodePrefixes;

use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class UpdateUniqueCodePrefixTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::UNIQUE_CODE_PREFIXES;
    }

    public function test_should_create_new_unique_code_prefix()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = UniqueCodePrefix::factory()->create();

        $payload = [
            'prefix' => strtoupper(RandomStringHelper::alphaGenerate(2)),
            'active' => true,
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$uniqueCodePrefix->id",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    public function test_should_return_error_if_unique_code_prefix_not_exists()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = Uuid::uuid4Generate();

        $payload = [
            'prefix' => strtoupper(RandomStringHelper::alphaGenerate(2)),
            'active' => true,
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$uniqueCodePrefix",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_id_has_an_invalid_format()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        $uniqueCodePrefix = 'invalid-uuid';

        $payload = [
            'prefix' => strtoupper(RandomStringHelper::alphaGenerate(2)),
            'active' => true,
        ];

        $response = $this->putJson(
            "$this->endpoint/id/$uniqueCodePrefix",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }
}
