<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Repositories\ModulesRepository;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\ModulesDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\ModulesLists;

class ModulesDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface $membersRepositoryMock;
    protected MockObject|UsersRepositoryInterface   $usersRepositoryMock;
    protected MockObject|ModulesRepositoryInterface $modulesRepositoryMock;
    protected MockObject|UpdateMemberResponse       $updateMemberResponseMock;

    protected string $churchId;
    protected string $moduleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->usersRepositoryMock      = $this->createMock(UsersRepository::class);
        $this->modulesRepositoryMock    = $this->createMock(ModulesRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->churchId  = Uuid::uuid4Generate();
        $this->moduleId  = Uuid::uuid4Generate();
    }

    public function getModulesDataUpdateService(): ModulesDataUpdateService
    {
        return new ModulesDataUpdateService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
            $this->modulesRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_user_member_modules_data(
        string $rule
    ): void
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $modulesDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $userId = Uuid::uuid4Generate();

        $updated = $modulesDataUpdateService->execute($userId, [$this->moduleId]);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_should_return_exception_if_module_not_exists_case_1(
        string $rule
    ): void
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $modulesDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(null);

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::MODULE_NOT_FOUND));

        $modulesDataUpdateService->execute($userId, [$this->moduleId]);
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_should_return_exception_if_module_not_exists_case_2(
        string $rule
    ): void
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $modulesDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(
                ModulesLists::getModulesByIdInCreateMembers(
                    Uuid::uuid4Generate()
                )
            );

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::MODULE_NOT_FOUND));

        $modulesDataUpdateService->execute($userId, [$this->moduleId]);
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_should_return_exception_if_user_not_exists(
        string $rule
    ): void
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $modulesDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $modulesDataUpdateService->execute($userId, [$this->moduleId]);
    }

    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value])
        );

        $modulesDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(Uuid::uuid4Generate())
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => Uuid::uuid4Generate()])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH));

        $modulesDataUpdateService->execute($userId, [$this->moduleId]);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $modulesDataUpdateService = $this->getModulesDataUpdateService();

        $modulesDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $userId = Uuid::uuid4Generate();

        $modulesDataUpdateService->execute($userId, [$this->moduleId]);
    }
}
