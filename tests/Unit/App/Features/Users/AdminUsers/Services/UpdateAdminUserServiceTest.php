<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Services\UpdateAdminUserService;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Features\Users\Profiles\Infra\Repositories\ProfilesRepository;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Repositories\UsersRepository;
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
            $this->adminUserResponseMock,
        );
    }

    public function populateUsersDTO()
    {
        $this->userDtoMock->newPasswordGenerationsDTO = $this->createMock(NewPasswordGenerationsDTO::class);

        $this->userDtoMock->id        = Uuid::uuid4()->toString();
        $this->userDtoMock->name      = 'User Name';
        $this->userDtoMock->email     = 'email.example@email.com';
        $this->userDtoMock->password  = 'user_password';
        $this->userDtoMock->active    = true;
    }

    public function dataProviderUpdateAdminUser(): array
    {
        return [
            'By Support Rule'  => [
                RulesEnum::ADMIN_USERS_SUPPORT_UPDATE->value,
                ProfilesLists::getAdminMasterProfile()
            ],
            'By Admin Master Rule'  => [
                RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value,
                ProfilesLists::getAdminMasterProfile()
            ],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateAdminUser
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_admin_user(
        string $rule,
    ): void
    {
        $updateAdminUserService = $this->getUpdateAdminUserService();

        $updateAdminUserService->setPolicy(new Policy([$rule]));

        $this->populateUsersDTO();

        $id = Uuid::uuid4()->toString();

        $this
            ->adminUsersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser($id));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($id));

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $adminUser = $updateAdminUserService->execute(
            $this->userDtoMock,
        );

        $this->assertInstanceOf(AdminUserResponse::class, $adminUser);
    }

    public function test_should_should_return_exception_if_admin_user_not_exists()
    {
        $updateAdminUserService = $this->getUpdateAdminUserService();

        $updateAdminUserService->setPolicy(new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value
        ]));

        $this->populateUsersDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateAdminUserService->execute(
            $this->userDtoMock,
        );
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $updateAdminUserService = $this->getUpdateAdminUserService();

        $updateAdminUserService->setPolicy(new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value
        ]));

        $this->populateUsersDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser());

        $this
            ->adminUsersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $updateAdminUserService->execute(
            $this->userDtoMock,
        );
    }

    public function test_should_return_exception_if_the_user_tries_to_register_a_superior_profile()
    {
        $updateAdminUserService = $this->getUpdateAdminUserService();

        $updateAdminUserService->setPolicy(new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_UPDATE->value
        ]));

        $this->populateUsersDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateAdminUserService->execute(
            $this->userDtoMock,
        );
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateAdminUserService = $this->getUpdateAdminUserService();

        $updateAdminUserService->setPolicy(new Policy([
            'ABC'
        ]));

        $this->populateUsersDTO();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateAdminUserService->execute(
            $this->userDtoMock,
        );
    }
}
