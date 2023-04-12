<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Infra\Repositories\CityRepository;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\UpdateChurchService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\CitiesLists;

class UpdateChurchServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private MockObject|CityRepositoryInterface   $cityRepositoryMock;

    private MockObject|ChurchDTO $churchDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
        $this->cityRepositoryMock   = $this->createMock(CityRepository::class);

        $this->churchDtoMock = $this->createMock(ChurchDTO::class);

        $this->churchDtoMock->id     = Uuid::uuid4()->toString();
        $this->churchDtoMock->cityId = Uuid::uuid4()->toString();
    }

    public function getUpdateChurchService(): UpdateChurchService
    {
        return new UpdateChurchService(
            $this->churchRepositoryMock,
            $this->cityRepositoryMock
        );
    }

    public function test_should_update_church()
    {
        $updateChurchService = $this->getUpdateChurchService();

        $updateChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_UPDATE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch());

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById());

        $updated = $updateChurchService->execute($this->churchDtoMock);

        $this->assertInstanceOf(Church::class, $updated);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $updateChurchService = $this->getUpdateChurchService();

        $updateChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_UPDATE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_city_id_not_exists()
    {
        $updateChurchService = $this->getUpdateChurchService();

        $updateChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_UPDATE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch());

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateChurchService = $this->getUpdateChurchService();

        $updateChurchService->setPolicy(new Policy([
            'ABC'
        ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateChurchService->execute($this->churchDtoMock);
    }
}
