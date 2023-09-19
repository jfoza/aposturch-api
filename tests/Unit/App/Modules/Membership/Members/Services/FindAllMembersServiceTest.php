<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\FindAllMembersService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;

class FindAllMembersServiceTest extends TestCase
{
    use MembersProvidersTrait;

    private MockObject|MembersRepositoryInterface $membersRepositoryMock;
    private MockObject|MembersFiltersDTO $membersFiltersDtoMock;

    private string $churchId;
    private string $moduleId;

    private mixed $churches;
    private mixed $modules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock  = $this->createMock(MembersRepository::class);
        $this->profilesRepositoryMock = $this->createMock(ProfilesRepository::class);
        $this->membersFiltersDtoMock  = $this->createMock(MembersFiltersDTO::class);

        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getFindAllMembersService(): FindAllMembersService
    {
        return new FindAllMembersService(
            $this->membersRepositoryMock,
        );
    }

    /**
     * @dataProvider dataProviderListMembers
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_members_list(
        string $rule,
    ): void
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $findAllMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $members = $findAllMembersService->execute($this->membersFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $members);
    }

    /**
     * @dataProvider dataProviderListMembersValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_filter_by_church_is_invalid(
        string $rule,
    ): void
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->membersFiltersDtoMock->churchesId = [Uuid::uuid4Generate()];

        $findAllMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH));

        $findAllMembersService->execute($this->membersFiltersDtoMock);
    }

    /**
     * @dataProvider dataProviderListMembersValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_filter_by_profile_is_invalid(
        string $rule,
    ): void
    {
        $findAllMembersService = $this->getFindAllMembersService();

        $findAllMembersService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->membersFiltersDtoMock->profileId = Uuid::uuid4Generate();

        $findAllMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->membersRepositoryMock
            ->method('findAll')
            ->willReturn(MemberLists::getMembers());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $findAllMembersService->execute($this->membersFiltersDtoMock);
    }
}
