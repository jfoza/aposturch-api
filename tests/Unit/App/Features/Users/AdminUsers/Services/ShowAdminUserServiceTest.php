<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\ShowAdminUserService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;

class ShowAdminUserServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->adminUsersFiltersDtoMock = $this->createMock(AdminUsersFiltersDTO::class);
    }

    public function getShowAdminUserService(): ShowAdminUserService
    {
        return new ShowAdminUserService(
            $this->adminUsersRepositoryMock,
        );
    }

    public function dataProviderShowAdminUser(): array
    {
        return [
            'By Support Rule' => [RulesEnum::ADMIN_USERS_SUPPORT_VIEW->value],
            'By Admin Master Rule' => [RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderShowAdminUser
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_return_unique_admin_user(string $rule): void
    {
        $showAdminUserService = $this->getShowAdminUserService();

        $showAdminUserService->setPolicy(new Policy([$rule]));

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(AdminUsersLists::getUniqueAdminUser());

        $adminUsers = $showAdminUserService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($adminUsers);
    }

    public function test_should_return_exception_if_the_user_tries_to_view_a_superior_profile()
    {
        $showAdminUserService = $this->getShowAdminUserService();

        $showAdminUserService->setPolicy(new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value]));

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(AdminUsersLists::getUniqueTechnicalSupportUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showAdminUserService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showAdminUserService = $this->getShowAdminUserService();

        $showAdminUserService->setPolicy(new Policy(['ABC']));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showAdminUserService->execute(Uuid::uuid4Generate());
    }
}
