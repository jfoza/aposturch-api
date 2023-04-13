<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\CreateAdminUserService;
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

class CreateAdminUserServiceTest extends TestCase
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

    public function getCreateAdminUserService(): CreateAdminUserService
    {
        return new CreateAdminUserService(
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

    public function dataProviderInsertAdminUser(): array
    {
        return [
            'By Admin Master Rule' => [
                RulesEnum::ADMIN_USERS_ADMIN_MASTER_INSERT->value,
                ProfilesLists::getAdminMasterProfile()
            ],
            'By Admin Church Rule' => [
                RulesEnum::ADMIN_USERS_ADMIN_CHURCH_INSERT->value,
                ProfilesLists::getAdminChurchProfile()
            ],
            'By Admin Module Rule' => [
                RulesEnum::ADMIN_USERS_ADMIN_MODULE_INSERT->value,
                ProfilesLists::getAdminModuleProfile()
            ],
            'By Assistant Rule'    => [
                RulesEnum::ADMIN_USERS_ASSISTANT_INSERT->value,
                ProfilesLists::getAssistantProfile()
            ],
        ];
    }

    /**
     * @dataProvider dataProviderInsertAdminUser
     *
     * @param string $rule
     * @param mixed $profile
     * @return void
     * @throws AppException
     */
    public function test_should_create_new_admin_user(
        string $rule,
        mixed $profile
    ): void
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([$rule]);

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

        $createAdminUserService = $this->getCreateAdminUserService();

        $adminUserCreated = $createAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );

        $this->assertInstanceOf(AdminUserResponse::class, $adminUserCreated);
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_INSERT->value
        ]);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $createAdminUserService = $this->getCreateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_profile_not_exists()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_INSERT->value
        ]);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $createAdminUserService = $this->getCreateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }

    public function test_should_return_exception_if_the_user_tries_to_register_a_superior_profile()
    {
        $profileId = Uuid::uuid4()->toString();

        $this->populateUsersDTO($profileId);

        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MODULE_INSERT->value
        ]);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminMasterProfile($profileId));

        $createAdminUserService = $this->getCreateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createAdminUserService->execute(
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

        $createAdminUserService = $this->getCreateAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createAdminUserService->execute(
            $this->userDtoMock,
            $policy
        );
    }
}
