<?php

namespace Tests\Unit\App\Features\Auth\Services;

use App\Exceptions\AppException;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Features\Auth\Services\ShowAuthUserService;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\UsersLists;

final class ShowAuthUserServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|RulesRepositoryInterface $rulesRepositoryMock;
    private MockObject|AuthUserResponse $authUserResponseMock;
    private AuthDTO $authDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock  = $this->createMock(UsersRepository::class);
        $this->rulesRepositoryMock  = $this->createMock(RulesRepository::class);
        $this->authUserResponseMock = $this->createMock(AuthUserResponse::class);

        $this->authDtoMock = $this->createMock(AuthDTO::class);

        $this->authDtoMock->email = 'test@email.com';
        $this->authDtoMock->password = 'pass';
    }

    public function getShowAuthUserService(): ShowAuthUserService
    {
        return new ShowAuthUserService(
            $this->usersRepositoryMock,
            $this->rulesRepositoryMock,
            $this->authUserResponseMock,
        );
    }

    public function test_should_return_valid_user_auth()
    {
        $showAuthUserService = $this->getShowAuthUserService();

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::getAdminUserInAuth($this->authDtoMock->password));

        $authUserResponse = $showAuthUserService->execute($this->authDtoMock);

        $this->assertInstanceOf(AuthUserResponse::class, $authUserResponse);
    }

    public function test_should_return_exception_if_user_email_not_exists()
    {
        $showAuthUserService = $this->getShowAuthUserService();

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);

        $showAuthUserService->execute($this->authDtoMock);
    }

    public function test_should_return_exception_if_passwords_not_match()
    {
        $showAuthUserService = $this->getShowAuthUserService();

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::getAdminUserInAuth('password-not-match'));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);

        $showAuthUserService->execute($this->authDtoMock);
    }

    public function test_should_return_exception_if_inactive_user()
    {
        $showAuthUserService = $this->getShowAuthUserService();

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::getAdminUserInAuth($this->authDtoMock->password, false));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_UNAUTHORIZED);

        $showAuthUserService->execute($this->authDtoMock);
    }
}
