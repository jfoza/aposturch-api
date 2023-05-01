<?php

namespace Tests\Unit\App\Features\Auth\Services;

use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Services\AuthGenerateService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\App\Resources\AuthLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthGenerateServiceTest extends TestCase
{
    private MockObject|AuthResponse $authResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        JWTAuth::shouldReceive('tokenById')->andreturn(AuthLists::accessToken());
        JWTAuth::shouldReceive('getTTL')->andreturn(AuthLists::getTTL());

        Auth::shouldReceive('tokenById')->andreturn(AuthLists::accessToken());
        Auth::shouldReceive('getTTL')->andreturn(AuthLists::getTTL());

        $this->authResponseMock = $this->createMock(AuthResponse::class);
    }

    public function getAuthGenerateService(): AuthGenerateService
    {
        return new AuthGenerateService($this->authResponseMock);
    }

    public function test_should_return_authentication_object()
    {
        $authGenerateService = $this->getAuthGenerateService();

        $authUserResponse = AuthLists::getAuthUserResponse();

        $authResponse = $authGenerateService->execute($authUserResponse);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertEquals(AuthLists::accessToken(), $authResponse->accessToken);
        $this->assertEquals(AuthLists::getTTL(), $authResponse->expiresIn);
        $this->assertEquals(AuthLists::tokenType(), $authResponse->tokenType);
    }
}
