<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Features\City\Cities\Models\City;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Helpers\RandomStringHelper;
use Ramsey\Uuid\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesDataProviders;

class UpdateChurchTest extends BaseTestCase
{
    use ChurchesDataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer(Credentials::ADMIN_MASTER);
    }

    public function test_should_update_unique_church()
    {
        $church = Church::factory()->create();

        $city = City::where(City::DESCRIPTION, 'Novo Hamburgo')->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "phone" => "51999999999",
            "email" => $name."@gmail.com",
            "youtube" => "",
            "facebook" => "",
            "instagram" => "",
            "zipCode" => "93320012",
            "address" => "Av. Nações Unidas",
            "numberAddress" => "2815",
            "complement" => "",
            "district" => "Rio Branco",
            "active" => true,
            "uf" => "RS",
            "cityId" => $city->id
        ];

        $response = $this->putJson(
            $this->endpoint."/id/{$church->id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertOk();
    }

    /**
     * @dataProvider formErrorsDataProvider
     *
     * @param mixed $name
     * @param mixed $responsibleMembers
     * @param mixed $phone
     * @param mixed $email
     * @param mixed $youtube
     * @param mixed $facebook
     * @param mixed $instagram
     * @param mixed $zipCode
     * @param mixed $address
     * @param mixed $numberAddress
     * @param mixed $complement
     * @param mixed $district
     * @param mixed $active
     * @param mixed $uf
     * @param mixed $cityId
     * @return void
     */
    public function test_should_return_error_if_invalid_payload(
        mixed $name,
        mixed $responsibleMembers,
        mixed $phone,
        mixed $email,
        mixed $youtube,
        mixed $facebook,
        mixed $instagram,
        mixed $zipCode,
        mixed $address,
        mixed $numberAddress,
        mixed $complement,
        mixed $district,
        mixed $active,
        mixed $uf,
        mixed $cityId,
    ): void
    {
        $payload = [
            "name"               => $name,
            "phone"              => $phone,
            "email"              => $email,
            "youtube"            => $youtube,
            "facebook"           => $facebook,
            "instagram"          => $instagram,
            "zipCode"            => $zipCode,
            "address"            => $address,
            "numberAddress"      => $numberAddress,
            "complement"         => $complement,
            "district"           => $district,
            "active"             => $active,
            "uf"                 => $uf,
            "cityId"             => $cityId,
        ];

        $id = Uuid::uuid4()->toString();

        $response = $this->putJson(
            $this->endpoint."/id/{$id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_church_id_not_exists()
    {
        $city = City::where(City::DESCRIPTION, 'Novo Hamburgo')->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "phone" => "51999999999",
            "email" => $name."@gmail.com",
            "youtube" => "",
            "facebook" => "",
            "instagram" => "",
            "zipCode" => "93320012",
            "address" => "Av. Nações Unidas",
            "numberAddress" => "2815",
            "complement" => "",
            "district" => "Rio Branco",
            "active" => true,
            "uf" => "RS",
            "cityId" => $city->id
        ];

        $id = Uuid::uuid4()->toString();

        $response = $this->putJson(
            $this->endpoint."/id/{$id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_city_id_not_exists()
    {
        $church = Church::factory()->create();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "phone" => "51999999999",
            "email" => $name."@gmail.com",
            "youtube" => "",
            "facebook" => "",
            "instagram" => "",
            "zipCode" => "93320012",
            "address" => "Av. Nações Unidas",
            "numberAddress" => "2815",
            "complement" => "",
            "district" => "Rio Branco",
            "active" => true,
            "uf" => "RS",
            "cityId" => Uuid::uuid4()->toString()
        ];

        $id = Uuid::uuid4()->toString();

        $response = $this->putJson(
            $this->endpoint."/id/{$church->id}",
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }
}
