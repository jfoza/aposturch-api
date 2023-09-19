<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\UpdateStatusMemberService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;

class UpdateStatusUserServiceTest extends TestCase
{
    use MembersProvidersTrait;

    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;

    public string $userId;
    private string $churchId;
    private string $moduleId;

    private mixed $modules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);

        $this->userId = Uuid::uuid4Generate();

        $this->moduleId = Uuid::uuid4Generate();
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getUpdateStatusMemberService(): UpdateStatusMemberService
    {
        return new UpdateStatusMemberService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdateStatusMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_member_status(
        string $rule
    ): void
    {
        $churchId = Uuid::uuid4Generate();

        $updateStatusMemberService = $this->getUpdateStatusMemberService();

        $updateStatusMemberService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $churchId,
                $this->moduleId,
            )
        );

        $updateStatusMemberService->setPolicy(
            new Policy([$rule])
        );

        $church = Collection::make([
            (object)([
                Church::ID          => $churchId,
                Church::NAME        => "Igreja Teste 1",
                Church::UNIQUE_NAME => "igreja-teste-1",
                Church::PHONE       => "51999999999",
                Church::EMAIL       => "ibvcx@gmail.com",
                Church::ACTIVE      => true,
            ])
        ]);

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $church,
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $result = $updateStatusMemberService->execute($this->userId);

        $this->assertIsArray($result);
    }

    /**
     * @dataProvider dataProviderMemberProfilesValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $rule,
    ): void
    {
        $churchId = Uuid::uuid4Generate();

        $updateStatusMemberService = $this->getUpdateStatusMemberService();

        $updateStatusMemberService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $churchId,
                $this->moduleId,
            )
        );

        $updateStatusMemberService->setPolicy(
            new Policy([$rule])
        );

        $church = Collection::make([
            (object)([
                Church::ID          => $churchId,
                Church::NAME        => "Igreja Teste 1",
                Church::UNIQUE_NAME => "igreja-teste-1",
                Church::PHONE       => "51999999999",
                Church::EMAIL       => "ibvcx@gmail.com",
                Church::ACTIVE      => true,
            ])
        ]);

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $church,
                    $this->modules,
                    ProfileUniqueNameEnum::ADMIN_CHURCH->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $updateStatusMemberService->execute($this->userId);
    }

    /**
     * @dataProvider dataProviderMemberProfilesValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches(
        string $rule
    ): void
    {
        $updateStatusMemberService = $this->getUpdateStatusMemberService();

        $updateStatusMemberService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                Uuid::uuid4Generate(),
                $this->moduleId
            )
        );

        $updateStatusMemberService->setPolicy(
            new Policy([$rule])
        );

        $church = Collection::make([
            (object)([
                Church::ID          => Uuid::uuid4Generate(),
                Church::NAME        => "Igreja Teste 1",
                Church::UNIQUE_NAME => "igreja-teste-1",
                Church::PHONE       => "51999999999",
                Church::EMAIL       => "ibvcx@gmail.com",
                Church::ACTIVE      => true,
            ])
        ]);

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $church,
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS));

        $updateStatusMemberService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusMemberService = $this->getUpdateStatusMemberService();

        $updateStatusMemberService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusMemberService->execute(Uuid::uuid4Generate());
    }
}
