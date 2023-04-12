<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\FindAllAdminUsersService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;

class FindAllAdminUsersServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|AdminUsersFiltersDTO $adminUsersFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->adminUsersFiltersDtoMock = $this->createMock(AdminUsersFiltersDTO::class);
    }

    public function getFindAllAdminUsersService(): FindAllAdminUsersService
    {
        return new FindAllAdminUsersService(
            $this->adminUsersRepositoryMock,
        );
    }

    public function dataProviderFindAllAdminUsers(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value],
            'By Admin Module Rule' => [RulesEnum::ADMIN_USERS_ADMIN_MODULE_VIEW->value],
            'By Assistant Rule'    => [RulesEnum::ADMIN_USERS_ASSISTANT_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderFindAllAdminUsers
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_return_admin_users_list(string $rule): void
    {
        $policy = new Policy([$rule]);

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(AdminUsersLists::getAllAdminUsers());

        $findAllAdminUsersService = $this->getFindAllAdminUsersService();

        $adminUsers = $findAllAdminUsersService->execute(
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

        $findAllAdminUsersService = $this->getFindAllAdminUsersService();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllAdminUsersService->execute(
            $this->adminUsersFiltersDtoMock,
            $policy
        );
    }
}
