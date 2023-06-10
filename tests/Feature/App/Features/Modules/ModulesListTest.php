<?php

namespace Tests\Feature\App\Features\Modules;

use Tests\Feature\BaseTestCase;

class ModulesListTest extends BaseTestCase
{
    private string $endpoint;
    private array $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::MODULES_ROUTE;

        $this->jsonStructure = [
            'id',
            'module_description',
            'module_unique_name',
            'active',
            'created_at',
            'updated_at'
        ];

        $this->setAuthorizationBearerByAdminChurch();
    }

    public function test_should_return_auth_user_modules_list()
    {
        $response = $this->getJson(
            $this->endpoint.'/list',
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure([$this->jsonStructure]);
    }
}
