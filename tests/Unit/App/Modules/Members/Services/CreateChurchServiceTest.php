<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Http\Pagination\PaginationOrder;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Infra\Repositories\CityRepository;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\CreateChurchService;
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
    private MockObject|AdminUsersRepositoryInterface  $adminUsersRepositoryMock;

    private MockObject|ChurchDTO $churchDtoMock;

    private string $responsibleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock     = $this->createMock(ChurchRepository::class);
        $this->cityRepositoryMock       = $this->createMock(CityRepository::class);
        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);

        $this->responsibleId = Uuid::uuid4()->toString();

        $adminUsersFiltersDtoMock = $this->createMock(AdminUsersFiltersDTO::class);

        $adminUsersFiltersDtoMock->adminsId = [$this->responsibleId];

        $adminUsersFiltersDtoMock->paginationOrder = $this->createMock(PaginationOrder::class);

        $this->churchDtoMock = $this->createMock(ChurchDTO::class);

        $this->churchDtoMock->adminUsersFiltersDTO = $adminUsersFiltersDtoMock;

        $this->churchDtoMock->cityId = Uuid::uuid4()->toString();
        $this->churchDtoMock->name = RandomStringHelper::alnumGenerate(6);
    }

    public function getCreateChurchService(): CreateChurchService
    {
        return new CreateChurchService(
            $this->churchRepositoryMock,
            $this->cityRepositoryMock,
            $this->adminUsersRepositoryMock
        );
    }

    public function test_should_insert_new_church()
    {
        $createChurchService = $this->getCreateChurchService();

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make([
                ['admin_user_id' => $this->responsibleId],
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

    public function test_should_return_exception_if_responsible_id_not_exists()
    {
        $createChurchService = $this->getCreateChurchService();

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make([
                ['admin_user_id' => Uuid::uuid4()->toString()],
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_city_id_not_exists()
    {
        $createChurchService = $this->getCreateChurchService();

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make([
                ['admin_user_id' => $this->responsibleId],
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
