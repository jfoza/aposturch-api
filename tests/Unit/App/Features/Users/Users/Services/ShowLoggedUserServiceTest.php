<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Features\Users\AdminUsers\Responses\LoggedUserResponse;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Users\Services\ShowLoggedUserService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;

class ShowLoggedUserServiceTest extends TestCase
{
    private MockObject|RulesRepositoryInterface $rulesRepositoryMock;
    private readonly MockObject|LoggedUserResponse $loggedUserResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rulesRepositoryMock    = $this->createMock(RulesRepository::class);
        $this->loggedUserResponseMock = $this->createMock(LoggedUserResponse::class);
    }

    public function getShowLoggedUserService(): ShowLoggedUserService
    {
        $showLoggedUserService = new ShowLoggedUserService(
            $this->rulesRepositoryMock,
            $this->loggedUserResponseMock
        );

        $showLoggedUserService->setAuthenticatedUser(AdminUsersLists::getAdminUserLogged());

        return $showLoggedUserService;
    }

    public function test_should_return_logged_user_data()
    {
        $this
            ->rulesRepositoryMock
            ->method('findAllByUserIdAndModulesId')
            ->willReturn(AdminUsersLists::getRules());

        $showLoggedUserService = $this->getShowLoggedUserService();

        $adminUserLogged = $showLoggedUserService->execute();

        $this->assertInstanceOf(LoggedUserResponse::class, $adminUserLogged);
    }
}
