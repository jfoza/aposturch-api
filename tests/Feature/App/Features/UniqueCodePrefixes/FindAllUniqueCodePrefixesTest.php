<?php

namespace Tests\Feature\App\Features\UniqueCodePrefixes;

use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;

class FindAllUniqueCodePrefixesTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::UNIQUE_CODE_PREFIXES;
    }

    public function getAssertion(): array
    {
        return [
            'id',
            'prefix',
            'active',
            'created_at',
        ];
    }

    public function test_should_return_list_of_unique_code_prefixes()
    {
        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);

        UniqueCodePrefix::factory()->create();

        $response = $this->getJson(
            $this->endpoint,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->getAssertion()]);
    }
}
