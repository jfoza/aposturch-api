<?php

namespace Tests\Feature\App\Modules\Membership\Church;

use App\Features\City\Cities\Infra\Models\City;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\MemberTypes\Models\MemberType;
use App\Shared\Enums\MemberTypesEnum;
use App\Shared\Helpers\RandomStringHelper;
use Ramsey\Uuid\Uuid;
use Tests\Feature\BaseTestCase;
use Tests\Feature\Resources\Modules\Churches\ChurchesDataProviders;

class CreateChurchTest extends BaseTestCase
{
    use ChurchesDataProviders;

    private string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = self::CHURCHES_ROUTE;

        $this->setAuthorizationBearer();
    }

    public function test_should_create_new_church()
    {
        $member = Member::whereRelation('memberType', MemberType::UNIQUE_NAME, MemberTypesEnum::RESPONSIBLE->value)->first();

        $city = City::where(City::DESCRIPTION, 'Novo Hamburgo')->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "responsibleMembers" => [
                $member->id
            ],
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

        $response = $this->postJson(
            $this->endpoint,
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
            "responsibleMembers" => $responsibleMembers,
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

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertUnprocessable();
    }

    public function test_should_return_error_if_sending_more_than_3_responsible_members()
    {
        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "responsibleMembers" => [
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString()
            ],
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

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }

    public function test_should_return_error_if_responsible_member_not_exists()
    {
        $member = Member::whereRelation('memberType', MemberType::UNIQUE_NAME, MemberTypesEnum::RESPONSIBLE->value)->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "responsibleMembers" => [
                $member->id,
                Uuid::uuid4()->toString(),
            ],
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

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertNotFound();
    }

    public function test_should_return_error_if_type_member_is_invalid()
    {
        $member = Member::whereRelation('memberType', MemberType::UNIQUE_NAME, MemberTypesEnum::COMMON_MEMBER->value)->first();

        $name = RandomStringHelper::alnumGenerate();

        $payload = [
            "name" => $name,
            "responsibleMembers" => [
                $member->id,
            ],
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

        $response = $this->postJson(
            $this->endpoint,
            $payload,
            $this->getAuthorizationBearer()
        );

        $response->assertBadRequest();
    }
}
