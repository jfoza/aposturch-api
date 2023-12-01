<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Features\General\Images\Enums\TypeOriginImageEnum;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesDataProviders;

class UploadChurchImageTest extends BaseTestCase
{
    use ChurchesDataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE.'/upload/image';

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public function test_must_insert_a_new_product_image()
    {
        $church = Church::factory()->create();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'churchId' => $church->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'type',
            'path'
        ]);
    }

    public function test_must_delete_current_image_and_insert_a_new_church_image()
    {
        $church = Church::factory()->create();

        $imageCreated = Image::factory()->create([
            Image::PATH => 'product/test.png',
            Image::TYPE => TypeUploadImageEnum::PRODUCT->value,
            Image::ORIGIN => TypeOriginImageEnum::UPLOAD->value,
        ]);

        Church::find($church->id)->imagesChurch()->sync([$imageCreated->id]);

        $image = UploadedFile::fake()->image('test2.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'churchId' => $church->id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'type',
            'path'
        ]);
    }

    public function test_should_return_error_if_church_not_exists()
    {
        $id = Uuid::uuid4()->toString();

        $image = UploadedFile::fake()->image('test.png');

        $server = $this->transformHeadersToServerVars(
            $this->getAuthorizationBearer()
        );

        $payload = [
            'churchId' => $id
        ];

        $response = $this->call(
            'POST',
            $this->endpoint,
            $payload,
            [],
            ['image' => $image],
            $server
        );

        $response->assertNotFound();
    }
}
