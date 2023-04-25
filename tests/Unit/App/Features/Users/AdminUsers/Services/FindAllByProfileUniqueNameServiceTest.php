<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\FindAllByProfileUniqueNameService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;

class FindAllByProfileUniqueNameServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|AdminUsersFiltersDTO $adminUsersFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->adminUsersFiltersDtoMock = $this->createMock(AdminUsersFiltersDTO::class);

        $this->adminUsersFiltersDtoMock->profileUniqueName = [Uuid::uuid4()->toString()];
    }

    public function getFindAllByProfileUniqueNameService(): FindAllByProfileUniqueNameService
    {
        return new FindAllByProfileUniqueNameService(
            $this->adminUsersRepositoryMock,
        );
    }

    public function test_should_to_return_admin_users_list()
    {
        $findAllByProfileUniqueNameService = $this->getFindAllByProfileUniqueNameService();

        $findAllByProfileUniqueNameService->setPolicy(new Policy([
            RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value
        ]));

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(AdminUsersLists::getAllAdminUsers());

        $adminUsers = $findAllByProfileUniqueNameService->execute(
            $this->adminUsersFiltersDtoMock,
        );

        $this->assertInstanceOf(Collection::class, $adminUsers);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllByProfileUniqueNameService = $this->getFindAllByProfileUniqueNameService();

        $findAllByProfileUniqueNameService->setPolicy(new Policy([
            'ABC'
        ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllByProfileUniqueNameService->execute(
            $this->adminUsersFiltersDtoMock,
        );
    }
}
