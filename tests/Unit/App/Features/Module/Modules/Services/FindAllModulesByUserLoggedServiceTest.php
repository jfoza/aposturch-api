<?php

namespace Tests\Unit\App\Features\Module\Modules\Services;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Services\FindAllModulesByUserLoggedService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MemberLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class FindAllModulesByUserLoggedServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        JWTAuth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged());
        Auth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged());
    }

    public function getFindAllModulesByUserLoggedService(): FindAllModulesByUserLoggedService
    {
        return new FindAllModulesByUserLoggedService();
    }

    public function test_should_return_active_modules_user_logged()
    {
        $findAllModulesByUserLoggedService = $this->getFindAllModulesByUserLoggedService();

        $findAllModulesByUserLoggedService->setPolicy(
            new Policy([
                RulesEnum::MODULES_VIEW->value
            ])
        );

        $modules = $findAllModulesByUserLoggedService->execute();

        $this->assertIsArray($modules);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllModulesByUserLoggedService = $this->getFindAllModulesByUserLoggedService();

        $findAllModulesByUserLoggedService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllModulesByUserLoggedService->execute();
    }
}
