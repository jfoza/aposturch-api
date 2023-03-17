<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\ShowAdminUserService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;

class ShowAdminUserServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|AdminUsersFiltersDTO $adminUsersFiltersDtoMock;

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

    public function test_should_to_return_unique_admin_user_by_admin_user_rule()
    {
        $policy = new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserIdAndProfileUniqueName')
            ->willReturn(AdminUsersLists::getUniqueAdminUser());

        $showAdminUserService = $this->getShowAdminUserService();

        $adminUsers = $showAdminUserService->execute(
            $this->adminUsersFiltersDtoMock,
            $policy
        );

        $this->assertInstanceOf(Collection::class, $adminUsers);
    }

    public function test_should_to_return_unique_admin_user_by_employee_rule()
    {
        $policy = new Policy([
            RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value
        ]);

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserIdAndProfileUniqueName')
            ->willReturn(AdminUsersLists::getUniqueAdminUser());

        $showAdminUserService = $this->getShowAdminUserService();

        $adminUsers = $showAdminUserService->execute(
            $this->adminUsersFiltersDtoMock,
            $policy
        );

        $this->assertInstanceOf(Collection::class, $adminUsers);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $policy = new Policy([
            'RULE_NOT_EXISTS'
        ]);

        $showAdminUserService = $this->getShowAdminUserService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showAdminUserService->execute(
            $this->adminUsersFiltersDtoMock,
            $policy
        );
    }
}
