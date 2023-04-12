<?php

namespace Tests\Unit\App\Features\Auth\Business;

use App\Exceptions\AppException;
use App\Features\Auth\Business\SessionsAdminUserBusiness;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Http\Resources\AdminAuthResource;
use App\Features\Auth\Http\Responses\Admin\AdminAuthResponse;
use App\Features\Auth\Http\Responses\Admin\AdminUserResponse;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Infra\Repositories\SessionsRepository;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\AdminUsersLists;
use Tests\Unit\App\Resources\AuthLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class SessionsAdminUserBusinessTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|SessionsRepositoryInterface   $sessionsRepositoryMock;
    private MockObject|RulesRepositoryInterface      $rulesRepositoryMock;
    private MockObject|SessionsDTO                   $sessionsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        JWTAuth::shouldReceive('tokenById')->andreturn(AuthLists::accessToken());
        JWTAuth::shouldReceive('getTTL')->andreturn(AuthLists::getTTL());

        Auth::shouldReceive('tokenById')->andreturn(AuthLists::accessToken());
        Auth::shouldReceive('getTTL')->andreturn(AuthLists::getTTL());

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->sessionsRepositoryMock   = $this->createMock(SessionsRepository::class);
        $this->rulesRepositoryMock      = $this->createMock(RulesRepository::class);
        $this->sessionsDtoMock          = $this->createMock(SessionsDTO::class);
    }

    public function sessionsAdminUserBusinessInstance(): SessionsAdminUserBusiness
    {
        $authResponseMock = $this->createMock(AdminAuthResponse::class);

        $authResponseMock->user = $this->createMock(AdminUserResponse::class);

        $authResourceMock =  new AdminAuthResource(
            $authResponseMock
        );

        return new SessionsAdminUserBusiness(
            $this->adminUsersRepositoryMock,
            $this->rulesRepositoryMock,
            $this->sessionsRepositoryMock,
            $authResourceMock,
        );
    }

    public function populateSessionsDTO()
    {
        $this->sessionsDtoMock->email = 'usuario@email.com';
        $this->sessionsDtoMock->password = 'Teste123';
        $this->sessionsDtoMock->ipAddress = '172.19.0.4';
    }

    public function test_should_authenticate_user_by_returning_jwt_token()
    {
        $this->populateSessionsDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findByEmail')
            ->willReturn(AdminUsersLists::getAdminUserByEmail());

        $this
            ->rulesRepositoryMock
            ->method('findAllByUserIdAndModulesId')
            ->willReturn(AdminUsersLists::getRules());

        $sessionsAdminUserBusiness = $this->sessionsAdminUserBusinessInstance();

        $auth = $sessionsAdminUserBusiness->login($this->sessionsDtoMock);

        $this->assertInstanceOf(AdminAuthResponse::class, $auth);
        $this->assertEquals(AuthLists::accessToken(), $auth->accessToken);
        $this->assertEquals(AuthLists::getTTL(), $auth->expiresIn);
        $this->assertEquals(AuthLists::tokenType(), $auth->tokenType);
    }

    public function test_should_return_exception_if_user_not_exists()
    {
        $this->populateSessionsDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $sessionsAdminUserBusiness = $this->sessionsAdminUserBusinessInstance();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);
        $sessionsAdminUserBusiness->login($this->sessionsDtoMock);
    }

    public function test_should_return_exception_if_passwords_not_match()
    {
        $this->populateSessionsDTO();
        $this->sessionsDtoMock->password = 'password-not-exists';

        $this
            ->adminUsersRepositoryMock
            ->method('findByEmail')
            ->willReturn(AdminUsersLists::getAdminUserByEmail());

        $sessionsAdminUserBusiness = $this->sessionsAdminUserBusinessInstance();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);
        $sessionsAdminUserBusiness->login($this->sessionsDtoMock);
    }

    public function test_should_return_exception_if_user_is_inactive()
    {
        $this->populateSessionsDTO();

        $this
            ->adminUsersRepositoryMock
            ->method('findByEmail')
            ->willReturn(AdminUsersLists::getAdminUserByEmail(false));

        $sessionsAdminUserBusiness = $this->sessionsAdminUserBusinessInstance();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);
        $sessionsAdminUserBusiness->login($this->sessionsDtoMock);
    }
}
