<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Modules\Membership\Church\Models\Church;
use App\Shared\Helpers\RandomStringHelper;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesAssertions;

class ShowByChurchUniqueNameTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public function test_should_return_unique_church()
    {
        $church = Church::first();

        $response = $this->getJson(
            $this->endpoint."/unique-name/{$church->unique_name}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(ChurchesAssertions::churchByIdAssertion());
        $response->assertJsonFragment(['unique_name' => $church->unique_name]);
    }

    public function test_should_return_error_id_church_id_not_exists()
    {
        $uniqueName = RandomStringHelper::alnumGenerate();

        $response = $this->getJson(
            $this->endpoint."/unique-name/{$uniqueName}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
