<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\UpdateStatusUserService;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;

class UpdateStatusUserServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;

    private string $userId;
    private string $defaultChurchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);

        $this->userId = Uuid::uuid4Generate();
        $this->defaultChurchId = Uuid::uuid4Generate();
    }

    public function getUpdateStatusUserService(): UpdateStatusUserService
    {
        return new UpdateStatusUserService(
            $this->usersRepositoryMock,
            $this->membersRepositoryMock,
        );
    }

    public function dataProviderAdminProfilesProfilesValidations(): array
    {
        return [
            'From Admin Master to Technical Support' => [
                RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value,
                ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value
            ],
            'From Technical Support to Technical Support' => [
                RulesEnum::USERS_TECHNICAL_SUPPORT_UPDATE_STATUS->value,
                ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value
            ],
            'From Admin Master to Admin Master' => [
                RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value,
                ProfileUniqueNameEnum::ADMIN_MASTER->value
            ],
        ];
    }

    public function dataProviderMemberProfilesProfilesValidations(): array
    {
        return [
            'From Admin Church to Admin Church' => [
                RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value
            ],
        ];
    }

    public function test_should_update_status_by_technical_support()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->defaultChurchId));

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_TECHNICAL_SUPPORT_UPDATE_STATUS->value
        ]));

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser($this->userId, ProfileUniqueNameEnum::ASSISTANT->value));

        $result = $updateStatusUserService->execute($this->userId);

        $this->assertIsArray($result);
    }

    public function test_should_update_status_by_admin_master()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->defaultChurchId));

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value
        ]));

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser($this->userId, ProfileUniqueNameEnum::ASSISTANT->value));

        $result = $updateStatusUserService->execute($this->userId);

        $this->assertIsArray($result);
    }

    public function test_should_update_status_by_admin_church()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->defaultChurchId));

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value
        ]));

        $church = Collection::make([
            (object)([
                Church::ID          => $this->defaultChurchId,
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
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $result = $updateStatusUserService->execute(Uuid::uuid4Generate());

        $this->assertIsArray($result);
    }

    /**
     * @dataProvider dataProviderAdminProfilesProfilesValidations
     *
     * @param string $rule
     * @param string $profile
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_admins(
        string $rule,
        string $profile,
    ): void
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->defaultChurchId));

        $updateStatusUserService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser($this->userId, $profile));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $updateStatusUserService->execute($this->userId);
    }

    /**
     * @dataProvider dataProviderMemberProfilesProfilesValidations
     *
     * @param string $rule
     * @param string $profile
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $rule,
        string $profile,
    ): void
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->defaultChurchId));

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value
        ]));

        $church = Collection::make([
            (object)([
                Church::ID          => $this->defaultChurchId,
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
                    $profile
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $updateStatusUserService->execute($this->userId);
    }

    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged()
        );

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value
        ]));

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
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH));

        $updateStatusUserService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusUserService->execute(Uuid::uuid4Generate());
    }
}
