<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Modules\Membership\Church\Models\Church;
use Ramsey\Uuid\Uuid;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesAssertions;

class ShowByChurchIdTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer();
    }

    public function test_should_return_unique_church()
    {
        $church = Church::first();

        $response = $this->getJson(
            $this->endpoint."/id/{$church->id}",
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
        $response->assertJsonStructure(ChurchesAssertions::churchByIdAssertion());
        $response->assertJsonFragment(['id' => $church->id]);
    }

    public function test_should_return_error_id_church_id_not_exists()
    {
        $id = Uuid::uuid4()->toString();

        $response = $this->getJson(
            $this->endpoint."/id/{$id}",
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
