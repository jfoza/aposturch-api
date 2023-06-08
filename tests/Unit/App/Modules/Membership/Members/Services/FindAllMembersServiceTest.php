<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\FindAllMembersService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\ProfilesLists;

class FindAllMembersServiceTest extends TestCase
{
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;
    private MockObject|MembersFiltersDTO $membersFiltersDtoMock;

    public string $churchId = '8c1ca2c1-5d35-4c2a-9303-3ce0cd7ee1ee';

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock  = $this->createMock(MembersRepository::class);
        $this->profilesRepositoryMock = $this->createMock(ProfilesRepository::class);
        $this->membersFiltersDtoMock  = $this->createMock(MembersFiltersDTO::class);
    }

    public function dataProviderFiltersByMembers(): array
    {
        return [
            [null, null],
            [$this->churchId, null],
            [null, Uuid::uuid4Generate()],
            [$this->churchId, Uuid::uuid4Generate()],
        ];
    }

    public function getFindAllMembersService(): FindAllMembersService
    {
        $findAllMembersService = new FindAllMembersService(
            $this->membersRepositoryMock,
            $this->profilesRepositoryMock,
        );

        $findAllMembersService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->churchId));

        return $findAllMembersService;
    }

    public function test_should_return_members_list_by_admin_master()
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW->value
            ])
        );

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $members = $findAllMembersService->execute($this->membersFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $members);
    }

    public function test_should_return_members_with_church_id_filter_list_by_admin_master()
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW->value
            ])
        );

        $this->membersFiltersDtoMock->churchIdInQueryParam = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $members = $findAllMembersService->execute($this->membersFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $members);
    }

    /**
     * @dataProvider dataProviderFiltersByMembers
     *
     * @param mixed $churchId
     * @param mixed $profileId
     * @return void
     * @throws AppException
     */
    public function test_should_return_members_list_by_members(
        string|null $churchId,
        string|null $profileId,
    ): void
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_VIEW->value
            ])
        );

        $this->membersFiltersDtoMock->churchIdInQueryParam = $churchId;
        $this->membersFiltersDtoMock->profileId = $profileId;

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminModuleProfile($profileId));

        $members = $findAllMembersService->execute($this->membersFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $members);
    }

    public function test_should_return_exception_if_the_church_used_as_a_filter_is_different_from_the_authenticated_users_church()
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_VIEW->value
            ])
        );

        $this->membersFiltersDtoMock->churchIdInQueryParam = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllMembersService->execute($this->membersFiltersDtoMock);
    }

    public function test_should_return_exception_if_profile_used_as_filter_not_exists()
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_VIEW->value
            ])
        );

        $this->membersFiltersDtoMock->profileId = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $findAllMembersService->execute($this->membersFiltersDtoMock);
    }

    public function test_should_return_exception_if_profile_used_as_filter_is_not_allowed()
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_VIEW->value
            ])
        );

        $profileId = Uuid::uuid4Generate();

        $this->membersFiltersDtoMock->profileId = $profileId;

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminMasterProfile($profileId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllMembersService->execute($this->membersFiltersDtoMock);
    }
}
