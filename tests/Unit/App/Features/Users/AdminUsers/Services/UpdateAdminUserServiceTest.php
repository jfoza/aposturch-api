<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\UpdateAdminUserService;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Infra\Repositories\ProfilesRepository;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Infra\Repositories\UsersRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ProfilesLists;
use Tests\Unit\App\Resources\UsersLists;

class UpdateAdminUserServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|UsersRepositoryInterface      $usersRepositoryMock;
    private MockObject|ProfilesRepositoryInterface   $profilesRepositoryMock;
    private MockObject|AdminUserResponse             $adminUserResponseMock;
    private MockObject|UserDTO                       $userDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->usersRepositoryMock      = $this->createMock(UsersRepository::class);
        $this->profilesRepositoryMock   = $this->createMock(ProfilesRepository::class);
        $this->adminUserResponseMock    = $this->createMock(AdminUserResponse::class);
        $this->userDtoMock              = $this->createMock(UserDTO::class);
    }

    public function getUpdateAdminUserService(): UpdateAdminUserService
    {
        return new UpdateAdminUserService(
            $this->adminUsersRepositoryMock,
            $this->usersRepositoryMock,
            $this->profilesRepositoryMock,
            $this->adminUserResponseMock,
        );
    }

    public function populateUsersDTO(string $profileId)
    {
        $this->userDtoMock->newPasswordGenerationsDTO = $this->createMock(NewPasswordGenerationsDTO::class);

        $this->userDtoMock->id        = Uuid::uuid4()->toString();
        $this->userDtoMock->name      = 'User Name';
        $this->userDtoMock->email     = 'email.example@email.com';
        $this->userDtoMock->password  = 'user_password';
        $this->userDtoMock->active    = true;
        $this->userDtoMock->profileId = $profileId;
    }

    public function dataProviderUpdateAdminUser(): array
    {
        return [
            'By Admin Master Rule'  => [
                RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value,
                ProfilesLists::getAdminMasterProfile()
            ],
            'By Admin Church Rule'  => [
                RulesEnum::ADMIN_USERS_ADMIN_CHURCH_UPDATE->value,
                ProfilesLists::getAdminChurchProfile()
            ],
            'By Admin Module Rule' => [
                RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value,
                ProfilesLists::getAdminModuleProfile()
            ],
            'By Assistant Rule'    => [
                RulesEnum::ADMIN_USERS_ASSISTANT_UPDATE->value,
                ProfilesLists::getAssistantProfile()
            ],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateAdminUser
     *
     * @param string $rule
     * @param mixed $profile
     * @return void
     * @throws AppException
     */
    public function test_should_update_admin_user(
        string $rule,
        mixed $profile
    ): void
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([$rule]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UsersLists::showUser());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn($profile);

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $adminUser = $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );

        $this->assertInstanceOf(AdminUserResponse::class, $adminUser);
    }

    public function test_should_should_return_exception_if_admin_user_not_exists()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UsersLists::showUser());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_profile_not_exists()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UsersLists::showUser());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_the_user_tries_to_register_a_superior_profile()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UsersLists::showUser());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminMasterProfile($profileId));

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            'RULE_NOT_EXISTS'
        ]);

        $updateAdminUserService = $this->getUpdateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }
}
