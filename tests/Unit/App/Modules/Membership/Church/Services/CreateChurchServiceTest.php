<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\CreateChurchService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\CitiesLists;

class CreateChurchServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private MockObject|CityRepositoryInterface   $cityRepositoryMock;
    private MockObject|ChurchDTO $churchDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock  = $this->createMock(ChurchRepository::class);
        $this->cityRepositoryMock    = $this->createMock(CityRepository::class);
        $this->churchDtoMock         = $this->createMock(ChurchDTO::class);

        $this->churchDtoMock->cityId = Uuid::uuid4()->toString();
        $this->churchDtoMock->name   = RandomStringHelper::alnumGenerate(6);
    }

    public function getCreateChurchService(): CreateChurchService
    {
        return new CreateChurchService(
            $this->churchRepositoryMock,
            $this->cityRepositoryMock,
        );
    }

    public function test_should_insert_new_church()
    {
        $createChurchService = $this->getCreateChurchService();

        $memberId = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById());

        $this
            ->churchRepositoryMock
            ->method('create')
            ->willReturn(Collection::make([Church::ID => Uuid::uuid4()->toString()]));

        $created = $createChurchService->execute($this->churchDtoMock);

        $this->assertInstanceOf(Collection::class, $created);
    }

    public function test_should_return_exception_if_city_id_not_exists()
    {
        $createChurchService = $this->getCreateChurchService();

        $memberId = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createChurchService = $this->getCreateChurchService();

        $createChurchService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createChurchService->execute($this->churchDtoMock);
    }
}
