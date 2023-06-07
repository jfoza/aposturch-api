<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\UpdateChurchService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\MemberLists;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UpdateChurchServiceTest extends TestCase
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

        $this->churchDtoMock->id     = Uuid::uuid4()->toString();
        $this->churchDtoMock->cityId = Uuid::uuid4()->toString();
        $this->churchDtoMock->name = RandomStringHelper::alnumGenerate(6);
    }

    public function getUpdateChurchService(): UpdateChurchService
    {
        $updateChurchService = new UpdateChurchService(
            $this->churchRepositoryMock,
            $this->cityRepositoryMock,
        );

        $updateChurchService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->churchDtoMock->id));

        return $updateChurchService;
    }

    public function dataProviderUpdateChurch(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE->value],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException|UserNotDefinedException
     */
    public function test_should_update_church(string $rule): void
    {
        $updateChurchService = $this->getUpdateChurchService();

        $updateChurchService->setPolicy(new Policy([$rule]));

        $memberId = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchDtoMock->id));

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
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE->value
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
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE->value
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

    public function test_should_return_exception_if_user_tries_to_update_a_church_other_than_his()
    {
        $updateChurchService = $this->getUpdateChurchService();

        $memberId = Uuid::uuid4()->toString();
        $this->churchDtoMock->id = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $updateChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch());

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

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
