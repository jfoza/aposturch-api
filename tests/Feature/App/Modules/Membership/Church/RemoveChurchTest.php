<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Features\Products\Products\Infra\Models\Product;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Models\Member;
use Ramsey\Uuid\Uuid;
use Tests\Feature\BaseTestCase;

class RemoveChurchTest extends BaseTestCase
{
    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer();
    }

    public function test_should_remove_a_unique_church()
    {
        $church = Church::factory()->create();

        $response = $this->deleteJson(
            $this->endpoint."/{$church->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_remove_a_unique_church_with_image()
    {
        $church = Church::factory()->create();

        $type = TypeUploadImageEnum::CHURCH->value;

        $image = Image::factory()->create([
            Image::TYPE => $type,
            Image::PATH => $type."/test.png",
        ]);

        Church::find($church->id)->imagesChurch()->sync([$image->id]);

        $response = $this->deleteJson(
            $this->endpoint."/{$church->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNoContent();
    }

    public function test_should_error_if_church_id_not_exists()
    {
        $id = Uuid::uuid4()->toString();

        $response = $this->deleteJson(
            $this->endpoint."/{$id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_church_has_members()
    {
        $church = Church::whereRelation('member', Member::tableField(Member::ID), '!=', null)->first();

        $response = $this->deleteJson(
            $this->endpoint."/{$church->id}",
            [],
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }
}
