<?php

namespace Tests\Feature\App\Features\UniqueCodePrefixes;

use App\Shared\Helpers\RandomStringHelper;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class InsertUniqueCodePrefixTest extends BaseTestCase
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

        $payload = [
            'prefix' => strtoupper(RandomStringHelper::alphaGenerate(2)),
            'active' => true,
        ];

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertCreated();
    }
}
