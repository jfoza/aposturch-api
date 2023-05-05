<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Infra\Repositories\CityRepository;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\CreateChurchService;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MemberTypesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\MembersLists;

class CreateChurchServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private MockObject|CityRepositoryInterface   $cityRepositoryMock;
    private MockObject|MembersRepositoryInterface  $membersRepositoryMock;

    private MockObject|ChurchDTO $churchDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock  = $this->createMock(ChurchRepository::class);
        $this->cityRepositoryMock    = $this->createMock(CityRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepositoryInterface::class);
        $this->churchDtoMock         = $this->createMock(ChurchDTO::class);

        $this->churchDtoMock->cityId = Uuid::uuid4()->toString();
        $this->churchDtoMock->name   = RandomStringHelper::alnumGenerate(6);
    }

    public function getCreateChurchService(): CreateChurchService
    {
        return new CreateChurchService(
            $this->churchRepositoryMock,
            $this->cityRepositoryMock,
            $this->membersRepositoryMock,
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
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch($memberId)
            );

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

    public function test_should_return_exception_if_the_number_of_members_is_greater_than_3()
    {
        $createChurchService = $this->getCreateChurchService();

        $this->churchDtoMock->responsibleMembers = [
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
        ];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch()
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_member_id_not_exists()
    {
        $createChurchService = $this->getCreateChurchService();

        $this->churchDtoMock->responsibleMembers = [Uuid::uuid4()->toString()];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch()
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_invalid_member_type()
    {
        $createChurchService = $this->getCreateChurchService();

        $memberId = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch(
                    $memberId,
                    MemberTypesEnum::COMMON_MEMBER->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createChurchService->execute($this->churchDtoMock);
    }

    public function test_should_return_exception_if_invalid_profile_user()
    {
        $createChurchService = $this->getCreateChurchService();

        $memberId = Uuid::uuid4()->toString();

        $this->churchDtoMock->responsibleMembers = [$memberId];

        $createChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch(
                    $memberId,
                    MemberTypesEnum::RESPONSIBLE->value,
                    ProfileUniqueNameEnum::ADMIN_MODULE->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createChurchService->execute($this->churchDtoMock);
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
            ->membersRepositoryMock
            ->method('findByIds')
            ->willReturn(
                MembersLists::getMembersInCreateChurch($memberId)
            );

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
