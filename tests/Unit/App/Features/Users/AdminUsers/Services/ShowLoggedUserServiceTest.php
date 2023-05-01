<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Features\Users\AdminUsers\Responses\LoggedUserResponse;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Users\Services\ShowLoggedUserService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowLoggedUserServiceTest extends TestCase
{
    private MockObject|RulesRepositoryInterface $rulesRepositoryMock;
    private readonly MockObject|LoggedUserResponse $loggedUserResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        JWTAuth::shouldReceive('user')->andreturn(AdminUsersLists::getAdminUserLogged());
        Auth::shouldReceive('user')->andreturn(AdminUsersLists::getAdminUserLogged());

        $this->rulesRepositoryMock    = $this->createMock(RulesRepository::class);
        $this->loggedUserResponseMock = $this->createMock(LoggedUserResponse::class);
    }

    public function getShowLoggedUserService(): ShowLoggedUserService
    {
        return new ShowLoggedUserService(
            $this->rulesRepositoryMock,
            $this->loggedUserResponseMock
        );
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
